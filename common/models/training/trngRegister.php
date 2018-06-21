<?php

namespace common\models\training;

use Yii;

/**
 * This is the model class for table "trng_register".
 *
 * @property integer $reg_id
 * @property string $email
 * @property string $citizen_id
 * @property string $title
 * @property string $first_name
 * @property string $last_name
 * @property string $department
 * @property string $position
 * @property string $tel
 * @property string $modified
 *
 * @property TrngPaticipant[] $trngPaticipants
 */
class trngRegister extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'trng_register';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'citizen_id', 'title', 'first_name', 'last_name', 'department', 'tel'], 'required'],
            [['modified'], 'safe'],
            [['email'], 'string', 'max' => 80],
            [['citizen_id'], 'string', 'max' => 13],
            [['title'], 'string', 'max' => 20],
            [['first_name', 'last_name', 'position'], 'string', 'max' => 100],
            [['department'], 'string', 'max' => 200],
            [['tel'], 'string', 'max' => 10],
            [['email', 'citizen_id'], 'unique'],
            [['citizen_id'], 'validateCitizenid'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'reg_id' => 'Reg ID',
            'email' => 'Email',
            'citizen_id' => 'หมายเลขบัตร ปชช.',
            'title' => 'คำนำหน้า',
            'first_name' => 'ชื่อ',
            'last_name' => 'นามสกุล',
            'department' => 'หน่วยงาน / บริษัท',
            'position' => 'ตำแหน่ง',
            'tel' => 'มือถือ',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrngPaticipants() {
        return $this->hasMany(TrngPaticipant::className(), ['reg_id' => 'reg_id']);
    }

    public function validateCitizenid() {
        $citizen_id = $this->citizen_id;
        $rev = strrev($citizen_id); // reverse string ขั้นที่ 0 เตรียมตัว
        $total = 0;
        for ($i = 1; $i < 13; $i++) { // ขั้นตอนที่ 1 - เอาเลข 12 หลักมา เขียนแยกหลักกันก่อน
            $mul = $i + 1;
            $count = $rev[$i] * $mul; // ขั้นตอนที่ 2 - เอาเลข 12 หลักนั้นมา คูณเข้ากับเลขประจำหลักของมัน
            $total = $total + $count; // ขั้นตอนที่ 3 - เอาผลคูณทั้ง 12 ตัวมา บวกกันทั้งหมด
        }
        $mod = $total % 11; //ขั้นตอนที่ 4 - เอาเลขที่ได้จากขั้นตอนที่ 3 มา mod 11 (หารเอาเศษ)
        $sub = 11 - $mod; //ขั้นตอนที่ 5 - เอา 11 ตั้ง ลบออกด้วย เลขที่ได้จากขั้นตอนที่ 4
        $check_digit = $sub % 10; //ถ้าเกิด ลบแล้วได้ออกมาเป็นเลข 2 หลัก ให้เอาเลขในหลักหน่วยมาเป็น Check Digit

        if ($rev[0] != $check_digit) {// ตรวจสอบ ค่าที่ได้ กับ เลขตัวสุดท้ายของ บัตรประจำตัวประชาชน
            $this->addError('citizen_id', "\"$citizen_id\" เลขที่บัตรประชาชนไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง");
        }
//        $this->addError('citizen_id', 'Incorrect username or password.' . $this->citizen_id);
    }

//     public function validateEmail()
//    {
//        $email = $this->email;
////        $reg = trngRegister::findOne(['email' => $email]);
//        
////        if (empty($reg)) {// ตรวจสอบ ค่าที่ได้ กับ เลขตัวสุดท้ายของ บัตรประจำตัวประชาชน
////            $this->addError('email',  "\"$email\" นี้มีแล้วในระบบ กรุณาตรวจสอบอีกครั้ง");
////        } 
//
//    }
//    public static function lists($search = NULL) {
//
//        $query = trngRegister::find()
////                ->andWhere(["course_name" => $search]);
//                ->andFilterWhere([
//            'or',
////            ["course_name" => $search],
//            ["like", "course_name", $search],
////            ["like", "tbl_staff.last_thname", $search]
//        ]);
//
//        $dataProvider = new \yii\data\ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 25,
//            ],
//        ]);
//
//        return $dataProvider;
//    }
}
