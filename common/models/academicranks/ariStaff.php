<?php

namespace common\models\academicranks;

use Yii;
use common\models\tblStaffPosition;
use common\models\tblPosition;
use common\models\tblPositionSubtype;
use common\models\academicranks\ariStaffAcademic;
use common\models\academicranks\ariAcademic;
use common\models\tblStaffStatus;
use common\models\academicranks\ariEducation;
use common\models\tblStaffHistory;
use common\models\tblStaffEducate;
use common\models\academicranks\ariExtendTime;
use common\models\academicranks\ariStaffExecutive;
use common\models\Staff;
use common\models\TblBelongto;
use common\models\Department;
use common\components\ndate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class ariStaff extends Staff {

    public function Department($citizen_id = NULL) {
        $subQuery = TblBelongto::findOne(['citizen_id' => $citizen_id]);
        $depart_id = $subQuery->depart_id;
        $query = Department::findOne(['id' => $depart_id]);

        return $query->title;
    }

    public function Position($citizen_id = NULL) {
        $subQuery = tblStaffPosition::findOne(['citizen_id' => $citizen_id]);
        $position_id = $subQuery->position_id;
        $query = tblPosition::findOne(['position_id' => $position_id]);

        return $query->position;
    }

    public function Positionsub($citizen_id = NULL) {
        $subQuery = tblStaffPosition::findOne(['citizen_id' => $citizen_id]);
        $positionsub_id = $subQuery->position_subtype_id;
        $query = tblPositionSubtype::findOne(['position_subtype_id' => $positionsub_id]);

        return $query->position_subtype;
    }

    public function Academic($citizen_id = NULL) {
        //$position_id=4;
        $SubQuery = tblStaffPosition::find()->select('position_id')->where(['citizen_id' => $citizen_id]);
        if ($SubQuery != 4) {
            return "สายสนับสนุน";
        }
        $subQuery = ariStaffAcademic::findOne(['citizen_id' => $citizen_id]);
        $academic_id = $subQuery->academic_id;
        $query = ariAcademic::findOne(['academic_id' => $academic_id]);
        if ($query == "") {
            return "อาจารย์";
        } else {
            return $query->title . '( ' . $query->abb . ')';
        }
    }

    public function Startwork($citizen_id = NULL) {
        $query = tblStaffPosition::findOne(['citizen_id' => $citizen_id]);
        //    ->select(['status_date']);
        // Yii::$app->thaiFormatter->locale = 'th_TH';
        $start_work = $query->date_work;
        //   Yii::$app->thaiFormatter->locale = 'th-TH';
        //   $dmy= date_format($start_work, 'd M Y');
        //     $d = new ndate();
        $d = new ndate();
        return $d->getThaiShortDate($start_work);
       // return datetime($start_work, 'php:d M Y');
    }

    public function Startedu($citizen_id = NULL) {
        $query = ariEducation::findOne(['citizen_id' => $citizen_id]);
        //    ->select(['status_date']);
        // Yii::$app->thaiFormatter->locale = 'th_TH';
        $start_d = $query->start_date;
        if ($start_d == "") {
            return "ไม่ได้ลาศึกษา";
        } else {
            //    Yii::$app->thaiFormatter->locale = 'th-TH';
            $dmy = Yii::$app->Formatter->asDate($start_d, 'php:Y-m-d');
            $d = new ndate();
            return $d->getThaiShortDate($dmy);
        }
        // return Yii::$app->thaiFormatter->asDate($start_work, 'long');
//        if ($start_d =""){
//            return "ไม่ได้ลาศึกษา";
//        }else{
//           Yii::$app->thaiFormatter->locale = 'th-TH';
//           $dmy= Yii::$app->Formatter->asDate($start_d, 'php:Y-m-d'); 
//            $d = new ndate();
//            //return $d->getThaiShortDate($dmy); 
//            return $start_d;
//        }
    }

    public function Status($citizen_id = NULL) {
        $subquery = NEW Query();
        $subquery->select([
                    "status_id"
                ])
                ->from("tbl_staff_history")
                ->where(["citizen_id" => $citizen_id])
                ->orderBy([
                    "status_date" => SORT_DESC
        ]);

        $result = $subquery->createCommand()->queryOne();
        $status_id = $result[status_id];
        //$status_id=$subQuery->status_id;
        $query = tblStaffStatus::findOne(['status_id' => $status_id]);
        return $query->title;
    }

    public function TotalTimeWork($citizen_id = NULL) {
        $query = tblStaffPosition::findOne(['citizen_id' => $citizen_id]);
        //    ->select(['status_date']);
        // Yii::$app->thaiFormatter->locale = 'th_TH';
        $start_work = $query->date_work;
        //$today = date("Y-m-d");
        $todayY = date("Y");
        $todayM = date("m");
        $todayD = date("d");
        $bdate = explode("-", $start_work);
        $bY = $bdate[0];
        $bM = $bdate[1];
        $bD = $bdate[2];
        $LeapYear = date("L"); // 1 = leap year Feb has 29 day

        $d31 = ['01', '03', '05', '07', '08', '10', '12'];
        $d30 = ['04', '06', '09', '11'];
        $d28 = ['02'];

        $todayM2 = $bM;

        if ([$todayM2, $d31] == TRUE) {
            $subD = 31;
        } else if ([$todayM2, $d30] == TRUE) {
            $subD = 30;
        } else if ([$todayM2, $d28] == TRUE) {
            if ($LeapYear == 1) {
                $subD = 29;
            } else {
                $subD = 28;
            }
        }

        if (($todayY == $bY) && ($todayM == $bM) && ($todayD == $bD)) {
            $aY2 = 0;
            $aM2 = 0;
            $aD2 = 0;
        } else if (($todayY == $bY) && ($todayM == $bM) && ($todayD > $bD)) {
            $aY2 = 0;
            $aM2 = 0;
            $aD2 = $todayD - $bD;
        }
        //else if(($todayY==$bY)&&($todayM==$bM)&&($todayD<$bD)) { $aY2=0; $aM2=12-($todayM-$bM); $aD2=$subD-($bD-$todayD); } 
        else if (($todayY == $bY) && ($todayM > $bM) && ($todayD == $bD)) {
            $aY2 = 0;
            $aM2 = $todayM - $bM;
            $aD2 = 0;
        } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD > $bD)) {
            $aY2 = 0;
            $aM2 = $todayM - $bM;
            $aD2 = $todayD - $bD;
        } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD < $bD)) {
            $aY2 = 0;
            $aM2 = 12 - ($todayM - $bM);
            $aD2 = $subD - ($bD - $todayD);
        } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD == $bD)) {
            $aY2 = $todayY - $bY;
            $aM2 = $todayM - $bM;
            $aD2 = 0;
        } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD > $bD)) {
            $aY2 = $todayY - $bY;
            $aM2 = $todayM - $bM;
            $aD2 = $todayD - $bD;
        } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD < $bD)) {
            $aY2 = $todayY - $bY;
            $aM2 = $todayM - $bM - 1;
            $aD2 = $subD - ($bD - $todayD);
        } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD == $bD)) {
            $aY2 = $todayY - $bY - 1;
            $aM2 = 12 - ($bM - $todayM);
            $aD2 = 0;
        } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD < $bD)) {
            $aY2 = $todayY - $bY - 1;
            $aM2 = 12 - ($bM - $todayM) - 1;
            $aD2 = $bD - $todayD;
        } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD > $bD)) {
            $aY2 = $todayY - $bY - 1;
            $aM2 = 12 - ($bM - $todayM);
            $aD2 = $todayD - $bD;
        } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD == $bD)) {
            $aY2 = $todayY - $bY;
            $aM2 = 0;
            $aD2 = 0;
        } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD > $bD)) {
            $aY2 = $todayY - $bY;
            $aM2 = 0;
            $aD2 = $todayD - $bD;
        } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD < $bD)) {
            $aY2 = $todayY - $bY - 1;
            $aM2 = 11;
            $aD2 = $subD - ($bD - $todayD);
        }


        $age = $aY2 . "ปี" . $aM2 . "เดือน" . $aD2 . "วัน";
        return ($age);
    }

    public function counterSeven($citizen_id) {
        $start_study = ariEducation::find()->where(['citizen_id' => $citizen_id])
                ->one();
        $id_stud = $start_study->citizen_id;
        $start = $start_study->start_date;
        $end = $start_study->end_date;
        $date_graduation_date = $start_study->graduation_date;
        $query = ariStaffAcademic::findOne(['citizen_id' => $citizen_id]);
        $citizenid = $query->citizen_id;
        if ($citizenid == "") {
            $subquery = ariStaffExecutive::findOne(['citizen_id' => $citizen_id]);
            $cit_id = $subquery->citizen_id;
            // เริ่ม ตรวจสอบว่าเคยดำรงตำแหน่งบริหารหรือไม่
            if ($cit_id == "") {

                $startwork = tblStaffPosition::findOne(['citizen_id' => $citizen_id]);
                $startWork = $startwork->date_work;


                $extime = ariExtendTime::find()->where(['citizen_id' => $id_stud])
                        ->one();
                $exst = $extime->extend_start;
                $exend = $extime->extend_end;

                list( $syear, $smonth, $sday) = explode("-", $start);
                list( $eyear, $emonth, $eday) = explode("-", $end);
                //----------- explode วันที่ต่อเวลาศึกษา ----------
                list( $str_year, $str_month, $str_day) = explode("-", $exst);
                list( $end_year, $end_month, $end_day) = explode("-", $exend);
                //----------- explode วันที่จบการศึกษา -----------
                list($dategradeyear, $dategrademonth, $dategradeday) = explode("-", $date_graduation_date);
                //----------- explode วันที่บรรจุเข้าทำงาน ---------
                list( $styear, $stmonth, $stday) = explode("-", $startWork);
                list( $yyear, $ymonth, $yday) = explode("-", $years);
                $level = tblStaffEducate::find()->where(['citizen_id' => $citizen_id])
                        ->one();
                $level_id = $level->level_id;


                if ($level_id >= 2) {
                    $subquery = NEW Query();
                    $subquery->select([
                                "status_id"
                            ])
                            ->from("tbl_staff_history")
                            ->where(["citizen_id" => $citizen_id])
                            ->orderBy([
                                "status_date" => SORT_DESC
                    ]);

                    $result = $subquery->createCommand()->queryOne();
                    $status_id = $result[status_id];
                    if ($status_id == 1) {
                        if ($startwork) {
                            if ($date_graduation_date <> '0000-00-00') {
                                if ($syear and $dategradeday) {
                                    if ($exst and $exend) {
                                        if ($syear < 1970 or $dategradeyear < 1970) {
                                            $yearad = 1970 - $syear;
                                            $syear = 1970;
                                            $yearads = 1970 - $dategradeyear;
                                            $dategradeyear = 1970;
                                        } else {
                                            $yearad = 0;
                                            $yearads = 0;
                                        }

                                        $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                                        $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                                        $mages = $ex_e - $ex_s;
                                    }
                                    $y1 = (date("Y", $mages) - 1970 + $yearads);
                                    $m1 = (date("m", $mages) - 1);
                                    $d1 = (date("d", $mages) - 1);

                                    $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                                    $es = mktime(1, 1, 1, $dategrademonth, $dategradeday, $dategradeyear);
                                    $mage = $es - $sts;
                                    $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                                    // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                                    $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                                    $m = (date("m", $mage) - 1) + $m1;
                                    $d = (date("d", $mage) - 1) + $d1;

                                    if ($d >= 30) {
                                        $m = $m + 1;
                                        $d = $d - 30;
                                        if ($m >= 12) {
                                            $y = $y + 1 + 7;
                                            $m = $m - 12;
                                            $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                            //  Yii::$app->thaiFormatter->locale = 'th-TH';

                                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                            $d = new ndate();
                                            return $dmy;
                                            //   return $status_id;
                                        }
                                    }



                                    $y = $y + 7;
                                    $m = (date("m", $mage) - 1);
                                    $d = (date("d", $mage) - 1);
                                    //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                                    $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                    //   Yii::$app->thaiFormatter->locale = 'th-TH';
                                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                    $d = new ndate();
                                    return $dmy;
                                    //return $status_id;
                                    // return Yii::$app->thaiFormatter->asDate($st, 'long');
                                    // return ($y.''.$m.''.$d);
                                    //     return $y;
                                }
                                if ($d >= 30) {
                                    $m = $m + 1;
                                    $d = $d - 30;
                                    if ($m >= 12) {
                                        $y = $y + 1 + 7;
                                        $m = $m - 12;
                                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                        //   Yii::$app->thaiFormatter->locale = 'th-TH';
                                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                        $d = new ndate();
                                        //  return $status_id;
                                        return $dmy;
                                        //  return Yii::$app->thaiFormatter->asDate($st, 'long');  
                                        //   return $y;
                                    }
                                }



                                $y = $y + 7;
                                $m = (date("m", $mage) - 1);
                                $d = (date("d", $mage) - 1);
                                //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                                $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                                //    Yii::$app->thaiFormatter->locale = 'th-TH';
                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $dmy;
                                //return $status_id;
                                //  return Yii::$app->thaiFormatter->asDate($st, 'long');
                            }
                            // กรณีที่กลับมารายงานตัวแต่ยังไม่สำเร็จการศึกษา
                            if ($syear and $eday) {
                                if ($exst and $exend) {
                                    if ($syear < 1970 or $eyear < 1970) {
                                        $yearad = 1970 - $syear;
                                        $syear = 1970;
                                        $yearads = 1970 - $eyear;
                                        $eyear = 1970;
                                    } else {
                                        $yearad = 0;
                                        $yearads = 0;
                                    }

                                    $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                                    $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                                    $mages = $ex_e - $ex_s;
                                }
                                $y1 = (date("Y", $mages) - 1970 + $yearads);
                                $m1 = (date("m", $mages) - 1);
                                $d1 = (date("d", $mages) - 1);

                                $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                                $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                                $mage = $es - $sts;
                                $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                                // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                                $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                                $m = (date("m", $mage) - 1) + $m1;
                                $d = (date("d", $mage) - 1) + $d1;

                                if ($d >= 30) {
                                    $m = $m + 1;
                                    $d = $d - 30;
                                    if ($m >= 12) {
                                        $y = $y + 1 + 7;
                                        $m = $m - 12;
                                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                        //  Yii::$app->thaiFormatter->locale = 'th-TH';

                                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                        $d = new ndate();
                                        return $dmy;
                                        //   return $y;
                                    }
                                }



                                $y = $y + 7;
                                $m = (date("m", $mage) - 1);
                                $d = (date("d", $mage) - 1);
                                //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                                $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                //   Yii::$app->thaiFormatter->locale = 'th-TH';
                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $dmy;
                                // return Yii::$app->thaiFormatter->asDate($st, 'long');
                                // return ($y.''.$m.''.$d);
                                //     return $y;
                            }
                            if ($d >= 30) {
                                $m = $m + 1;
                                $d = $d - 30;
                                if ($m >= 12) {
                                    $y = $y + 1 + 7;
                                    $m = $m - 12;
                                    $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                    //   Yii::$app->thaiFormatter->locale = 'th-TH';
                                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                    $d = new ndate();
                                    return $dmy;
                                    //  return Yii::$app->thaiFormatter->asDate($st, 'long');  
                                    //   return $y;
                                }
                            }



                            $y = $y + 7;
                            $m = (date("m", $mage) - 1);
                            $d = (date("d", $mage) - 1);
                            //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                            $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                            //    Yii::$app->thaiFormatter->locale = 'th-TH';
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            return $dmy;
                            // สิ้นสุด   
                        }
                    }
                    if ($startwork) {
                        if ($syear and $eday) {
                            if ($exst and $exend) {
                                if ($syear < 1970 or $eyear < 1970) {
                                    $yearad = 1970 - $syear;
                                    $syear = 1970;
                                    $yearads = 1970 - $eyear;
                                    $eyear = 1970;
                                } else {
                                    $yearad = 0;
                                    $yearads = 0;
                                }

                                $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                                $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                                $mages = $ex_e - $ex_s;
                            }
                            $y1 = (date("Y", $mages) - 1970 + $yearads);
                            $m1 = (date("m", $mages) - 1);
                            $d1 = (date("d", $mages) - 1);

                            $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                            $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                            $mage = $es - $sts;
                            $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                            // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                            $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                            $m = (date("m", $mage) - 1) + $m1;
                            $d = (date("d", $mage) - 1) + $d1;

                            if ($d >= 30) {
                                $m = $m + 1;
                                $d = $d - 30;
                                if ($m >= 12) {
                                    $y = $y + 1 + 7;
                                    $m = $m - 12;
                                    $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                    //  Yii::$app->thaiFormatter->locale = 'th-TH';

                                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                    $d = new ndate();
                                    return $dmy;
                                    //   return $y;
                                }
                            }



                            $y = $y + 7;
                            $m = (date("m", $mage) - 1);
                            $d = (date("d", $mage) - 1);
                            //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                            $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                            //   Yii::$app->thaiFormatter->locale = 'th-TH';
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            return $dmy;
                            // return Yii::$app->thaiFormatter->asDate($st, 'long');
                            // return ($y.''.$m.''.$d);
                            //     return $y;
                        }
                        if ($d >= 30) {
                            $m = $m + 1;
                            $d = $d - 30;
                            if ($m >= 12) {
                                $y = $y + 1 + 7;
                                $m = $m - 12;
                                $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                //   Yii::$app->thaiFormatter->locale = 'th-TH';
                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $dmy;
                                //  return Yii::$app->thaiFormatter->asDate($st, 'long');  
                                //   return $y;
                            }
                        }



                        $y = $y + 7;
                        $m = (date("m", $mage) - 1);
                        $d = (date("d", $mage) - 1);
                        //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                        $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                        //    Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        return $dmy;

                        //  return Yii::$app->thaiFormatter->asDate($st, 'long');
                    }
                    //ปิดตรวจสอบวุฒิการศึกษา
                }
                //ปิดตรวจสอบวุฒิการศึกษา
                if ($startwork) {
                    if ($syear and $eday) {
                        if ($exst and $exend) {
                            if ($syear < 1970 or $eyear < 1970) {
                                $yearad = 1970 - $syear;
                                $syear = 1970;
                                $yearads = 1970 - $eyear;
                                $eyear = 1970;
                            } else {
                                $yearad = 0;
                                $yearads = 0;
                            }

                            $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                            $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                            $mages = $ex_e - $ex_s;
                        }
                        $y1 = (date("Y", $mages) - 1970 + $yearads);
                        $m1 = (date("m", $mages) - 1);
                        $d1 = (date("d", $mages) - 1);
                        $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                        $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                        $mage = $es - $sts;
                        $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                        // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                        $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                        $m = (date("m", $mage) - 1) + $m1;
                        $d = (date("d", $mage) - 1) + $d1;

                        if ($d >= 30) {
                            $m = $m + 1;
                            $d = $d - 30;
                            if ($m >= 12) {
                                $y = $y + 1 + 11;
                                $m = $m - 12;
                                $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                //    Yii::$app->thaiFormatter->locale = 'th-TH';

                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $dmy;
                                //   return $y;
                            }
                        }



                        $y = $y + 11;
                        $m = (date("m", $mage) - 1);
                        $d = (date("d", $mage) - 1);
                        //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                        //  Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        return $dmy;
                        // return Yii::$app->thaiFormatter->asDate($st, 'long');
                        // return ($y.''.$m.''.$d);
                        //     return $y;
                    }
                    if ($d >= 30) {
                        $m = $m + 1;
                        $d = $d - 30;
                        if ($m >= 12) {
                            $y = $y + 1 + 11;
                            $m = $m - 12;
                            $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                            //    Yii::$app->thaiFormatter->locale = 'th-TH';
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            return $dmy;
                            //  return Yii::$app->thaiFormatter->asDate($st, 'long');  
                            //   return $y;
                        }
                    }



                    $y = $y + 11;
                    $m = (date("m", $mage) - 1);
                    $d = (date("d", $mage) - 1);
                    //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                    $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                    //    Yii::$app->thaiFormatter->locale = 'th-TH';
                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                    $d = new ndate();
                    return $dmy;

                    //  return Yii::$app->thaiFormatter->asDate($st, 'long');
                }
                return "-";
            }
            //------------------------------------
            $startwork = tblStaffPosition::findOne(['citizen_id' => $citizen_id]);
            $startWork = $startwork->date_work;


            $extime = ariExtendTime::find()->where(['citizen_id' => $id_stud])
                    ->one();
            $exst = $extime->extend_start;
            $exend = $extime->extend_end;

            list( $syear, $smonth, $sday) = explode("-", $start);
            list( $eyear, $emonth, $eday) = explode("-", $end);
            //----------- explode วันที่ต่อเวลาศึกษา ----------
            list( $str_year, $str_month, $str_day) = explode("-", $exst);
            list( $end_year, $end_month, $end_day) = explode("-", $exend);
            //----------- explode วันที่บรรจุเข้าทำงาน ---------
            list( $styear, $stmonth, $stday) = explode("-", $startWork);
            list( $yyear, $ymonth, $yday) = explode("-", $years);
            $level = tblStaffEducate::find()->where(['citizen_id' => $citizen_id])
                    ->one();
            $level_id = $level->level_id;
            //------------------------------------
            //---------- คำนวณระยะเวลาที่ดำรงตำแหน่งบริหาร ---------------
            $q = ariStaffExecutive::findOne(['citizen_id' => $citizen_id]);
            $date_start = $q->date_start;
            $date_stop = $q->date_stop;
            //----------- explode วันที่ดำรงตำแหน่งบริหาร
            list( $syearEc, $smonthEc, $sdayEc) = explode("-", $date_start);
            list( $eyearEc, $emonthEc, $edayEc) = explode("-", $date_stop);
            //-------------------------------------
            $ec_s = mktime(1, 1, 1, $smonthEc, $sdayEc, $syearEc);
            $ec_e = mktime(1, 1, 1, $emonthEc, $edayEc, $eyearEc);
            $Ec = $ec_e - $ec_s;
            //---------- คำนวณระยะเวลาที่ดำรงตำแหน่งบริหาร ---------------
            $y_Ec = (date("Y", $Ec) - 1970 + $yearads);
            $m_Ec = (date("m", $Ec) - 1);
            $d_Ec = (date("d", $Ec) - 1);
            //------------------------------------------------------
            //-------------------------------------------------------
            if ($level_id >= 2) {
                if ($startwork) {
                    if ($syear and $eday) {
                        if ($exst and $exend) {
                            if ($syear < 1970 or $eyear < 1970) {
                                $yearad = 1970 - $syear;
                                $syear = 1970;
                                $yearads = 1970 - $eyear;
                                $eyear = 1970;
                            } else {
                                $yearad = 0;
                                $yearads = 0;
                            }

                            $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                            $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                            $mages = $ex_e - $ex_s;
                        }
                        $y1 = (date("Y", $mages) - 1970 + $yearads);
                        $m1 = (date("m", $mages) - 1);
                        $d1 = (date("d", $mages) - 1);
                        $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                        $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                        $mage = $es - $sts;
                        $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                        // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                        $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                        $m = (date("m", $mage) - 1) + $m1;
                        $d = (date("d", $mage) - 1) + $d1;

                        if ($d >= 30) {
                            $m = $m + 1 + $m_Ec;
                            $d = $d - 30 + $d_Ec;
                            if ($m >= 12) {
                                $y = $y + 1 + 7 + $y_Ec;
                                $m = $m - 12;
                                $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                //  Yii::$app->thaiFormatter->locale = 'th-TH';

                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $dmy;
                                //   return $y;
                            }
                        }



                        $y = $y + 7 + $y_Ec;
                        $m = (date("m", $mage) - 1) + $m_Ec;
                        $d = (date("d", $mage) - 1) + $d_Ec;
                        //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                        //   Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        return $dmy;
                        // return Yii::$app->thaiFormatter->asDate($st, 'long');
                        // return ($y.''.$m.''.$d);
                        //     return $y;
                    }
                    if ($d >= 30) {
                        $m = $m + 1 + $m_Ec;
                        $d = $d - 30 + $d_Ec;
                        if ($m >= 12) {
                            $y = $y + 1 + 7 + $y_Ec;
                            $m = $m - 12;
                            $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                            //   Yii::$app->thaiFormatter->locale = 'th-TH';
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            return $dmy;
                            //  return Yii::$app->thaiFormatter->asDate($st, 'long');  
                            //   return $y;
                        }
                    }



                    $y = $y + 7 + $y_Ec;
                    $m = (date("m", $mage) - 1) + $m_Ec;
                    $d = (date("d", $mage) - 1) + $d_Ec;
                    //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                    $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                    //    Yii::$app->thaiFormatter->locale = 'th-TH';
                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                    $d = new ndate();
                    return $dmy;

                    //  return Yii::$app->thaiFormatter->asDate($st, 'long');
                }
                //ปิดตรวจสอบวุฒิการศึกษา
            }
            //ปิดตรวจสอบวุฒิการศึกษา
            if ($startwork) {
                if ($syear and $eday) {
                    if ($exst and $exend) {
                        if ($syear < 1970 or $eyear < 1970) {
                            $yearad = 1970 - $syear;
                            $syear = 1970;
                            $yearads = 1970 - $eyear;
                            $eyear = 1970;
                        } else {
                            $yearad = 0;
                            $yearads = 0;
                        }

                        $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                        $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                        $mages = $ex_e - $ex_s;
                    }
                    $y1 = (date("Y", $mages) - 1970 + $yearads);
                    $m1 = (date("m", $mages) - 1);
                    $d1 = (date("d", $mages) - 1);
                    $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                    $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                    $mage = $es - $sts;
                    $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                    // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                    $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                    $m = (date("m", $mage) - 1) + $m1;
                    $d = (date("d", $mage) - 1) + $d1;

                    if ($d >= 30) {
                        $m = $m + 1 + $m_Ec;
                        $d = $d - 30 + $d_Ec;
                        if ($m >= 12) {
                            $y = $y + 1 + 11 + $y_Ec;
                            $m = $m - 12;
                            $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                            //    Yii::$app->thaiFormatter->locale = 'th-TH';

                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            return $dmy;
                            //   return $y;
                        }
                    }



                    $y = $y + 11 + $y_Ec;
                    $m = (date("m", $mage) - 1) + $m_Ec;
                    $d = (date("d", $mage) - 1) + $d_Ec;
                    //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                    $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                    //  Yii::$app->thaiFormatter->locale = 'th-TH';
                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                    $d = new ndate();
                    return $dmy;
                    // return Yii::$app->thaiFormatter->asDate($st, 'long');
                    // return ($y.''.$m.''.$d);
                    //     return $y;
                }
                if ($d >= 30) {
                    $m = $m + 1 + $m_Ec;
                    $d = $d - 30 + $d_Ec;
                    if ($m >= 12) {
                        $y = $y + 1 + 11 + $y_Ec;
                        $m = $m - 12;
                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                        //    Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        return $dmy;
                        //  return Yii::$app->thaiFormatter->asDate($st, 'long');  
                        //   return $y;
                    }
                }



                $y = $y + 11 + $y_Ec;
                $m = (date("m", $mage) - 1) + $m_Ec;
                $d = (date("d", $mage) - 1) + $d_Ec;
                //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                //    Yii::$app->thaiFormatter->locale = 'th-TH';
                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                $d = new ndate();
                return $dmy;

                //  return Yii::$app->thaiFormatter->asDate($st, 'long');
            }
            return "--";
        } else {
            return "-";
        }
    }

    public function counterNine($citizen_id) {
        $academic_position = ariStaffAcademic::find()->where(['citizen_id' => $citizen_id])
                ->one();
        $academic_position1 = $academic_position->academic_id;
        // $academic_position1=1;
        // $academic_position2=2;
        //$academic_position3=3;
        // $academic_position4=4;

        $start_study = ariEducation::find()->where(['citizen_id' => $citizen_id])
                ->one();
        $id_stud = $start_study->citizen_id;
        $start = $start_study->start_date;
        $end = $start_study->end_date;
        $authorise_date = ariStaffAcademic::find()->where(['citizen_id' => $citizen_id, 'academic_id' => $academic_position1])
                // ->andWhere(['acade_name_id' => $academic_position1])
                ->one();
        $authorisedate = $authorise_date->authorise_date;
        $extime = ariExtendTime::find()->where(['citizen_id' => $id_stud])
                ->one();
        $exst = $extime->extend_start;
        $exend = $extime->extend_end;
        list( $syear, $smonth, $sday) = explode("-", $start);
        list( $eyear, $emonth, $eday) = explode("-", $end);
        //----------- explode วันที่ต่อเวลาศึกษา ----------
        list( $str_year, $str_month, $str_day) = explode("-", $exst);
        list( $end_year, $end_month, $end_day) = explode("-", $exend);
        //----------- explode วันที่แต่งตั้งตแหน่ง ----------
        list( $styear, $stmonth, $stday) = explode("-", $authorisedate);
        list( $yyear, $ymonth, $yday) = explode("-", $years);
        $subquery = ariStaffExecutive::findOne(['citizen_id' => $citizen_id]);
        $cit_id = $subquery->citizen_id;
        // เริ่ม ตรวจสอบว่าเคยดำรงตำแหน่งบริหารหรือไม่
        //---------- คำนวณระยะเวลาที่ดำรงตำแหน่งบริหาร ---------------
        if ($cit_id == "") {
            if ($authorise_date) {
                if ($syear and $eday) {
                    if ($exst and $exend) {
                        if ($syear < 1970 or $eyear < 1970) {
                            $yearad = 1970 - $syear;
                            $syear = 1970;
                            $yearads = 1970 - $eyear;
                            $eyear = 1970;
                        } else {
                            $yearad = 0;
                            $yearads = 0;
                        }

                        $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                        $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                        $mages = $ex_e - $ex_s;
                    }
                    $y1 = (date("Y", $mages) - 1970 + $yearads);
                    $m1 = (date("m", $mages) - 1);
                    $d1 = (date("d", $mages) - 1);
                    $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                    $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                    $mage = $es - $sts;
                    $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                    // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                    $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                    $m = (date("m", $mage) - 1) + $m1;
                    $d = (date("d", $mage) - 1) + $d1;

                    if ($d >= 30) {
                        $m = $m + 1;
                        $d = $d - 30;
                        if ($m >= 12) {
                            $y = $y + 1 + 9;
                            $m = $m - 12;
                            $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            //  return $d->getThaiShortDate($dmy); 
                            return $dmy;
                        }
                    }



                    $y = $y + 9;
                    $m = (date("m", $mage) - 1);
                    $d = (date("d", $mage) - 1);
                    //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                    $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                    //  Yii::$app->thaiFormatter->locale = 'th-TH';
                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                    $d = new ndate();
                    //   return $d->getThaiShortDate($dmy);
                    // return ($y.''.$m.''.$d);
                    return $dmy;
                }
                if ($d >= 30) {
                    $m = $m + 1;
                    $d = $d - 30;
                    if ($m >= 12) {
                        $y = $y + 1 + 9;
                        $m = $m - 12;
                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                        //    Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        //  return $d->getThaiShortDate($dmy);  
                        return dmy;
                    }
                }



                $y = $y + 9;
                $m = (date("m", $mage) - 1);
                $d = (date("d", $mage) - 1);
                //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                //  Yii::$app->thaiFormatter->locale = 'th-TH';
                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                $d = new ndate();
                //   return $d->getThaiShortDate($dmy); 
                return $dmy;
            }
            return "-";
        }
        $q = ariStaffExecutive::findOne(['citizen_id' => $citizen_id]);
        $date_start = $q->date_start;
        $date_stop = $q->date_stop;
//----------- explode วันที่ดำรงตำแหน่งบริหาร
        list( $syearEc, $smonthEc, $sdayEc) = explode("-", $date_start);
        list( $eyearEc, $emonthEc, $edayEc) = explode("-", $date_stop);
//-------------------------------------
        $ec_s = mktime(1, 1, 1, $smonthEc, $sdayEc, $syearEc);
        $ec_e = mktime(1, 1, 1, $emonthEc, $edayEc, $eyearEc);
        $Ec = $ec_e - $ec_s;
//---------- คำนวณระยะเวลาที่ดำรงตำแหน่งบริหาร ---------------
        $y_Ec = (date("Y", $Ec) - 1970 + $yearads);
        $m_Ec = (date("m", $Ec) - 1);
        $d_Ec = (date("d", $Ec) - 1);
//------------------------------------------------------
//-------------------------------------------------------
        if ($authorise_date) {
            if ($syear and $eday) {
                if ($exst and $exend) {
                    if ($syear < 1970 or $eyear < 1970) {
                        $yearad = 1970 - $syear;
                        $syear = 1970;
                        $yearads = 1970 - $eyear;
                        $eyear = 1970;
                    } else {
                        $yearad = 0;
                        $yearads = 0;
                    }

                    $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                    $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                    $mages = $ex_e - $ex_s;
                }
                $y1 = (date("Y", $mages) - 1970 + $yearads);
                $m1 = (date("m", $mages) - 1);
                $d1 = (date("d", $mages) - 1);
                $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                $mage = $es - $sts;
                $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                $m = (date("m", $mage) - 1) + $m1;
                $d = (date("d", $mage) - 1) + $d1;

                if ($d >= 30) {
                    $m = $m + 1 + $m_Ec;
                    $d = $d - 30 + $d_Ec;
                    if ($m >= 12) {
                        $y = $y + 1 + 9 + $y_Ec;
                        $m = $m - 12;
                        $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        //  return $d->getThaiShortDate($dmy); 
                        return $dmy;
                    }
                }



                $y = $y + 9 + $y_Ec;
                $m = (date("m", $mage) - 1) + $m_Ec;
                $d = (date("d", $mage) - 1) + $d_Ec;
                //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                //  Yii::$app->thaiFormatter->locale = 'th-TH';
                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                $d = new ndate();
                //   return $d->getThaiShortDate($dmy);
                // return ($y.''.$m.''.$d);
                return $dmy;
            }
            if ($d >= 30) {
                $m = $m + 1 + $m_Ec;
                $d = $d - 30 + $d_Ec;
                if ($m >= 12) {
                    $y = $y + 1 + 9 + $y_Ec;
                    $m = $m - 12;
                    $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                    //     Yii::$app->thaiFormatter->locale = 'th-TH';
                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                    $d = new ndate();
                    //  return $d->getThaiShortDate($dmy);  
                    return dmy;
                }
            }



            $y = $y + 9 + $y_Ec;
            $m = (date("m", $mage) - 1) + $m_Ec;
            $d = (date("d", $mage) - 1) + $d_Ec;
            //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
            $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
            //   Yii::$app->thaiFormatter->locale = 'th-TH';
            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
            $d = new ndate();
            //   return $d->getThaiShortDate($dmy); 
            return $dmy;
        }
        return "-";
    }

    public function Qualificate($citizen_id) {
        $academic = ariStaffAcademic::find()->where(['citizen_id' => $citizen_id])
                ->orderBy(authorise_date)
                ->one();
        $academic_id = $academic->academic_id;
        $academic_position1 = $academic->academic_id;
        $level = tblStaffEducate::find()->where(['citizen_id' => $citizen_id])
                ->one();
        $level_id = $level->level_id;

        if ($academic_id = 3) {
            $subQuery = tblStaffHistory::findOne(['citizen_id' => $citizen_id]);
            $status_id = $subQuery->status_id;
            if ($status_id = 4) {
                $start_study = ariEducation::find()->where(['citizen_id' => $citizen_id])
                        ->one();
                $id_stud = $start_study->citizen_id;
                $start = $start_study->start_date;
                $end = $start_study->end_date;
                $authorise_date = ariStaffAcademic::find()->where(['citizen_id' => $citizen_id, 'academic_id' => $academic_position1])
                        // ->andWhere(['acade_name_id' => $academic_position1])
                        ->one();
                $authorisedate = $authorise_date->authorise_date;
                $extime = ariExtendTime::find()->where(['citizen_id' => $id_stud])
                        ->one();
                $exst = $extime->extend_start;
                $exend = $extime->extend_end;
                list( $syear, $smonth, $sday) = explode("-", $start);
                list( $eyear, $emonth, $eday) = explode("-", $end);
                //----------- explode วันที่ต่อเวลาศึกษา ----------
                list( $str_year, $str_month, $str_day) = explode("-", $exst);
                list( $end_year, $end_month, $end_day) = explode("-", $exend);
                //----------- explode วันที่แต่งตั้งตแหน่ง ----------
                list( $styear, $stmonth, $stday) = explode("-", $authorisedate);
                list( $yyear, $ymonth, $yday) = explode("-", $years);
                if ($authorise_date) {
                    if ($syear and $eday) {
                        if ($exst and $exend) {
                            if ($syear < 1970 or $eyear < 1970) {
                                $yearad = 1970 - $syear;
                                $syear = 1970;
                                $yearads = 1970 - $eyear;
                                $eyear = 1970;
                            } else {
                                $yearad = 0;
                                $yearads = 0;
                            }

                            $ex_s = mktime(1, 1, 1, $str_month, $str_day, $str_year);
                            $ex_e = mktime(1, 1, 1, $end_month, $end_day, $end_year);
                            $mages = $ex_e - $ex_s;
                        }
                        $y1 = (date("Y", $mages) - 1970 + $yearads);
                        $m1 = (date("m", $mages) - 1);
                        $d1 = (date("d", $mages) - 1);
                        $sts = mktime(1, 1, 1, $smonth, $sday, $syear);
                        $es = mktime(1, 1, 1, $emonth, $eday, $eyear);
                        $mage = $es - $sts;
                        $age = (date("Y", $mage) - 1970 + $yearads) . "ปี" . (date("m", $mage) - 1) . "เดือน" . (date("d", $mage) - 1) . "วัน";
                        // $years=Yii::$app->Formatter->asDate($ages, 'php:Y-m-d');

                        $y = (date("Y", $mage) - 1970 + $yearads) + $y1;
                        $m = (date("m", $mage) - 1) + $m1;
                        $d = (date("d", $mage) - 1) + $d1;

                        if ($d >= 30) {
                            $m = $m + 1;
                            $d = $d - 30;
                            if ($m >= 12) {
                                $y = $y + 1 + 3;
                                $m = $m - 12;
                                $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $d->getThaiShortDate($dmy);
                                //  return $dmy;
                            }
                        }



                        $y = $y + 3;
                        $m = (date("m", $mage) - 1);
                        $d = (date("d", $mage) - 1);
                        //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                        //  Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        return $d->getThaiShortDate($dmy);
                        // return ($y.''.$m.''.$d);
                        // return $dmy;
                    }
                    if ($d >= 30) {
                        $m = $m + 1;
                        $d = $d - 30;
                        if ($m >= 12) {
                            $y = $y + 1 + 3;
                            $m = $m - 12;
                            $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                            //    Yii::$app->thaiFormatter->locale = 'th-TH';
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            return $d->getThaiShortDate($dmy);
                            //  return dmy;
                        }
                    }



                    $y = $y + 3;
                    $m = (date("m", $mage) - 1);
                    $d = (date("d", $mage) - 1);
                    //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                    $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                    // Yii::$app->thaiFormatter->locale = 'th-TH';
                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                    $d = new ndate();
                    return $d->getThaiShortDate($dmy);
                    // return $dmy;
                }
            }
            $academic = ariStaffAcademic::find()->where(['citizen_id' => $citizen_id])
                    ->orderBy(authorise_date)
                    ->one();
            $academic_id = $academic->academic_id;
            $academic_position1 = $academic->academic_id;
            $authorise_date = ariStaffAcademic::find()->where(['citizen_id' => $citizen_id, 'academic_id' => $academic_position1])
                    // ->andWhere(['acade_name_id' => $academic_position1])
                    ->one();
            $authorisedate = $authorise_date->authorise_date;

            //----------- explode วันที่ต่อเวลาศึกษา ----------
            //  list( $str_year, $str_month , $str_day) = explode("-" , $exst);
            //  list( $end_year, $end_month , $end_day) = explode("-" , $exend);
            //----------- explode วันที่แต่งตั้งตแหน่ง ----------
            list( $styear, $stmonth, $stday) = explode("-", $authorisedate);
            list( $yyear, $ymonth, $yday) = explode("-", $years);
            if ($authorise_date) {

                if ($d >= 30) {
                    $m = $m + 1;
                    $d = $d - 30;
                    if ($m >= 12) {
                        $y = $y + 1 + 3;
                        $m = $m - 12;
                        $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                        //    Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        return $d->getThaiShortDate($dmy);
                        //    return dmy;
                    }
                }



                $y = $y + 3;
                $m = (date("m", $mage) - 1);
                $d = (date("d", $mage) - 1);
                //   $st = mktime(1, 1, 1, $stmonth+$m, $stday+$d, $styear+$y);
                $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                //  Yii::$app->thaiFormatter->locale = 'th-TH';
                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                $d = new ndate();
                return $d->getThaiShortDate($dmy);
                // return $dmy;    
            }
        }
        $status = tblStaffHistory::find()->where(['citizen_id' => $citizen_id])
                ->one();
        $statusid = $status->status;
        if ($academic_id = 4) {
            $query = NEW Query();
            $query->select([
                        "status_id"
                    ])
                    ->from("tbl_staff_history")
                    ->where(["citizen_id" => $citizen_id])
                    ->orderBy([
                        "status_date" => SORT_DESC
            ]);

            $result1 = $query->createCommand()->queryOne();
            $status_id = $result1[status_id];
            //  $status_id=1;//ข้อมูลจำลอง
            if ($status_id == 1) {
                //start
                $query = NEW Query();
                $query->select([
                            "level_id"
                        ])
                        ->from("tbl_staff_educate")
                        ->where(["citizen_id" => $citizen_id])
                        ->orderBy([
                            "level_id" => SORT_DESC
                ]);

                $result1 = $query->createCommand()->queryOne();
                $level_id = $result1[level_id];
                //end
                //    $level_id=2;//ข้อมูลจำลอง

                if ($level_id == 1) {
//               $query = NEW Query();
//                $query->select([
//                    "status_id"
//                ])
//                ->from("tbl_staff_history")
//                ->where(["citizen_id" => $citizen_id])
//                ->orderBy([
//                    "status_date" => SORT_DESC
//                ]);
//
//            $result1 = $query->createCommand()->queryOne();
//            $stid=1;
                    //  if($stid==1){
                    //ตรวจสอบว่า ยังใช้วุฒิป.ตรี และยังปฏิบัติราชการอยู่
                    $querys = tblStaffPosition::findOne(['citizen_id' => $citizen_id]);
                    $start_work = $querys->date_work;
                    $today = date("Y-m-d");
                    $studydate = explode("-", $today);
                    // $timStmp1 = mktime(0,0,0,$studydate[1],$studydate[2],$studydate[0]);
                    $todayY = $studydate[0];
                    $todayM = $studydate[1];
                    $todayD = $studydate[2];
                    $bdate = explode("-", $start_work);
                    //   $timStmp2 = mktime(0,0,0,$bdate[1],$bdate[2],$bdate[0]);
                    $bY = $bdate[0];
                    $bM = $bdate[1];
                    $bD = $bdate[2];

                    $LeapYear = date("L"); // 1 = leap year Feb has 29 day

                    $d31 = ['01', '03', '05', '07', '08', '10', '12'];
                    $d30 = ['04', '06', '09', '11'];
                    $d28 = ['02'];

                    $todayM2 = $bM;

                    if ([$todayM2, $d31] == TRUE) {
                        $subD = 31;
                    } else if ([$todayM2, $d30] == TRUE) {
                        $subD = 30;
                    } else if ([$todayM2, $d28] == TRUE) {
                        if ($LeapYear == 1) {
                            $subD = 29;
                        } else {
                            $subD = 28;
                        }
                    }

                    if (($todayY == $bY) && ($todayM == $bM) && ($todayD == $bD)) {
                        $aY2 = 0;
                        $aM2 = 0;
                        $aD2 = 0;
                    } else if (($todayY == $bY) && ($todayM == $bM) && ($todayD > $bD)) {
                        $aY2 = 0;
                        $aM2 = 0;
                        $aD2 = $todayD - $bD;
                    }
                    //else if(($todayY==$bY)&&($todayM==$bM)&&($todayD<$bD)) { $aY2=0; $aM2=12-($todayM-$bM); $aD2=$subD-($bD-$todayD); } 
                    else if (($todayY == $bY) && ($todayM > $bM) && ($todayD == $bD)) {
                        $aY2 = 0;
                        $aM2 = $todayM - $bM;
                        $aD2 = 0;
                    } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD > $bD)) {
                        $aY2 = 0;
                        $aM2 = $todayM - $bM;
                        $aD2 = $todayD - $bD;
                    } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD < $bD)) {
                        $aY2 = 0;
                        $aM2 = 12 - ($todayM - $bM);
                        $aD2 = $subD - ($bD - $todayD);
                    } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD == $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = $todayM - $bM;
                        $aD2 = 0;
                    } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD > $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = $todayM - $bM;
                        $aD2 = $todayD - $bD;
                    } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD < $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = $todayM - $bM - 1;
                        $aD2 = $subD - ($bD - $todayD);
                    } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD == $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 12 - ($bM - $todayM);
                        $aD2 = 0;
                    } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD < $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 12 - ($bM - $todayM) - 1;
                        $aD2 = $bD - $todayD;
                    } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD > $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 12 - ($bM - $todayM);
                        $aD2 = $todayD - $bD;
                    } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD == $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = 0;
                        $aD2 = 0;
                    } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD > $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = 0;
                        $aD2 = $todayD - $bD;
                    } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD < $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 11;
                        $aD2 = $subD - ($bD - $todayD);
                    }


                    $age = $aY2 . "ปี" . $aM2 . "เดือน" . $aD2 . "วัน";
                    if ($aY2 <= 2) {
                        return "ต้องศึกษาต่อระดับปริญญาโท";
                    }
                } else if ($level_id == 2) {

                    //คำนวณวันที่สามารถเริ่มขอตำแหน่งทางวิชาการกรณี วุฒิ ป.ตรี แต่เรียนจบ ป.โท จะใช้ วุฒิ ป.โท ยื่นขอ
                    $query = NEW Query();
                    $query->select([
                                "level_id"
                            ])
                            ->from("tbl_staff_educate")
                            ->where(["citizen_id" => $citizen_id])
                            ->orderBy([
                                "level_id" => SORT_ASC
                    ]);

                    $result = $query->createCommand()->queryOne();
                    $levelid = $result[level_id];
                    //  $levelid=1;//ข้อมูลจำลอง
                    if ($levelid == 1) {
                        $query = NEW Query();
                        $query->select([
                                    "status_date"
                                ])
                                ->from("tbl_staff_history")
                                ->where(["citizen_id" => $citizen_id, "status_id" => 4])
                                ->orderBy([
                                    "status_date" => SORT_DESC
                        ]);

                        $result = $query->createCommand()->queryOne();
                        $status_date = $result[status_date];
                        //    $status_date="2016-01-05"; //ข้อมูลจำลอง

                        $studydate = explode("-", $status_date);
                        //   $timStmp1 = mktime(0,0,0,$studydate[1],$studydate[2],$studydate[0]);
                        $todayY = $studydate[0];
                        $todayM = $studydate[1];
                        $todayD = $studydate[2];
                        $query = tblStaffPosition::find()->where(['citizen_id' => $citizen_id])
                                ->one();
                        $start_work = $query->date_work;
                        $bdate = explode("-", $start_work);
                        //  $timStmp2 = mktime(0,0,0,$bdate[1],$bdate[2],$bdate[0]);
                        $bY = $bdate[0];
                        $bM = $bdate[1];
                        $bD = $bdate[2];
                        //       if ($timStmp1 == $timStmp2) {
                        //          return "\$date = \$date2";
                        //        }
                        //        $LeapYear=date("L"); // 1 = leap year Feb has 29 day

                        $d31 = ['01', '03', '05', '07', '08', '10', '12'];
                        $d30 = ['04', '06', '09', '11'];
                        $d28 = ['02'];

                        $todayM2 = $bM;

                        if ([$todayM2, $d31] == TRUE) {
                            $subD = 31;
                        } else if ([$todayM2, $d30] == TRUE) {
                            $subD = 30;
                        } else if ([$todayM2, $d28] == TRUE) {
                            if ($LeapYear == 1) {
                                $subD = 29;
                            } else {
                                $subD = 28;
                            }
                        }

                        if (($todayY == $bY) && ($todayM == $bM) && ($todayD == $bD)) {
                            $aY2 = 0;
                            $aM2 = 0;
                            $aD2 = 0;
                        } else if (($todayY == $bY) && ($todayM == $bM) && ($todayD > $bD)) {
                            $aY2 = 0;
                            $aM2 = 0;
                            $aD2 = $todayD - $bD;
                        }
                        //else if(($todayY==$bY)&&($todayM==$bM)&&($todayD<$bD)) { $aY2=0; $aM2=12-($todayM-$bM); $aD2=$subD-($bD-$todayD); } 
                        else if (($todayY == $bY) && ($todayM > $bM) && ($todayD == $bD)) {
                            $aY2 = 0;
                            $aM2 = $todayM - $bM;
                            $aD2 = 0;
                        } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD > $bD)) {
                            $aY2 = 0;
                            $aM2 = $todayM - $bM;
                            $aD2 = $todayD - $bD;
                        } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD < $bD)) {
                            $aY2 = 0;
                            $aM2 = 12 - ($todayM - $bM);
                            $aD2 = $subD - ($bD - $todayD);
                        } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD == $bD)) {
                            $aY2 = $todayY - $bY;
                            $aM2 = $todayM - $bM;
                            $aD2 = 0;
                        } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD > $bD)) {
                            $aY2 = $todayY - $bY;
                            $aM2 = $todayM - $bM;
                            $aD2 = $todayD - $bD;
                        } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD < $bD)) {
                            $aY2 = $todayY - $bY;
                            $aM2 = $todayM - $bM - 1;
                            $aD2 = $subD - ($bD - $todayD);
                        } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD == $bD)) {
                            $aY2 = $todayY - $bY - 1;
                            $aM2 = 12 - ($bM - $todayM);
                            $aD2 = 0;
                        } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD < $bD)) {
                            $aY2 = $todayY - $bY - 1;
                            $aM2 = 12 - ($bM - $todayM) - 1;
                            $aD2 = $bD - $todayD;
                        } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD > $bD)) {
                            $aY2 = $todayY - $bY - 1;
                            $aM2 = 12 - ($bM - $todayM);
                            $aD2 = $todayD - $bD;
                        } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD == $bD)) {
                            $aY2 = $todayY - $bY;
                            $aM2 = 0;
                            $aD2 = 0;
                        } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD > $bD)) {
                            $aY2 = $todayY - $bY;
                            $aM2 = 0;
                            $aD2 = $todayD - $bD;
                        } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD < $bD)) {
                            $aY2 = $todayY - $bY - 1;
                            $aM2 = 11;
                            $aD2 = $subD - ($bD - $todayD);
                        }


                        $age = $aY2 . "ปี" . $aM2 . "เดือน" . $aD2 . "วัน";
                        $age_work = 3285 - ((($aY2 * 365) + ($aM2 * 31)) + $aD2);
                        $ratio = (5 * 365) / (9 * 365);
                        $ages = ($age_work * $ratio) / 365;
                        $ages_work = floor($ages);
                        //คำนวณวันที่สามารถยื่นขอได้กรณีบรรจุตรี เรียนต่อ จบป.โท
                        $query = NEW Query();
                        $query->select([
                                    "status_date"
                                ])
                                ->from("tbl_staff_history")
                                ->where(["citizen_id" => $citizen_id, "status_id" => 1])
                                ->orderBy([
                                    "status_date" => SORT_DESC
                        ]);

                        $result2 = $query->createCommand()->queryOne();
                        $status_date = $result2[status_date];
                        list( $styear, $stmonth, $stday) = explode("-", $status_date);
                        if ($status_date) {

                            if ($d >= 30) {
                                $m = $m + 1;
                                $d = $d - 30;
                                if ($m >= 12) {
                                    $y = $y + 1 + $ages_work;
                                    $m = $m - 12;
                                    $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                    //       Yii::$app->thaiFormatter->locale = 'th-TH';
                                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                    $d = new ndate();
                                    return $d->getThaiShortDate($dmy);
                                    //    return dmy;
                                }
                            }



                            $y = $y + $ages_work;
                            $m = (date("m", $mage) - 1);
                            $d = (date("d", $mage) - 1);

                            $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                            //    Yii::$app->thaiFormatter->locale = 'th-TH';
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            // return $d->getThaiShortDate($dmy); 
                        }
                        //สิ้นสุด
                        return "(หลังจากสำเร็จการศึกษาต้องทำงานอีก" . '  ' . $ages_work . " ปี)" . "วันที่ยื่นขอได้ " . $d->getThaiShortDate($dmy);
                    }
                    //คำนวณหาวันที่ครบวันที่สามารถขอตำแหน่งทางวิชาการได้กรณีบรรจุวุฒิปริญญาโท
                    $startdate = tblStaffPosition::find()->where(['citizen_id' => $citizen_id])
                            ->orderBy(date_work)
                            ->one();
                    $startwork = $startdate->date_work;
                    list( $styear, $stmonth, $stday) = explode("-", $startwork);

                    if ($startwork) {

                        if ($d >= 30) {
                            $m = $m + 1;
                            $d = $d - 30;
                            if ($m >= 12) {
                                $y = $y + 1 + 5;
                                $m = $m - 12;
                                $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                //    Yii::$app->thaiFormatter->locale = 'th-TH';
                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $d->getThaiShortDate($dmy);
                                //    return dmy;
                            }
                        }



                        $y = $y + 5;
                        $m = (date("m", $mage) - 1);
                        $d = (date("d", $mage) - 1);

                        $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                        //   Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        return $d->getThaiShortDate($dmy);
                    }//สิ้นสุดคำนวณหาวันที่ครบวันที่สามารถขอตำแหน่งทางวิชาการได้กรณีบรรจุวุฒิปริญญาโท
                } //สิ้นสุด คำนวณวันที่สามารถเริ่มขอตำแหน่งทางวิชาการกรณี วุฒิ ป.ตรี แต่เรียนจบ ป.โท จะใช้ วุฒิ ป.โท ยื่นขอ
                //เริ่มคำนวณหาวันที่สามารถยื่นขอตำแหน่งวิชาการกรณีมีวุฒิป.โท แต่เรียนต่อ และปรับวุฒิป.เอกใช้วุฒิป.เอกขอตำแหน่ง
                $query = NEW Query();
                $query->select([
                            "level_id"
                        ])
                        ->from("tbl_staff_educate")
                        ->where(["citizen_id" => $citizen_id])
                        ->orderBy([
                            "level_id" => SORT_ASC
                ]);

                $result = $query->createCommand()->queryOne();
                $lev_id = $result[level_id];
                if ($lev_id == 2) {
                    $query = NEW Query();
                    $query->select([
                                "status_date"
                            ])
                            ->from("tbl_staff_history")
                            ->where(["citizen_id" => $citizen_id, "status_id" => 4])
                            ->orderBy([
                                "status_date" => SORT_DESC
                    ]);

                    $result = $query->createCommand()->queryOne();
                    $statusdate = $result[status_date];
                    //    $status_date="2016-01-05"; //ข้อมูลจำลอง

                    $studydate = explode("-", $statusdate);
                    //   $timStmp1 = mktime(0,0,0,$studydate[1],$studydate[2],$studydate[0]);
                    $todayY = $studydate[0];
                    $todayM = $studydate[1];
                    $todayD = $studydate[2];
                    $query = tblStaffPosition::find()->where(['citizen_id' => $citizen_id])
                            ->one();
                    $start_work = $query->date_work;
                    $bdate = explode("-", $start_work);
                    //  $timStmp2 = mktime(0,0,0,$bdate[1],$bdate[2],$bdate[0]);
                    $bY = $bdate[0];
                    $bM = $bdate[1];
                    $bD = $bdate[2];
                    //       if ($timStmp1 == $timStmp2) {
                    //          return "\$date = \$date2";
                    //        }
                    //        $LeapYear=date("L"); // 1 = leap year Feb has 29 day

                    $d31 = ['01', '03', '05', '07', '08', '10', '12'];
                    $d30 = ['04', '06', '09', '11'];
                    $d28 = ['02'];

                    $todayM2 = $bM;

                    if ([$todayM2, $d31] == TRUE) {
                        $subD = 31;
                    } else if ([$todayM2, $d30] == TRUE) {
                        $subD = 30;
                    } else if ([$todayM2, $d28] == TRUE) {
                        if ($LeapYear == 1) {
                            $subD = 29;
                        } else {
                            $subD = 28;
                        }
                    }

                    if (($todayY == $bY) && ($todayM == $bM) && ($todayD == $bD)) {
                        $aY2 = 0;
                        $aM2 = 0;
                        $aD2 = 0;
                    } else if (($todayY == $bY) && ($todayM == $bM) && ($todayD > $bD)) {
                        $aY2 = 0;
                        $aM2 = 0;
                        $aD2 = $todayD - $bD;
                    }
                    //else if(($todayY==$bY)&&($todayM==$bM)&&($todayD<$bD)) { $aY2=0; $aM2=12-($todayM-$bM); $aD2=$subD-($bD-$todayD); } 
                    else if (($todayY == $bY) && ($todayM > $bM) && ($todayD == $bD)) {
                        $aY2 = 0;
                        $aM2 = $todayM - $bM;
                        $aD2 = 0;
                    } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD > $bD)) {
                        $aY2 = 0;
                        $aM2 = $todayM - $bM;
                        $aD2 = $todayD - $bD;
                    } else if (($todayY == $bY) && ($todayM > $bM) && ($todayD < $bD)) {
                        $aY2 = 0;
                        $aM2 = 12 - ($todayM - $bM);
                        $aD2 = $subD - ($bD - $todayD);
                    } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD == $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = $todayM - $bM;
                        $aD2 = 0;
                    } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD > $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = $todayM - $bM;
                        $aD2 = $todayD - $bD;
                    } else if (($todayY > $bY) && ($todayM > $bM) && ($todayD < $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = $todayM - $bM - 1;
                        $aD2 = $subD - ($bD - $todayD);
                    } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD == $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 12 - ($bM - $todayM);
                        $aD2 = 0;
                    } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD < $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 12 - ($bM - $todayM) - 1;
                        $aD2 = $bD - $todayD;
                    } else if (($todayY > $bY) && ($todayM < $bM) && ($todayD > $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 12 - ($bM - $todayM);
                        $aD2 = $todayD - $bD;
                    } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD == $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = 0;
                        $aD2 = 0;
                    } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD > $bD)) {
                        $aY2 = $todayY - $bY;
                        $aM2 = 0;
                        $aD2 = $todayD - $bD;
                    } else if (($todayY > $bY) && ($todayM == $bM) && ($todayD < $bD)) {
                        $aY2 = $todayY - $bY - 1;
                        $aM2 = 11;
                        $aD2 = $subD - ($bD - $todayD);
                    }


                    $age = $aY2 . "ปี" . $aM2 . "เดือน" . $aD2 . "วัน";
                    $age_work = 1825 - ((($aY2 * 365) + ($aM2 * 31)) + $aD2);
                    $ratio = (2 * 365) / (5 * 365);
                    $ages = ($age_work * $ratio) / 365;
                    $ages_work = floor($ages);
                    //คำนวณวันที่สามารถยื่นขอได้กรณีบรรจุโท เรียนต่อ จบป.เอก
                    $query = NEW Query();
                    $query->select([
                                "status_date"
                            ])
                            ->from("tbl_staff_history")
                            ->where(["citizen_id" => $citizen_id, "status_id" => 1])
                            ->orderBy([
                                "status_date" => SORT_DESC
                    ]);

                    $result2 = $query->createCommand()->queryOne();
                    $status_date = $result2[status_date];
                    list( $styear, $stmonth, $stday) = explode("-", $status_date);
                    if ($status_date) {

                        if ($d >= 30) {
                            $m = $m + 1;
                            $d = $d - 30;
                            if ($m >= 12) {
                                $y = $y + 1 + $ages_work;
                                $m = $m - 12;
                                $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                                //     Yii::$app->thaiFormatter->locale = 'th-TH';
                                $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                                $d = new ndate();
                                return $d->getThaiShortDate($dmy);
                                //    return dmy;
                            }
                        }



                        $y = $y + $ages_work;
                        $m = (date("m", $mage) - 1);
                        $d = (date("d", $mage) - 1);

                        $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                        //  Yii::$app->thaiFormatter->locale = 'th-TH';
                        $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                        $d = new ndate();
                        // return $d->getThaiShortDate($dmy); 
                    }
                    //สิ้นสุด
                    return "(หลังจากสำเร็จการศึกษาต้องทำงานอีก" . '  ' . $ages_work . " ปี)" . "วันที่ยื่นขอได้ " . $d->getThaiShortDate($dmy);
                }
                //คำนวณหาวันที่ครบวันที่สามารถขอตำแหน่งทางวิชาการได้กรณีบรรจุวุฒิปริญญาเอก
                $startdate = tblStaffPosition::find()->where(['citizen_id' => $citizen_id])
                        ->orderBy(date_work)
                        ->one();
                $startwork = $startdate->date_work;
                list( $styear, $stmonth, $stday) = explode("-", $startwork);

                if ($startwork) {

                    if ($d >= 30) {
                        $m = $m + 1;
                        $d = $d - 30;
                        if ($m >= 12) {
                            $y = $y + 1 + 2;
                            $m = $m - 12;
                            $st = mktime(1, 1, 1, $stmonth + $m, $stday + $d, $styear + $y);
                            //  Yii::$app->thaiFormatter->locale = 'th-TH';
                            $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                            $d = new ndate();
                            return $d->getThaiShortDate($dmy);
                            //    return dmy;
                        }
                    }



                    $y = $y + 2;
                    $m = (date("m", $mage) - 1);
                    $d = (date("d", $mage) - 1);

                    $st = mktime(-1, -1, -1, $stmonth + $m, $stday + $d, $styear + $y);
                    //    Yii::$app->thaiFormatter->locale = 'th-TH';
                    $dmy = Yii::$app->Formatter->asDate($st, 'php:Y-m-d');
                    $d = new ndate();
                    return $d->getThaiShortDate($dmy);
                }//สิ้นสุดคำนวณหาวันที่ครบวันที่สามารถขอตำแหน่งทางวิชาการได้กรณีบรรจุวุฒิปริญญาเอก
            } else if ($status_id == 4) {
                return "อยู่ระหว่างลาศึกษาต่อ";
            }
        }
    }

    public function searchAll() {
        $query = $this->find();
        $query->where(['citizen_id' => $_REQUEST['id']]);


        $query->orderBy('citizen_id ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    public function search() {
        // $position_id=4;
        // $subQuery = ariStaffPosition::find()->select('citizen_id')->where(['position_id'=>$position_id]);
        $citizen_id = \common\models\Staff::getStaffCitizen();
        $query = Staff::find()->where(['citizen_id' => $citizen_id]);
        $query->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        //  }
        return $dataProvider;
    }

}
