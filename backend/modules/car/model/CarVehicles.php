<?php

namespace common\models\car;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "car_vehicles".
 *
 * @property integer $id
 * @property string $register_id
 * @property string $brand_model
 * @property string $fuel
 * @property integer $capacity
 * @property integer $status
 * @property string $picture
 * @property string $staff
 * @property string $description
 */
class CarVehicles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    var $mPicture;
    
    public static function tableName()
    {
        return 'car_vehicles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['register_id', 'brand_model', 'capacity', 'status'], 'required'],
            [['capacity', 'status'], 'integer'],
            [['description'], 'string'],
            [['register_id', 'picture'], 'string', 'max' => 50],
            [['brand_model'], 'string', 'max' => 100],
            [['staff'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'register_id' => 'หมายเลขทะเบียน',
            'brand_model' => 'ประเภทรถ',
            'capacity' => 'จำนวนที่นั่ง',
            'status' => 'สถานะ',
            'picture' => 'รูปภาพ',
            'staff' => 'ผู้รับผิดชอบรถ',
            'description' => 'รายละเอียดอื่น ๆ',
        ];
    }
    
    public function search() {
        $query = $this->find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);
        //var_dump($query->createCommand()->rawSql);

        return $dataProvider;
    }
    
    public function makeDropDown() {
        global $data;
        $data = array();
        //$data['0'] = '-- Top Level --';        
        $parents = CarVehicles::find()
                //->where(['parent_id' => 0])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->register_id. ' ' . $parent->brand_model;
        }

        return $data;
    }
}
