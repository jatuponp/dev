<?php

namespace common\models\car;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "car_requestcar".
 *
 * @property integer $id
 * @property string $citizen_id
 * @property integer $gov_id
 * @property string $department
 * @property string $telephone
 * @property string $document_no
 * @property string $document_date
 * @property string $objective
 * @property string $objective_at
 * @property string $tambon
 * @property string $ampure
 * @property string $province
 * @property integer $traveller_total
 * @property string $traveller
 * @property string $travel_date
 * @property string $travel_time_begin
 * @property string $travel_time_end
 * @property string $return_date
 * @property string $return_time_end
 * @property string $pickup_at
 * @property string $pickup_time
 * @property string $contact_phone
 * @property string $contact_email
 * @property string $status
 * @property string $status_notice
 * @property string $created
 * @property string $modified
 */
class CarRequestcar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $persons;
    
    public static function tableName()
    {
        return 'car_requestcar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gov_id', 'traveller_total'], 'integer'],
            [['objective', 'objective_at', 'traveller_total', 'travel_time_begin', 'pickup_at', 'pickup_time'], 'required'],
            [['document_date', 'travel_date', 'travel_time_begin', 'travel_time_end', 'return_date', 'return_time_end', 'pickup_time', 'created', 'modified'], 'safe'],
            [['citizen_id'], 'string', 'max' => 20],
            [['approve','department', 'objective', 'traveller', 'status_notice'], 'string', 'max' => 500],
            [['telephone', 'document_no'], 'string', 'max' => 50],
            [['objective_at', 'tambon', 'ampure', 'province', 'pickup_at'], 'string', 'max' => 250],
            [['contact_phone', 'contact_email'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'citizen_id' => 'Citizen ID',
            'gov_id' => 'Gov ID',
            'approve' => 'เรียน',
            'department' => 'ส่วนราชการ',
            'telephone' => 'โทร',
            'document_no' => 'หนังสือเลขที่',
            'document_date' => 'วันที่',
            'objective' => 'วัตถุประสงค์',
            'objective_at' => 'ณ. ',
            'tambon' => 'ตำบล',
            'ampure' => 'อำเภอ',
            'province' => 'จังหวัด',
            'traveller_total' => 'จำนวนผู้ร่วมเดินทาง',
            'traveller' => 'ผู้ร่วมเดินทาง',
            'travel_date' => 'ระหว่างวันที่',
            'travel_time_begin' => 'เวลา',
            'travel_time_end' => 'ถึงเวลา',
            'return_date' => 'และถึงวันที่',
            'return_time_end' => 'เวลา',
            'pickup_at' => 'รถไปรับผู้ใช้ที่',
            'pickup_time' => 'เวลา',
            'contact_phone' => 'ติดต่อเบอร์โทร',
            'contact_email' => 'E-mail',
            'status' => 'สถานะ',
            'status_notice' => 'เนื่องจาก',
            'created' => 'Created',
            'modified' => 'Modified',
            'persons' => 'ผู้ร่วมเดินทาง'
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->citizen_id = Yii::$app->user->identity->citizen_id;
                $this->created = $now;
                $this->modified = $now;
            }else{
                $this->modified = $now;
            }
            return true;
        }
        return false;
    }
    
    public function search() {
        $query = $this->find();
        $query->where(['booking_id' => null]);
        $query->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        //var_dump($query->createCommand()->rawSql);

        return $dataProvider;
    }
    
    public function getStatus($id, $txtonly =  false) {
        $array = ['0' => 'จอง','1' => 'รับเรื่อง','2' => 'ดำเนินการ','3' => 'อนุมัติ','4' => 'ไม่อนุมัติ', '5' => 'ยกเลิกการจอง'];
        $class = ['0' => 'btn btn-primary','1' => 'btn btn-warning','2' => 'btn btn-info','3' => 'btn btn-success','4' => 'btn btn-danger','5' => 'btn btn-danger'];
        if($txtonly){
            $html = $array[$id];
        }else{
            $html = "<button type=\"button\" class=\"{$class[$id]}\">{$array[$id]}</button>";
        }
        return $html;
    }
}