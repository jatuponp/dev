<?php

namespace common\models\finance;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "fin_store".
 *
 * @property integer $id
 * @property integer $room_no
 * @property string $agreement
 * @property string $prefix
 * @property string $firstname
 * @property string $lastname
 * @property string $store_type
 * @property string $store_begin
 * @property string $store_end
 * @property string $location
 * @property string $store_rate
 */
class FinStore extends \yii\db\ActiveRecord {

    public $search;
    public $month;
    public $year;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'fin_store';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['room_no'], 'string', 'max' => 50],
            [['store_type', 'store_begin', 'store_end', 'location', 'store_rate'], 'required'],
            [['company'], 'string', 'max' => 512],
            [['store_begin', 'store_end'], 'safe'],
            [['store_rate'], 'number'],
            [['agreement', 'prefix'], 'string', 'max' => 100],
            [['firstname', 'lastname', 'store_type', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'room_no' => 'หมายเลขห้อง',
            'agreement' => 'สัญญาเลขที่',
            'prefix' => 'คำนำหน้า',
            'firstname' => 'ชื่อ',
            'lastname' => 'นามสกุล',
            'company' => 'ชื่อบริษัท',
            'store_type' => 'ประเภทร้าน',
            'store_begin' => 'วันทำสัญญา',
            'store_end' => 'สิ้นสุดสัญญา',
            'location' => 'สถานที่ตั้ง',
            'store_rate' => 'ค่าเช่า',
            'ratetype' => 'ต่อ'
        ];
    }

    public function search() {
        $query = $this->find();
        $search = $this->search;
        if ($search) {
            $query->where(['OR', ['LIKE', 'room_no', $search], ['LIKE', 'firstname', $search], ['LIKE', 'lastname', $search]]);
        }
        $query->orderBy('id ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    public function getFullname() {
        $arr = array("นาย", "นาง", "นางสาว");
        $fullname = (($this->company)? $this->company:$arr[$this->prefix] . $this->firstname . ' ' . $this->lastname);
        return $fullname;
    }

}
