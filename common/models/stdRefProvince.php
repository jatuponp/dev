<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "std_ref_province".
 *
 * @property integer $province_id
 * @property string $province_name_th
 * @property string $province_name_en
 * @property string $full_id
 * @property string $region_id
 * @property string $provinceid
 *
 * @property AmsRegister[] $amsRegisters
 */
class stdRefProvince extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'std_ref_province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'province_name_th', 'province_name_en', 'full_id', 'region_id', 'provinceid'], 'required'],
            [['province_id', 'provinceid'], 'integer'],
            [['province_name_th', 'province_name_en'], 'string', 'max' => 255],
            [['full_id', 'region_id'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsRegisters()
    {
        return $this->hasMany(AmsRegister::className(), ['province_id' => 'province_id']);
    }
    
    public function lists($lang = "th") {
        $query = stdRefProvince::find()
                ->all();
                
        $data = array();
        //$data['all'] = "ทั้งหมด";
        foreach ($query as $row) {

            if ($lang == "th") {
                $data[$row->province_id] = $row->province_name_th;
            } else {
                $data[$row->province_id] = $row->province_name_en;
            }
        }

        return $data;
        
        
    }
    
    public static function makeDD($all = FALSE) {

        $results = stdRefProvince::find()
                ->select(["province_id", "province_name_th"])
                //->where("register_name IS NOT NULL or register_name <> ''")
                ->all();

        $data = array();

        if ($all)
            $data["all"] = "ทั้งหมด";

        foreach ($results as $value) {
            $data[$value->province_id] = $value->province_name_th;
        }

        //$data = Yii::$app->getModule("admission")->facultyid;
        //$data = $results;
        return $data;
    }
    
    public static function getName($province_id = NULL) {
        $query= stdRefProvince::findOne(["province_id" => $province_id]);
        
        return $query->province_name_th;
        
    }
}
