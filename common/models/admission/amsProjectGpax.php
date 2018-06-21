<?php

namespace common\models\admission;

use Yii;
use common\models\amsProject;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ams_project_gpax".
 *
 * @property integer $proj_id
 * @property double $gpax
 *
 * @property AmsProject $proj
 */
class amsProjectGpax extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_project_gpax';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['proj_id', 'gpax'], 'required'],
            [['proj_id'], 'integer'],
            [['gpax'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'proj_id' => 'โครงการรับเข้า',
            'gpax' => 'Gpax',
            'projectname' => 'โครงการรับเข้า',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProj() {
        return $this->hasOne(amsProject::className(), ['proj_id' => 'proj_id']);
    }
    
    public function getProjectname() {
        return $this->proj->nameth;
////      OR  $customer->getDept()->andWhere('status=1')->all() 
    }

    public function lists($provider = FALSE) {
        $query = amsProjectGpax::find();
        //->orderBy("facultyid ASC");

        if ($provider) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            return $dataProvider;
        } else {

            return $query;
        }
    }

}
