<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "std_ref_district".
 *
 * @property integer $district_id
 * @property string $district_name_th
 * @property string $district_name_en
 * @property integer $province_id
 * @property string $full_id
 *
 * @property AmsRegister[] $amsRegisters
 */
class stdRefDistrict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'std_ref_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['district_id', 'district_name_th', 'district_name_en', 'province_id', 'full_id'], 'required'],
            [['district_id', 'province_id'], 'integer'],
            [['district_name_th', 'district_name_en'], 'string', 'max' => 255],
            [['full_id'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsRegisters()
    {
        return $this->hasMany(AmsRegister::className(), ['district_id' => 'district_id']);
    }
    
    public function lists($lang = "th", $province_id = NULL) {
        $query = stdRefDistrict::find()->all();
        
//        if ($province_id) {
//            $query-
//        }
            //    ->all();
                
        $data = array();
        //$data['all'] = "ทั้งหมด";
        foreach ($query as $row) {

            if ($lang == "th") {
                $data[$row->district_id] = $row->province_district_th;
            } else {
                $data[$row->district_id] = $row->province_district_en;
            }
        }

        return $data;
        
        
    }
}
