<?php

namespace common\models\admission;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ams_project_province".
 *
 * @property integer $proj_id
 * @property integer $open_id
 * @property integer $province_id
 *
 * @property StdRefProvince $province
 * @property AmsProject $proj
 * @property AmsProjectOpen $open
 */
class amsProjectProvince extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ams_project_province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proj_id', 'open_id', 'province_id'], 'required'],
            [['proj_id', 'open_id', 'province_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'proj_id' => 'Proj ID',
            'open_id' => 'Open ID',
            'province_id' => 'Province ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(StdRefProvince::className(), ['province_id' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProj()
    {
        return $this->hasOne(AmsProject::className(), ['proj_id' => 'proj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpen()
    {
        return $this->hasOne(AmsProjectOpen::className(), ['open_id' => 'open_id']);
    }
    
    public function lists($proj_id, $open_id = NULL) {

        $query = NEW Query();
        $query->select(["*", "std_ref_province.province_id AS province_id"])
                ->from(["ams_project_province"])
                ->innerJoin("std_ref_province", "std_ref_province.province_id = ams_project_province.province_id")
                ->where([
                    "proj_id" => $proj_id,
                    "open_id" => $open_id
                ])
                ->orderBy("std_ref_province.province_name_th ASC");

//        if ($open_id) {
//            $query->andWhere([
//                "open_id" => $open_id
//            ]);
//        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }
}
