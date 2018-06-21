<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_district".
 *
 * @property int $district_id
 * @property string $district_name_th
 * @property string $district_name_en
 * @property int $province_id
 * @property string $full_id
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'std_ref_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['district_id', 'district_name_th', 'district_name_en', 'province_id', 'full_id'], 'required'],
            [['district_id', 'province_id'], 'integer'],
            [['district_name_th', 'district_name_en'], 'string', 'max' => 255],
            [['full_id'], 'string', 'max' => 10],
            [['district_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'district_id' => 'District ID',
            'district_name_th' => 'District Name Th',
            'district_name_en' => 'District Name En',
            'province_id' => 'Province ID',
            'full_id' => 'Full ID',
        ];
    }
    
    public function getName($id) {
        $query = District::find()->where(['district_id' => $id])->one();
        return $query->district_name_th;
    }
}
