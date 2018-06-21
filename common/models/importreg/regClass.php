<?php

namespace common\models\importreg;

use Yii;
use app\modules\assess\models\assClass;
use yii\data\ArrayDataProvider;
use common\models\tblCourse;

/**
 * This is the model class for table "class".
 *
 * @property integer $classid
 * @property integer $campusid
 * @property integer $levelid
 * @property string $acadyear
 * @property string $semester
 * @property string $courseid
 * @property integer $section
 * @property string $classstatus
 * @property integer $totalseat
 * @property integer $enrollseat
 *
 */
class regClass extends \yii\db\ActiveRecord {

//    public $new, $update;
    /**
     * @inheritdoc
     */
    public static function getDb() {
        return Yii::$app->dbreg;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'class';
    }

//    /**
//     * @inheritdoc
//     */
//    public function rules() {
//        return [
//            [['classid', 'campusid', 'levelid', 'acadyear', 'semester', 'courseid', 'section', 'classstatus', 'totalseat', 'enrollseat'], 'required'],
//            [['classid', 'campusid', 'levelid', 'section', 'totalseat', 'enrollseat'], 'integer'],
//            [['acadyear'], 'string', 'max' => 4],
//            [['semester', 'classstatus'], 'string', 'max' => 1],
//            [['courseid'], 'string', 'max' => 12]
//        ];
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels() {
//        return [
//            'classid' => 'Classid',
//            'campusid' => 'Campusid',
//            'levelid' => 'Levelid',
//            'acadyear' => 'Acadyear',
//            'semester' => 'Semester',
//            'courseid' => 'Courseid',
//            'section' => 'Section',
//            'classstatus' => 'Classstatus',
//            'totalseat' => 'Totalseat',
//            'enrollseat' => 'Enrollseat',
//        ];
//    }
//    public function chkData($acadyear = NULL, $semester = NULL, $provider = true) {
//        $data["class"] = $this->dataClass($acadyear, $semester);
//        $data["classinstructor"] = $this->dataClassinstructor($acadyear, $semester);
//        $data["enroll"] = $this->dataEnrollsummay($acadyear, $semester);
//        $data["course"] = $this->dataCourse($acadyear, $semester);
//
//        //$data["class"] = $dataclasss;
//
//        return $data;
//    }

    public static function chkNum($acadyear, $semester) {
        $query = regClass::find()
                        ->where(["campusid" => '2'])
                        ->andWhere([
                            "acadyear" => $acadyear,
                            "semester" => $semester,
                        ])->all();

        $countNew = 0;
        $countUpdate = 0;
        //$countError = 0;
        foreach ($query as $q) {
            $assclass = assClass::findOne(["classid" => $q->CLASSID]);

            if (empty($assclass)) {
                $countNew++;
            } elseif ($q->CLASSSTATUS != $assclass->classstatus || $q->ENROLLSEAT != $assclass->enrollseat) {
                $countUpdate++;
            }
        }

        $data["new"] = $countNew;
        $data["update"] = $countUpdate;
        return $data;
    }

    public static function lists($acadyear = NUll, $semester = NULL, $new = NUll, $update = NULL) {

        $query = regClass::find()
                ->where([
                    "campusid" => 2,
                    "acadyear" => $acadyear,
                    "semester" => $semester,
                ])
                ->all();

        foreach ($query as $q) {
            $class = assClass::findOne(["classid" => $q->CLASSID]);

            if (empty($class) && (!$update || $new)) {
                $data[] = [
                    "CLASSID" => $q->CLASSID,
                    "COURSEID" => $q->COURSEID,
                    "CLASSSTATUS" => '<span class="label label-danger">ข้อมูลใหม่</span>',
                    "SECTION" => $q->SECTION,
                    "ENROLLSEAT" => $q->ENROLLSEAT,
                ];
            } elseif (($q->CLASSSTATUS != $class->classstatus || $q->ENROLLSEAT != $class->enrollseat) && !empty($class) && (!$new || $update)) {
                $temp = [
                    "CLASSID" => $q->CLASSID,
                    "COURSEID" => $q->COURSEID,
                    "SECTION" => $q->SECTION,
                ];

                if ($q->CLASSSTATUS != $class->classstatus) {
                    $temp["CLASSSTATUS"] = '<span class="label label-warning">' . $class->classstatus . ' ==> ' . $q->CLASSSTATUS . '</span>';
                } else {
                    $temp["CLASSSTATUS"] = $q->CLASSSTATUS;
                }
                if ($q->ENROLLSEAT != $class->enrollseat) {
                    $temp["ENROLLSEAT"] = '<span class="label label-warning">' . $class->enrollseat . ' ==> ' . $q->ENROLLSEAT . '</span>';
                } else {
                    $temp["ENROLLSEAT"] = $q->ENROLLSEAT;
                }
                $data[] = $temp;
            } elseif (!$new && !$update) {
                $data[] = [
                    "CLASSID" => $q->CLASSID,
                    "COURSEID" => $q->COURSEID,
                    "CLASSSTATUS" => '<span class="label label-info">คงเดิม</span>',
                    "SECTION" => $q->SECTION,
                    "ENROLLSEAT" => $q->ENROLLSEAT,
                ];
            }
        }


        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 50,
            ],
//            'sort' => [
//                'attributes' => ['id', 'name'],
//            ],
        ]);

        return $dataProvider;
    }

    public static function saveData($acadyear, $semester) {
        $query = regClass::find()
                ->where([
                    "campusid" => 2,
                    "acadyear" => $acadyear,
                    "semester" => $semester,
                ])
                ->all();

        $countNew = 0;
        $countUpdate = 0;

        $transaction = assClass::getDb()->beginTransaction();

        try {

            foreach ($query as $q) {
                $class = assClass::findOne(["classid" => $q->CLASSID]);
                if (empty($class)) {
                    
                    regClass::chkCourse($q->COURSEID);

                    $class = New assClass();
                    $class->classid = $q->CLASSID;
                    $class->campusid = $q->CAMPUSID;
                    $class->levelid = $q->LEVELID;
                    $class->acadyear = $q->ACADYEAR;
                    $class->semester = $q->SEMESTER;

                    $class->courseid = $q->COURSEID;
                    $class->section = $q->SECTION;
                    $class->classstatus = $q->CLASSSTATUS;
                    $class->totalseat = $q->TOTALSEAT;
                    $class->enrollseat = $q->ENROLLSEAT;
                    $class->save();

                    $countNew++;
                } elseif ($class->classstatus != $q->CLASSSTATUS || $class->enrollseat != $q->ENROLLSEAT) {
                    $class->classstatus = $q->CLASSSTATUS;
                    $class->totalseat = $q->TOTALSEAT;
                    $class->enrollseat = $q->ENROLLSEAT;

                    $class->setScenario("update");
                    $class->save();

                    $countUpdate++;
                }
            }
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            echo "<pre>";
            print_r($e);
            echo "</pre>";
        }
        $data = [
            "countNew" => $countNew,
            "countUpdate" => $countUpdate,
        ];

        return $data;
    }

    public static function chkCourse($courseid) {
        $tblcourse = tblCourse::findOne(["courseid" => $courseid]);
        if (empty($tblcourse)) {
            $regcourse = regCourse::findOne(["COURSEID" => $courseid]);

            if (!empty($regcourse)) {
                $tblcourse = NEW tblCourse();

                $tblcourse->courseid = $regcourse->COURSEID;
                $tblcourse->coursecode = $regcourse->COURSECODE;
                $tblcourse->revisioncode = $regcourse->REVISIONCODE;
                $tblcourse->courseunicode = $regcourse->COURSEUNICODE;
                $tblcourse->coursename = $regcourse->COURSENAME;
                $tblcourse->coursenameeng = $regcourse->COURSENAMEENG;
                $tblcourse->courseabb = $regcourse->COURSEABB;
                $tblcourse->courseabbeng = $regcourse->COURSEABBENG;
                $tblcourse->creditmin = $regcourse->CREDITMIN;
                $tblcourse->creditmax = $regcourse->CREDITMAX;
                $tblcourse->credittotal = $regcourse->CREDITTOTAL;
                $tblcourse->periodtotal = $regcourse->PERIODTOTAL;
                $tblcourse->credit1 = $regcourse->CREDIT1;
                $tblcourse->credit2 = $regcourse->CREDIT2;
                $tblcourse->credit3 = $regcourse->CREDIT3;
                $tblcourse->period1 = $regcourse->PERIOD1;
                $tblcourse->period2 = $regcourse->PERIOD2;
                $tblcourse->period3 = $regcourse->PERIOD3;
                $tblcourse->studycode1 = $regcourse->STUDYCODE1;
                $tblcourse->studycode2 = $regcourse->STUDYCODE2;
                $tblcourse->studycode3 = $regcourse->STUDYCODE3;
                //$tblcourse->feecharge = $regcourse->FEECHARGE;
                $tblcourse->coursetype = $regcourse->COURSETYPE;
                $tblcourse->coursestatus = $regcourse->COURSESTATUS;
//                $tblcourse->defaultclassstatus = $regcourse->DEFAULTCLASSSTATUS;
                $tblcourse->grademode = $regcourse->GRADEMODE;
                $tblcourse->facultyid = $regcourse->FACULTYID;
                $tblcourse->departmentid = $regcourse->DEPARTMENTID;
                $tblcourse->createdatetime = $regcourse->CREATEDATETIME;
                $tblcourse->lastupdatedatetime = $regcourse->LASTUPDATEDATETIME;
                $tblcourse->courseunit = $regcourse->COURSEUNIT;
                $tblcourse->coursegroup = $regcourse->COURSEGROUP;
                
                if(!$tblcourse->save()) {
                    print_r($tblcourse->getErrors());
                    exit();
                }
            }

        }
    }

}
