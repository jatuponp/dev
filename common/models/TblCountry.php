<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_country".
 *
 * @property integer $country_id
 * @property string $country_name_th
 * @property string $country_name_eng
 * @property string $country_code
 * @property string $nation_name_eng
 */
class TblCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_name_th'], 'required'],
            [['country_name_th', 'country_name_eng', 'nation_name_eng'], 'string', 'max' => 100],
            [['country_code'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'country_name_th' => 'Country Name Th',
            'country_name_eng' => 'Country Name Eng',
            'country_code' => 'Country Code',
            'nation_name_eng' => 'Nation Name Eng',
        ];
    }
    public function makeDropDown() {
        global $data;
        $data = array();
        $data['0'] = 'เลือกประเทศ';        
         $parents = Tblcountry::find()
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->country_id] = $parent->country_name_th;
            
        }

        return $data;
    }
    public static function makeDD($all = false) {

        //$results = amsProject::findAll(["acadyear" => $acadyear]);
        $results = Tblcountry::find()->all();

        $data = array();

        ($all)? $data[0] = "เลือกประเทศ":"";

//        $data[0] = "ไม่กำหนด";

        foreach ($results as $value) {

            $data[$value->country_id] = $value->country_name_th;
        }

        return $data;
    }
}
