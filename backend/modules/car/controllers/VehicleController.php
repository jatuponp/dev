<?php

namespace app\modules\car\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\car\CarVehicles;

class VehicleController extends \yii\web\Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete'],
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
        $model = new CarVehicles();

        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $model = new CarVehicles();
        if ($model->load(\Yii::$app->request->post())) {
            $request = Yii::$app->request->post('CarVehicles');
            $id = $request['id'];
            if ($id) {
                $model = CarVehicles::findOne($id);
                $model->attributes = $request;
            }
            $files = \yii\web\UploadedFile::getInstances($model, 'mPicture');
            if (isset($files) && count($files) > 0) {
                $mPath = $this->getPath();
                foreach ($files as $file) {
                    $mPic = 'car_' . substr(number_format(time() * rand(), 0, '', ''), 0, 14) . '.' . $file->extension;
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
            $model = CarVehicles::findOne($id);
        } else {
            //$sess = Yii::$app->session->get('sessPersons');
            //$model->search = $sess['search'];
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = CarVehicles::findOne($id);
        if ($model->delete()) {
            @unlink($this->getPath() . $model->picture);
        }
        return $this->redirect(['index']);
    }

}
