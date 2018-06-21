<?php

namespace common\models;

use Yii;
use common\models\stdStudentBio;
use common\models\stdPrefix;

/**
 * This is the model class for table "std_studentmaster".
 *
 * @property string $studentid
 * @property string $studentcode
 * @property integer $acadid
 * @property integer $campusid
 * @property integer $levelid
 * @property integer $facultyid
 * @property integer $departmentid
 * @property string $programid
 * @property string $minorprogramid
 * @property integer $feegroupid
 * @property integer $feemultiply
 * @property integer $prefixid
 * @property string $studentname
 * @property string $studentnameeng
 * @property string $studentsurname
 * @property string $studentsurnameeng
 * @property integer $creditattempt
 * @property integer $creditsatisfy
 * @property integer $creditpoint
 * @property integer $gradepoint
 * @property integer $gpa
 * @property integer $admitacadyear
 * @property integer $admitsemester
 * @property string $admitdate
 * @property string $finishdate
 * @property string $studentgroup
 * @property string $studentpassword
 * @property string $studentemail
 * @property integer $studentyear
 * @property integer $studentstatus
 * @property string $officerid
 * @property string $financestatus
 * @property string $lastupdateuserid
 * @property string $lastupdatedatetime
 * @property integer $schedulegroupid
 * @property string $cardid
 * @property string $webflag
 * @property string $parentpassword
 * @property integer $branchid
 * @property string $oldstudentid
 *
 * @property StdStudentbio $stdStudentbio
 */
class stdStudentMaster extends \yii\db\ActiveRecord {
    
