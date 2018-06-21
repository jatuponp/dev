<?php

namespace app\modules\car\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\car\CarRequestcar;
use common\models\car\CarBooking;
use yii\helpers\Json;

/**
 * Default controller for the `car` module
 */
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'booking', 'book', 'list', 'delete', 'getdistrict', 'bookshare', 'cancel', 'many', 'schedule', 'editschedule', 'deleteschedule'],
                        'allow' => true,
                        'roles' => ['carAdmin']
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $model = new CarBooking();
        return $this->render('index', ['model' => $model]);
    }

    public function actionList() {
        $model = new CarBooking();

        return $this->render('list', ['model' => $model]);
    }

    public function actionBooking() {
        $model = new CarRequestcar();

        return $this->render('booking', ['model' => $model]);
    }

    public function actionBook($id = null, $reqId = null) {
        if ($reqId) {
            $carRequest = CarRequestcar::findOne($reqId);
            $model = CarBooking::find()->where(['reqId' => $reqId])->one();

            if ($model->reqId) {
                $values = explode(',', $model->driver_id);
                foreach ($values as $value) {
                    if ($value) {
                        $inits[] = $value;
                    }
                }
            } else {
                $model = new CarBooking();
                $model->reqId = $carRequest->id;
                $model->title = $carRequest->objective;
                $model->place = $carRequest->objective_at;
                $model->amphoe = $carRequest->ampure;
                $model->province = $carRequest->province;
                $model->travel_date = $carRequest->travel_date;
                $model->travel_time = $carRequest->travel_time_begin;
                $model->return_date = $carRequest->return_date;
                $model->return_time = $carRequest->return_time_end;
                $model->pickup_at = $carRequest->pickup_at;
                $model->pickup_time = $carRequest->pickup_time;
            }
        } else if ($id) {
            $model = CarBooking::findOne($id);
            $carRequest = CarRequestcar::findOne($model->reqId);
        } else {
            $model = new CarBooking();
        }

        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->getRequest()->post('CarBooking');
            if ($request['id']) {
                $model = CarBooking::findOne($request['id']);
                $model->attributes = $request;
            }
            $driver = $request['driver'];
            if ($driver) {
                $model->driver_id = NULL;
                foreach ($driver as $d) {
                    $model->driver_id .= $d . ',';
                }
            }
            $model->parent = 0;

            if ($model->save()) {
                //ปรับปรุง booking_id ในตารางจองรถยนต์
                $carRequest->booking_id = $model->id;
                $carRequest->status = 3;
                $carRequest->save();
                //บันทึกรายการจัดรถยนต์ภายใต้การจัดรถยนต์หลัก
                $travelDate = $request['tra_date'];
                $travelTime = $request['tra_time'];
                $returnDate = $request['ret_date'];
                $returnTime = $request['ret_time'];
                if (is_array($travelDate)) {
                    $i = 0;
                    foreach ($travelDate as $tD) {
                        $SB = new CarBooking();
                        $SB->parent = $model->id;
                        $SB->reqId = $model->reqId;
                        $SB->title = $model->title;
                        $SB->place = $model->place;
                        $SB->amphoe = $model->amphoe;
                        $SB->province = $model->province;
                        $SB->travel_date = $tD;
                        $SB->travel_time = $travelTime[$i];
                        $SB->return_date = $returnDate[$i];
                        $SB->return_time = $returnTime[$i];
                        $SB->car_id = $model->car_id;
                        $SB->kms = $model->kms;
                        $SB->driver_id = $model->driver_id;
                        $SB->pickup_at = $model->pickup_at;
                        $SB->pickup_time = $model->pickup_time;
                        echo $tD . " <br/>" . $travelTime[$i] . " <br/>" . $returnDate[$i] . " <br/>" . $returnTime[$i] . " <br/>";
                        //exit();

                        if (!$SB->save()) {
                            print_r($SB->getErrors());
                            exit();
                        }
                        $i++;
                    }
                }

                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        //บันทึกสถานะ เปิดอ่านแล้ว
        $carRequest->status = 1;
        $carRequest->save() ? "" : exit();

        if (\Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap4\BootstrapPluginAsset' => false,
                'yii\bootstrap4\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false
            ];
            return $this->renderAjax('book', [
                        'model' => $model, 'reqCar' => $carRequest
            ]);
        } else {
            return $this->renderAjax('book', [
                        'model' => $model, 'reqCar' => $carRequest
            ]);
        }
    }

    public function actionBookshare($id = null, $reqId = null) {
        $model = new CarBooking();
        if ($id) {
            //ตรวจสอบวันเวลาตรงกันกับรายการรถยนต์หลัก ถ้าไม่ตรงกันแสดงว่าเดินทางร่วมกันไม่ได้
            //ตรวจสอบความจุ
            $reqCar = CarRequestcar::findOne($reqId);
            $reqCar->booking_id = $id;
            $reqCar->status = 3;
            $reqCar->save();

            return $this->renderAjax('bookconfirm', [
                        'model' => $model,
            ]);
        }

        if (\Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap4\BootstrapPluginAsset' => false,
                'yii\bootstrap4\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false
            ];
            return $this->renderAjax('bookshare', [
                        'model' => $model,
            ]);
        } else {
            return $this->renderAjax('bookshare', [
                        'model' => $model,
            ]);
        }
    }

    public function actionMany($id) {
        $model = CarRequestcar::find()->where(['booking_id' => $id])->all();
        if (\Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap4\BootstrapPluginAsset' => false,
                'yii\bootstrap4\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false
            ];
            return $this->renderAjax('many', [
                        'model' => $model,
            ]);
        } else {
            return $this->renderAjax('many', [
                        'model' => $model,
            ]);
        }
    }

    public function actionSchedule($id) {
        $model = CarBooking::findOne($id);
        if (\Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap4\BootstrapPluginAsset' => false,
                'yii\bootstrap4\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false
            ];
            return $this->renderAjax('schedule', [
                        'model' => $model,
            ]);
        } else {
            return $this->renderAjax('schedule', [
                        'model' => $model,
            ]);
        }
    }

    public function actionEditschedule($id) {
        $model = CarBooking::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $req = Yii::$app->request->post('CarBooking');
            if ($req['id']) {
                $model = CarBooking::findOne($req['id']);
                $model->attributes = $req;
            }
            $driver = $req['driver'];
            if ($driver) {
                $model->driver_id = NULL;
                foreach ($driver as $d) {
                    $model->driver_id .= $d . ',';
                }
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->save()) {
                $sch = CarBooking::findOne(($model->parent == 0) ? $model->id : $model->parent);
                return $this->renderAjax('schedule', [
                            'model' => $sch,
                ]);
            }
            return ['errors' => \yii\widgets\ActiveForm::validate($model)];
        }
        if (\Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap4\BootstrapPluginAsset' => false,
                'yii\bootstrap4\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false
            ];
            return $this->renderAjax('editschedule', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('editschedule', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDeleteschedule($id) {
        $model = CarBooking::findOne($id);
        $idx = ($model->parent == 0) ? $model->id : $model->parent;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->delete()) {            
            $sch = CarBooking::findOne($idx);
            return $this->renderAjax('schedule', [
                        'model' => $sch,
            ]);
        }
    }
    
    public function actionCancel($id) {
        $model = CarBooking::findOne($id);
        if($model->delete()){
            
            //ยกเลิกการจัดรถยนต์ในรายการจองรถยนต์
            $req = CarRequestcar::find()->where(['booking_id' => $id])->all();
            foreach ($req as $r){
                $r->booking_id = null;
                $r->status = 1;
                $r->status_notice = null;
                $r->save();
            }
            
            return $this->redirect(['list']);
        }
    }

    public function actionGetdistrict() {
        $this->enableCsrfValidation = false;
        $arr = array();
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $province_id = $parents[0];

                $district = \common\models\District::find()
                        ->where(['province_id' => $province_id])
                        ->all();
                foreach ($district as $d) {
                    $data = [];
                    $data['id'] = $d->district_id;
                    $data['name'] = $d->district_name_th;
                    $arr[] = $data;
                }

                echo Json::encode(['output' => $arr, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

}
