<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_course".
 *
 * @property string $courseid
 * @property string $coursecode
 * @property string $revisioncode
 * @property string $courseunicode
 * @property string $coursename
 * @property string $coursenameeng
 * @property string $courseabb
 * @property string $courseabbeng
 * @property double $creditmin
 * @property double $creditmax
 * @property double $credittotal
 * @property double $periodtotal
 * @property double $credit1
 * @property double $credit2
 * @property double $credit3
 * @property integer $period1
 * @property integer $period2
 * @property integer $period3
 * @property string $studycode1
 * @property string $studycode2
 * @property string $studycode3
 * @property string $feecharge
 * @property string $coursetype
 * @property string $coursestatus
 * @property string $defaultclassstatus
 * @property string $grademode
 * @property integer $facultyid
 * @property integer $departmentid
 * @property string $createdatetime
 * @property string $lastupdatedatetime
 * @property string $courseunit
 * @property string $coursegroup
 *
 * @property AssClass[] $assClasses
 */
class tblCourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //'period1', 'period2', 'period3', 
        return [
            [['courseid', 'coursecode', 'coursestatus', 'facultyid', 'departmentid'], 'required'],
            [['creditmin', 'creditmax', 'credittotal', 'periodtotal', 'credit1', 'credit2', 'credit3', 'period1', 'period2', 'period3', ], 'number'],
            [['facultyid', 'departmentid'], 'integer'],
            [['createdatetime', 'lastupdatedatetime'], 'safe'],
            [['courseid'], 'string', 'max' => 12],
            [['coursecode', 'courseunicode', 'courseunit'], 'string', 'max' => 16],
            [['revisioncode'], 'string', 'max' => 4],
            [['coursename', 'coursenameeng'], 'string', 'max' => 100],
            [['courseabb', 'courseabbeng'], 'string', 'max' => 32],
            [['studycode1', 'studycode2', 'studycode3', 'feecharge', 'coursetype', 'defaultclassstatus', 'coursegroup'], 'string', 'max' => 1],
            [['coursestatus', 'grademode'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courseid' => 'Courseid',
            'coursecode' => 'Coursecode',
            'revisioncode' => 'Revisioncode',
            'courseunicode' => 'Courseunicode',
            'coursename' => 'Coursename',
            'coursenameeng' => 'Coursenameeng',
            'courseabb' => 'Courseabb',
            'courseabbeng' => 'Courseabbeng',
            'creditmin' => 'Creditmin',
            'creditmax' => 'Creditmax',
            'credittotal' => 'Credittotal',
            'periodtotal' => 'Periodtotal',
            'credit1' => 'Credit1',
            'credit2' => 'Credit2',
            'credit3' => 'Credit3',
            'period1' => 'Period1',
            'period2' => 'Period2',
            'period3' => 'Period3',
            'studycode1' => 'Studycode1',
            'studycode2' => 'Studycode2',
            'studycode3' => 'Studycode3',
            'feecharge' => 'Feecharge',
            'coursetype' => 'Coursetype',
            'coursestatus' => 'Coursestatus',
            'defaultclassstatus' => 'Defaultclassstatus',
            'grademode' => 'Grademode',
            'facultyid' => 'Facultyid',
            'departmentid' => 'Departmentid',
            'createdatetime' => 'Createdatetime',
            'lastupdatedatetime' => 'Lastupdatedatetime',
            'courseunit' => 'Courseunit',
            'coursegroup' => 'Coursegroup',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssClasses()
    {
        return $this->hasMany(AssClass::className(), ['courseid' => 'courseid']);
    }
    
    public static function getCourseDetails($classid = NULL) {
        
        $query = NEW \yii\db\Query();
        $query->select(["coursecode", "coursename", "section", "acadyear", "semester"])
                ->from("ass_class")
                ->innerJoin("tbl_course", "tbl_course.courseid = ass_class.courseid")
                ->where([
                    "classid" => $classid
                ]);
        $results = $query->createCommand()->queryOne();
        
        return $results;
    }
}