    public $confirm;
    public $passwd;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'std_studentmaster';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['studentid', 'studentcode', 'acadid', 'campusid', 'levelid', 'facultyid', 'departmentid', 'programid', 'minorprogramid', 'feegroupid', 'feemultiply', 'prefixid', 'studentname', 'studentnameeng', 'studentsurname', 'studentsurnameeng', 'creditattempt', 'creditsatisfy', 'creditpoint', 'gradepoint', 'gpa', 'admitacadyear', 'admitsemester', 'admitdate', 'finishdate', 'studentgroup', 'studentpassword', 'studentemail', 'studentyear', 'studentstatus', 'officerid', 'financestatus', 'lastupdateuserid', 'lastupdatedatetime', 'schedulegroupid', 'cardid', 'webflag', 'parentpassword', 'branchid', 'oldstudentid'], 'required'],
            [['studentid', 'acadid', 'campusid', 'levelid', 'facultyid', 'departmentid', 'programid', 'minorprogramid', 'feegroupid', 'feemultiply', 'prefixid', 'creditattempt', 'creditsatisfy', 'creditpoint', 'gradepoint', 'gpa', 'admitacadyear', 'admitsemester', 'studentyear', 'studentstatus', 'officerid', 'schedulegroupid', 'branchid', 'oldstudentid'], 'integer'],
            [['admitdate', 'finishdate', 'lastupdatedatetime'], 'safe'],
            [['studentcode', 'studentgroup', 'cardid', 'webflag'], 'string', 'max' => 20],
            [['studentname', 'studentnameeng', 'studentsurname', 'studentsurnameeng'], 'string', 'max' => 200],
            [['studentpassword', 'lastupdateuserid', 'parentpassword'], 'string', 'max' => 50],
            [['studentemail'], 'string', 'max' => 100],
            [['financestatus'], 'string', 'max' => 10],
            ['studentemail', 'filter', 'filter' => 'trim'],
            ['studentemail', 'email'],
            ['studentemail', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup'],
            ['studentemail', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],
            [['passwd','confirm'], 'required'],
            ['passwd', 'string', 'min' => 8],
            ['confirm', 'compare', 'compareAttribute' => 'passwd'],
            [['passwd','confirm'], 'match', 'pattern' => '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{8,}$/', 'message' => 'รหัสผ่านต้องประกอบด้วย ตัวพิมพ์เล็ก ตัวพิมพ์ใหญ่ ตัวเลข และไม่น้อยกว่า 8 ตัว'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'studentid' => 'Studentid',
            'studentcode' => 'รหัสนักศึกษา',
            'acadid' => 'Acadid',
            'campusid' => 'Campusid',
            'levelid' => 'Levelid',
            'facultyid' => 'Facultyid',
            'departmentid' => 'Departmentid',
            'programid' => 'Programid',
            'minorprogramid' => 'Minorprogramid',
            'feegroupid' => 'Feegroupid',
            'feemultiply' => 'Feemultiply',
            'prefixid' => 'Prefixid',
            'studentname' => 'ชื่อ',
            'studentnameeng' => 'Studentnameeng',
            'studentsurname' => 'นามสกุล',
            'studentsurnameeng' => 'Studentsurnameeng',
            'creditattempt' => 'Creditattempt',
            'creditsatisfy' => 'Creditsatisfy',
            'creditpoint' => 'Creditpoint',
            'gradepoint' => 'Gradepoint',
            'gpa' => 'Gpa',
            'admitacadyear' => 'Admitacadyear',
            'admitsemester' => 'Admitsemester',
            'admitdate' => 'Admitdate',
            'finishdate' => 'Finishdate',
            'studentgroup' => 'Studentgroup',
            'studentpassword' => 'Studentpassword',
            'studentemail' => 'อีเมลล์',
            'studentyear' => 'Studentyear',
            'studentstatus' => 'Studentstatus',
            'officerid' => 'Officerid',
            'financestatus' => 'Financestatus',
            'lastupdateuserid' => 'Lastupdateuserid',
            'lastupdatedatetime' => 'Lastupdatedatetime',
            'schedulegroupid' => 'Schedulegroupid',
            'cardid' => 'Cardid',
            'webflag' => 'Webflag',
            'parentpassword' => 'Parentpassword',
            'branchid' => 'Branchid',
            'oldstudentid' => 'Oldstudentid',
            
            'citizenid' => 'หมายเลขบัตรประชาชน',
            'passwd' => 'รหัสผ่าน',
            'confirm' => 'ยืนยันรหัสผ่าน'
        ];
    }

    public function scenarios() {
        return [
            'status' => ['studentstatus'],
            'new' => ['studentid', 'studentcode', 'acadid', 'campusid', 'levelid', 'facultyid', 'departmentid', 'programid', 'minorprogramid', 'feegroupid', 'feemultiply', 'prefixid', 'studentname', 'studentsurname', 'creditattempt', 'creditsatisfy', 'creditpoint', 'admitacadyear', 'admitsemester', 'admitdate', 'studentyear', 'studentstatus', 'officerid', 'financestatus', 'lastupdatedatetime', 'cardid'],
            
//            'signup' => ['username', 'email', 'password'],
//            'resetPassword' => ['password'],
//            'requestPasswordResetToken' => ['email'],
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStdStudentbio() {
        return $this->hasOne(stdStudentbio::className(), ['studentid' => 'studentid']);
    }

    public function getStudentSex() {
        return $this->stdStudentbio->studentsex;
    }
    
    public function getCitizenid() {
        return $this->stdStudentbio->citizenid;
    }
    
    public function getStdPrefix() {
        return $this->hasOne(stdPrefix::className(), ['prefixid' => 'prefixid']);
    }
    
    public function getStudentPrefix(){
        return $this->stdPrefix->prefixname;
    }
    
    public function getStdFaculty() {
        return $this->hasOne(stdFaculty::className(), ['facultyid' => 'facultyid']);
    }
    
    public function getFacultyName(){
        return $this->stdFaculty->facultyname;
    }
    
    public function getStdLevel() {
        return $this->hasOne(stdLevel::className(), ['levelid' => 'levelid']);
    }
    
    public function getLevelName(){
        return $this->stdLevel->levelname;
    }
    
    public function getStdProgram() {
        return $this->hasOne(stdProgram::className(), ['programid' => 'programid']);
    }
    
    public function getProgramName(){
        return $this->stdProgram->programname;
    }
    
    public function getProgramabbeng(){
        return $this->stdProgram->programabbeng;
    }
    
    public function getBooking() {
        return $this->hasOne(drmBooking::className(), ['student_id' => 'studentid']);
    }
    
    public function getStudentIDByStudentCode($studentcode = NULL) {
        
        $data = stdStudentMaster::findOne(["studentcode" => $studentcode]);
        
        return $data->studentid;
               
        
    }

}
