<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Staff;
use common\models\stdStudentMaster;
use common\models\mlbMailboxType;

/**
 * This is the model class for table "mlb_mailbox".
 *
 * @property integer $id
 * @property string $student_id
 * @property integer $staff_id
 * @property string $receipt_date
 * @property string $get_date
 * @property string $get_time
 * @property integer $type_id
 * @property integer $status
 * @property string $note
 * @property integer $own_create
 * @property integer $own_give
 * @property string $apply_date
 */
class mlbMailbox extends \yii\db\ActiveRecord {

    public $kind; //เลือกแสดงรายการของนักศึกษาหรือบุคลากร
    public $search;
    public $datas;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mlb_mailbox';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_id', 'type_id', 'status', 'own_create', 'own_give'], 'integer'],
            [['receipt_date', 'type_id'], 'required'],
            [['receipt_date', 'get_date', 'get_time', 'apply_date'], 'safe'],
            [['note', 'emsid'], 'string'],
            [['student_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'student_id' => 'นักศึกษา',
            'staff_id' => 'บุคลากร',
            'receipt_date' => 'รับเข้าระบบ',
            'get_date' => 'จ่ายออก',
            'get_time' => 'เวลา',
            'type_id' => 'ประเภท',
            'status' => 'สถานะ',
            'emsid' => 'หมายเลข EMS',
            'note' => 'หมายเหตุ',
            'own_create' => 'Own Create',
            'own_give' => 'Own Give',
            'apply_date' => 'Apply Date',
        ];
    }
    
    public function getStaff() {
        return $this->hasOne(Staff::className(), ['citizen_id' => 'staff_id']);
    }
    
    public function getStaffName() {
        return $this->staff->first_thname;
    }
    
    public function getStudent() {
        return $this->hasOne(stdStudentMaster::className(), ['studentcode' => 'student_id']);
    }
    
    public function getSfullname() {
        return $this->student->studentname . ' ' . $this->student->studentsurname;
    }
    
    public function getStudentcode() {
        return $this->student->studentcode;
    }
    
    public function getMailtype() {
        return $this->hasOne(mlbMailboxType::className(), ['id' => 'type_id']);
    }
    
    public function getTypename() {
        return $this->mailtype->type_name;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $now = date('Y-m-d H:i:s');
                $this->apply_date = $now;
                $this->status = 0;
                $this->own_create = Staff::getStaffCitizen();
            }
            return true;
        }
        return false;
    }

    public function searchAll($status = 0) {
        $query = $this->find();
        $query->where(['status' => $status]);
        if($this->emsid){
            $query->andWhere(['emsid' => $this->emsid]);
        }
        
        if ($this->receipt_date)
            $query->andWhere(['receipt_date' => $this->receipt_date]);

        if ($this->kind == 1) {
            //เลือกนักศึกษา
            $query->andWhere(['staff_id' => 0]);
        } else if ($this->kind == 2) {
            //บุคลากร
            $query->andWhere(['student_id' => 0]);
        }

        $query->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

}
