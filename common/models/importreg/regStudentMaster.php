<?php

namespace common\models\importreg;

use Yii;
use yii\data\ArrayDataProvider;
use common\models\stdStudentMaster;
use common\models\stdStudentBio;
use yii\db\Exception;

//use common\models\importreg\regStudentBio;

class regStudentMaster extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $new, $status;

    public static function getDb() {
        return Yii::$app->dbreg;
    }

    public static function tableName() {
        return 'studentmaster';
    }

    public function attributeLabels() {
        return [
            'new' => 'นักศึกษาใหม่',
            'status' => 'สถานะมีการเปลี่ยนแปลง',
        ];
    }

    public function getRegstumaster() {
        return $this->hasOne(stdStudentMaster::className(), ['studentid' => 'STUDENTID']);
        //->where("std_studentmaster.studentID ");
    }

    public function getRegstubio() {
        return $this->hasOne(regStudentBio::className(), ['STUDENTID' => 'STUDENTID']);
    }

    public function getRegprogram() {
        return $this->hasOne(regProgram::className(), ['PROGRAMID' => 'PROGRAMID']);
    }

    public function getRegprefix() {
        return $this->hasOne(regPrefix::className(), ['PREFIXID' => 'PREFIXID']);
    }

    public function lists() {

        $sess = Yii::$app->session->get('sessImport');

        $year = substr($sess["ADMITACADYEAR"], 2, 2);
        if (!$year) {
            $this_y = date("Y");
            if ($this_y < 2558) {
                $this_y += 543;
            }
            $year = substr($this_y, 2, 2);
        }


        $query = regStudentMaster::find()
                //->select(["STUDENTID", "STUDENTSTATUS", "CITIZ"])
                ->joinWith("regstubio")
                ->joinWith("regprogram")
                ->joinWith("regprefix")
                ->where([
                    "campusid" => 2,
                    "studentmaster.facultyid" => [41, 42, 43, 44, 23],
                ])->andWhere(["like", 'studentmaster.studentcode', "$year%", FALSE])
                ->all();

        $new = $sess["new"];
        $status = $sess["status"];


        foreach ($query as $q) {
            $stdstudentmaster = stdStudentMaster::findOne(["studentid" => $q->STUDENTID]);

            if ($new && empty($stdstudentmaster)) {
                $data[] = [
                    "studentcode" => $q->STUDENTCODE,
                    "fullname" => $q->regprefix->PREFIXNAME . $q->STUDENTNAME . " " . $q->STUDENTSURNAME,
                    "status" => '<span class="label label-danger">นักศึกษาใหม่</span>',
                    "citizenid" => $q->regstubio->CITIZENID,
                    "programname" => $q->regprogram->PROGRAMNAME,
                ];
            } elseif ($status && ($q->STUDENTSTATUS != $stdstudentmaster->studentstatus) && !empty($stdstudentmaster)) {
                $data[] = [
                    "studentcode" => $q->STUDENTCODE,
                    "fullname" => $q->regprefix->PREFIXNAME . $q->STUDENTNAME . " " . $q->STUDENTSURNAME,
                    "status" => '<span class="label label-warning">' . $stdstudentmaster->studentstatus . ' ==> ' . $q->STUDENTSTATUS . '</span>',
                    "citizenid" => $q->regstubio->CITIZENID,
                    "programname" => $q->regprogram->PROGRAMNAME,
                ];
            } elseif ($new == false && $status == false) {
                $data[] = [
                    "studentcode" => $q->STUDENTCODE,
                    "fullname" => $q->regprefix->PREFIXNAME . $q->STUDENTNAME . " " . $q->STUDENTSURNAME,
                    "status" => '<span class="label label-info">คงเดิม</span>',
                    "citizenid" => $q->regstubio->CITIZENID,
                    "programname" => $q->regprogram->PROGRAMNAME,
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

    public static function impstudent() {
        $sess = Yii::$app->session->get('sessImport');

        $year = substr($sess["ADMITACADYEAR"], 2, 2);
        if (!$year) {
            $this_y = date("Y");
            if ($this_y < 2558) {
                $this_y += 543;
            }
            $year = substr($this_y, 2, 2);
        }

        $query = regStudentMaster::find()
//                ->select(["studentmaster.*", "studentbio.*"])
                ->joinWith("regstubio")
                ->where([
                    "campusid" => 2,
                    "studentmaster.facultyid" => [41, 42, 43, 44, 23],
                ])->andWhere(["like", 'studentmaster.studentcode', "$year%", FALSE])
                ->all();
        
//        echo "<pre>";
//        print_r($query);
//        echo "</pre>";
//die();
//        $new = $sess["new"];
//        $status = $sess["status"];
        $countnew = 0;
        $countupdate = 0;
        
//        echo $new, $status;
//        die();
        
//        $result = array();
        foreach ($query as $q) {
            $stdstudentmaster = stdStudentMaster::findOne(["studentid" => $q->STUDENTID]);
            $stdstudentbio = stdStudentBio::findOne(["studentid" => $q->STUDENTID]);
            if (empty($stdstudentmaster) && empty($stdstudentbio)) {
                $stdstudentmaster = NEW stdStudentMaster();
                $stdstudentbio = NEW \app\modules\setting\models\impStdbio();
                $transaction = stdStudentMaster::getDb()->beginTransaction();
                try {
                    $stdstudentmaster->studentid = $q->STUDENTID;
                    $stdstudentmaster->studentcode = $q->STUDENTCODE;
                    $stdstudentmaster->acadid = $q->ACADID;
                    $stdstudentmaster->campusid = $q->CAMPUSID;
                    $stdstudentmaster->levelid = $q->LEVELID;
                    $stdstudentmaster->facultyid = $q->FACULTYID;
                    $stdstudentmaster->departmentid = $q->DEPARTMENTID;
                    $stdstudentmaster->programid = $q->PROGRAMID;
                    $stdstudentmaster->minorprogramid = $q->MINORPROGRAMID;
                    $stdstudentmaster->feegroupid = $q->FEEGROUPID;
                    $stdstudentmaster->feemultiply = $q->FEEMULTIPLY;
                    $stdstudentmaster->prefixid = $q->PREFIXID;
                    $stdstudentmaster->studentname = $q->STUDENTNAME;
                    $stdstudentmaster->studentnameeng = $q->STUDENTNAMEENG;
                    $stdstudentmaster->studentsurname = $q->STUDENTSURNAME;
                    $stdstudentmaster->studentsurnameeng = $q->STUDENTSURNAMEENG;
                    $stdstudentmaster->creditattempt = $q->CREDITATTEMPT;
                    $stdstudentmaster->creditsatisfy = $q->CREDITSATISFY;
                    $stdstudentmaster->creditpoint = $q->CREDITPOINT;
                    $stdstudentmaster->gradepoint = $q->GRADEPOINT;
                    $stdstudentmaster->gpa = $q->GPA;
                    $stdstudentmaster->admitacadyear = $q->ADMITACADYEAR;
                    $stdstudentmaster->admitsemester = $q->ADMITSEMESTER;
                    $stdstudentmaster->admitdate = $q->ADMITDATE;
                    $stdstudentmaster->finishdate = $q->FINISHDATE;
//                $stdstudentmaster->studentgroup = $q->STUDENTGROUP;
//                $stdstudentmaster->studentpassword = $q->STUDENTPASSWORD;
//                $stdstudentmaster->studentemail = $q->STUDENTEMAIL;
                    $stdstudentmaster->studentyear = $q->STUDENTYEAR;
                    $stdstudentmaster->studentstatus = $q->STUDENTSTATUS;
                    $stdstudentmaster->officerid = $q->OFFICERID;
                    $stdstudentmaster->financestatus = $q->FINANCESTATUS;
                    $stdstudentmaster->lastupdateuserid = "NKC";
                    $stdstudentmaster->lastupdatedatetime = $q->LASTUPDATEDATETIME;
//                $stdstudentmaster->schedulegroupid = $q->SCHEDULEGROUPID;
                    $stdstudentmaster->cardid = $q->CARDID;
//                $stdstudentmaster->webflag = $q->WEBFLAG;
//                $stdstudentmaster->parentpassword = $q->PARENTPASSWORD;
//                $stdstudentmaster->branchid = $q->BRANCHID;
//                $stdstudentmaster->oldstudentid = $q->OLDSTUDENTID;
                    $stdstudentmaster->setScenario("new");
                    $stdstudentmaster->save();

                    $stdstudentbio->studentid = $q->regstubio->STUDENTID;
                    $stdstudentbio->nationid = $q->regstubio->NATIONID;
                    $stdstudentbio->religionid = $q->regstubio->RELIGIONID;
                    $stdstudentbio->schoolid = $q->regstubio->SCHOOLID;
                    $stdstudentbio->entrytype = $q->regstubio->ENTRYTYPE;
                    $stdstudentbio->entrydegree = $q->regstubio->ENTRYDEGREE;
                    $stdstudentbio->studentsex = $q->regstubio->STUDENTSEX;
                    $stdstudentbio->admitacadyear = $q->regstubio->ADMITACADYEAR;
                    $stdstudentbio->entrygpa = $q->regstubio->ENTRYGPA;
                    $stdstudentbio->citizenid = $q->regstubio->CITIZENID;
                    $stdstudentbio->cardexpirydate = $q->regstubio->CARDEXPIRYDATE;

                    $stdstudentbio->setScenario("import");
                    if (!$stdstudentbio->save()) {
                        print_r($stdstudentbio);
                        die();
                    }

                    $transaction->commit();
                    $countnew++;
                } catch (Exception $e) {
                    $transaction->rollBack();
//                    throw $e;

                    echo "<pre>";
                    print_r($e);
                    echo "</pre>";
                }


//                if (!$stdstudentmaster->save()) {
//                    print_r($stdstudentmaster->getErrors());
//                    exit();
//                } else {
//                    $result["countnew"] = $j++;
//                }
//                $result["countnew"] = $j++;
            } elseif (($q->STUDENTSTATUS != $stdstudentmaster->studentstatus) && !empty($stdstudentmaster)) {

                $stdstudentmaster->studentstatus = $q->STUDENTSTATUS;

                $stdstudentmaster->setScenario("status");
                if (!$stdstudentmaster->save()) {
                    print_r($stdstudentmaster->getErrors());
                    exit();
                } else {
                    //$result["success"] = true;
                    $countupdate++;
                }
            }
        }
        Yii::$app->getSession()->setFlash('success', [
            'body' => "บันทึกสำเร็จ <br/>"
            . "นำเข้าข้อมูลนักศึกษาใหม่ จำนวน $countnew คน <br>"
            . "ปรับปรุงข้อมูลสถานะ (STUDENT STATUS) จำนวน $countupdate คน",
            'type' => 'alert-success',
            'icon' => 'glyphicon glyphicon-ok-sign'
                //'title' => 'xxx',
        ]);

        //return $result;
    }

}
