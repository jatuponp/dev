<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_province".
 *
 * @property int $province_id
 * @property string $province_name_th
 * @property string $province_name_en
 * @property string $full_id
 * @property string $region_id
 * @property int $provinceid
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'std_ref_province';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_id', 'province_name_th', 'province_name_en', 'full_id', 'region_id', 'provinceid'], 'required'],
            [['province_id', 'provinceid'], 'integer'],
            [['province_name_th', 'province_name_en'], 'string', 'max' => 255],
            [['full_id', 'region_id'], 'string', 'max' => 10],
            [['province_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'province_id' => 'Province ID',
            'province_name_th' => 'Province Name Th',
            'province_name_en' => 'Province Name En',
            'full_id' => 'Full ID',
            'region_id' => 'Region ID',
            'provinceid' => 'Provinceid',
        ];
    }
    
    public function makeDropDown() {
        $query = Province::find();
        $result = $query->all();
        foreach ($result as $m) {
            $data[$m->province_id] = $m->province_name_th;
        }

        return $data;
    }
    
    public function getName($id) {
        $query = Province::find()->where(['province_id' => $id])->one();
        return $query->province_name_th;
    }
}