<?php

namespace app\modules\car\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\car\CarDriver;
use common\models\Staff;

class DriverController extends \yii\web\Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete', 'stafflist'],
                        'allow' => true,
                        'roles' => ['carAdmin']
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    private function getPath() {
        return \Yii::getAlias('@common') . '/media/car/';
    }

    private function getUrl() {
        return \Yii::getAlias('@common') . '/media/car/';
    }

    public function actionIndex() {
        $model = new CarDriver();

        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $model = new CarDriver();
        if ($model->load(\Yii::$app->request->post())) {
            $request = Yii::$app->request->post('CarDriver');
            $id = $request['id'];
            if ($id) {
                $model = CarDriver::findOne($id);
                $model->attributes = $request;
            }
            $files = \yii\web\UploadedFile::getInstances($model, 'mPicture');
            if (isset($files) && count($files) > 0) {
                $mPath = $this->getPath();
                foreach ($files as $file) {
                    $mPic = 'driver_' . substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $file->extension;
                    //Upload Images
                    if ($file->saveAs($mPath . $mPic)) {
                        $image = \Yii::$app->image->load($mPath . $mPic);
                        $image->resize(300, 413);
                        $image->save($mPath . $mPic);
                        $model->picture = $mPic;
                    }
                }
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = CarDriver::findOne($id);
        } else {
            //$sess = Yii::$app->session->get('sessPersons');
            //$model->search = $sess['search'];
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = CarDriver::findOne($id);
        if ($model->delete()) {
            @unlink($this->getPath() . $model->picture);
        }
        return $this->redirect(['index']);
    }
    
    public function actionStafflist($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$out = ['more' => false];
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = Staff::find()
                    ->select(['id' => 'citizen_id', 'text' => 'CONCAT(first_thname," ",last_thname)'])
                    ->where('first_thname LIKE :s', [':s' => "%$q%"])
                    ->orWhere('last_thname LIKE :s1', [':s1' => "%$q%"])
                    ->limit(20)
                    ->asArray()
                    ->all();

            $out['results'] = $query;
        } elseif ($id > 0) {
            $sq = Staff::find(['citizen_id' => $id])->one();
            $out['results'] = ['id' => $id, 'text' => $sq->first_thname . ' ' . $sq->last_thname];
            if (trim($sq->first_thname) == '') {
                $sq = \app\models\Department::find(['id' => $id])->one();
                $out['results'] = ['id' => $id, 'text' => $sq->title];
            }
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        return $out;
    }

}
