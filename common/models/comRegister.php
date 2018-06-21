<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ams_register".
 *
 * @property integer $reg_id
 * @property integer $prefixid
 * @property string $nameth
 * @property string $surnameth
 * @property string $sex
 * @property string $citizen_id
 * @property string $addr1
 * @property string $addr2
 * @property integer $district_id
 * @property integer $province_id
 * @property string $postal_code
 * @property string $tel
 * @property string $pathimage
 * @property string $edu_status
 * @property string $school_id
 * @property string $school_name
 * @property string $acadyear
 * @property double $GPAX
 * @property integer $shool_province_id
 * @property string $programcode
 * @property integer $proj_id
 * @property integer $open_id
 * @property integer $running
 * @property string $ref2
 * @property string $status
 * @property string $confirm
 * @property string $verify
 * @property string $modified
 *
 * @property AmsGpa[] $amsGpas
 * @property AmsIncludeDocument[] $amsIncludeDocuments
 * @property AmsProgram $programcode0
 * @property StdRefDistrict $district
 * @property StdRefProvince $province
 * @property AmsProject $proj
 * @property StdPrefix $prefix
 */
class comRegister extends \yii\db\ActiveRecord {

    public $search_pay, $search_student, $date_from, $date_to, $chk_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_register';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['prefixid', 'nameth', 'surnameth', 'sex', 'citizen_id', 'addr1', 'district_id', 'province_id', 'postal_code', 'tel', 'edu_status', 'school_id', 'acadyear', 'GPAX', 'shool_province_id', 'programcode', 'proj_id', 'open_id'], 'required'],
            [['prefixid', 'district_id', 'province_id', 'shool_province_id', 'proj_id', 'open_id', 'running'], 'integer'],
            [['sex', 'edu_status'], 'string'],
            [['GPAX'], 'number'],
            [['modified'], 'safe'],
            [['nameth', 'surnameth'], 'string', 'max' => 100],
            [['citizen_id'], 'string', 'max' => 13],
            [['addr1', 'addr2', 'pathimage'], 'string', 'max' => 255],
            [['postal_code'], 'string', 'max' => 10],
            [['tel', 'ref2', 'verify'], 'string', 'max' => 20],
            [['school_id'], 'string', 'max' => 11],
            [['school_name'], 'string', 'max' => 80],
            [['acadyear'], 'string', 'max' => 4],
            [['programcode'], 'string', 'max' => 6],
            [['status'], 'string', 'max' => 60],
            [['confirm'], 'string', 'max' => 1],
            [['citizen_id'], 'unique', 'targetAttribute' => ['citizen_id', 'proj_id', 'open_id'], 'message' => 'The combination of Citizen ID, Proj ID and Open ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'reg_id' => 'เลขที่สมัคร',
            'prefixid' => 'คำนำหน้า',
            'nameth' => 'ชื่อ',
            'surnameth' => 'นามสกุล',
            'sex' => 'เพศ',
            'citizen_id' => 'หมายเลขบัตรประชาชน',
            'addr1' => 'ที่อยู่',
            'addr2' => 'Addr2',
            'district_id' => 'อำเภอ/เขต',
            'province_id' => 'จังหวัด',
            'postal_code' => 'รหัสไปรณีย์',
            'tel' => 'เบอร์โทร',
            'pathimage' => 'Pathimage',
            'edu_status' => 'สถานภาพการศึกษา',
            'school_id' => 'รหัสโรงเรียน',
            'school_name' => 'โรงเรียน',
            'acadyear' => 'ปีการศึกษา',
            'GPAX' => 'Gpax',
            'shool_province_id' => 'จังหวัด',
            'programcode' => 'สาขาวิชา',
            'proj_id' => 'Proj ID',
            'open_id' => 'Open ID',
            'running' => 'Running',
            'ref2' => 'Ref2',
            'status' => 'สถานะ',
            'confirm' => 'ยืนยัน',
            'verify' => 'ตรวจสอบ',
            'modified' => 'Modified',
            'chk_date' => 'กำหนดวันที่',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBill($proj_id = NULL, $open_id = NULL, $citizen_id = NULL) {

        //\app\modules\admission\models\amsRegister::find()

        $query = NEW Query();
        $query->select(['ams_register.*', 'ams_gpa.*', 'prefixname', 'ams_project.nameth AS proj_name', 'ams_project.acadyear AS proj_acadyear', 'province_name_th', 'district_name_th', 'programname', 'ams_school.school_name AS sch_name'])
                ->from("ams_register")
                ->innerJoin("ams_gpa", 'ams_gpa.citizen_id = ams_register.citizen_id AND ams_gpa.proj_id = ams_register.proj_id')
                ->innerJoin("std_prefix", "std_prefix.prefixid = ams_register.prefixid")
                ->innerJoin("ams_project", "ams_project.proj_id = ams_register.proj_id")
                ->innerJoin("std_ref_province", "std_ref_province.province_id = ams_register.province_id")
                ->innerJoin("std_ref_district", "std_ref_district.district_id = ams_register.district_id")
                ->innerJoin("ams_school", "ams_school.school_id = ams_register.school_id")
                ->innerJoin("ams_program", "ams_program.programcode = ams_register.programcode")
                ->where([
                    'ams_register.proj_id' => $proj_id,
                    'ams_register.open_id' => $open_id,
                    'ams_register.citizen_id' => $citizen_id
                ])
//                ->createCommand()
//                ->queryOne()
        ;
        $command = $query->createCommand();
        $results = $command->queryOne();

        return $results;
    }

    public function getIncludeDocs($proj_id = NULL, $open_id = NULL, $citizen_id = NULL) {
        $query = NEW Query();
        $query->select(['document'])
                ->from("ams_include_document")
                ->innerJoin("ams_documents", "ams_documents.doc_id = ams_include_document.doc_id")
                ->where([
                    'ams_include_document.proj_id' => $proj_id,
                    'ams_include_document.open_id' => $open_id,
                    'ams_include_document.citizen_id' => $citizen_id])
                ->orderBy('seq');

        $command = $query->createCommand();
        $results = $command->queryAll();

        return $results;
    }

    public function listsPayment($proj_id = NULL, $open_id = NULL, $citizen_id = NULL) {
        $query = NEW Query();

        $query->select(['ams_register.*', "CONCAT(prefixname,ams_register.nameth, ' ', ams_register.surnameth) AS fullname", 'ams_project.nameth AS proj_name', 'ams_project.acadyear AS proj_acadyear', 'programname'])
                ->from("ams_register")
                ->innerJoin("std_prefix", "std_prefix.prefixid = ams_register.prefixid")
                ->innerJoin("ams_project", "ams_project.proj_id = ams_register.proj_id")
                ->innerJoin("ams_program", "ams_program.programcode = ams_register.programcode")
//                ->innerJoin('ams_banks', "ams_banks.ref2=ams_register.ref2")
                //->where(['ams_register.proj_id' => $proj_id, 'ams_register.open_id' => $open_id])
                //->where(['ams_register.citizen_id' => $citizen_id])
                ->Where(['payment' => 'Y']);
//                ->createCommand()
//                ->queryAll();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

    public function getRegister($citizen_id, $proj_id, $open_id) {
        $query = NEW Query();

        $query->select(['ams_register.*', 'ams_gpa.*', 'prefixname', 'ams_project.nameth AS proj_name', 'ams_project.acadyear AS proj_acadyear', 'province_name_th', 'district_name_th', 'programname', 'ams_school.school_name AS sch_name'])
                ->from("ams_register")
                ->innerJoin("ams_project", "ams_project.proj_id = ams_register.proj_id")
                ->innerJoin("std_prefix", "std_prefix.prefixid = ams_register.prefixid")
                ->innerJoin("std_ref_province", "std_ref_province.province_id = ams_register.province_id")
                ->innerJoin("std_ref_district", "std_ref_district.district_id = ams_register.district_id")
                ->innerJoin("ams_school", "ams_school.school_id = ams_register.school_id")
                ->innerJoin("ams_programcode", "ams_programcode.programcode = ams_register.programcode")
                ->innerJoin("ams_gpa", "ams_gpa.citizen_id = ams_register.citizen_id AND ams_gpa.proj_id = ams_register.proj_id AND ams_gpa.open_id = ams_register.open_id")
                ->where([
                    'ams_register.proj_id' => $proj_id,
                    'ams_register.open_id' => $open_id,
                    'ams_register.citizen_id' => $citizen_id
                ]);

        $query->createCommand()->queryOne();

        return $query;
    }

}
