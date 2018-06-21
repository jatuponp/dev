<?php

namespace common\components;

use yii\base\Component;
use DateTime;

class ndate extends Component {

    public function getMonth($m = 0) {
        $arr = array(
            "",
            "มกราคม",
            "กุมภาพันธ์",
            "มีนาคม",
            "เมษายน",
            "พฤษภาคม",
            "มิถุนายน",
            "กรกฏาคม",
            "สิงหาคม",
            "กันยายน",
            "ตุลาคม",
            "พฤศจิกายน",
            "ธันวาคม"
        );
        return $arr[$m];
    }

    public function getShortMonth($m = 0) {
        $arr = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        return $arr[$m];
    }

    public function getNowThai() {
        return date("d/m/") . (date("Y") + 543);
    }

    public function getThaiYearOption($start = null, $end = null) {

        $arr = array();
        $y = date("Y") + 543;
        for ($i = ($y - $start); $i <= ($y + $end); $i++) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    public static function convertMysqlToThaiDate($date, $time = true) {
        $arr = explode(" ", $date);
        $arrDate = explode("-", $arr[0]);
        $y = $arrDate[0] + 543;
        $m = $arrDate[1];
        $d = $arrDate[2];
        if ($time) {
            $dDate = "$d/$m/$y $arr[1]";
        } else {
            $dDate = "$d/$m/$y";
//            //$dDate = "$d ".$this->getShortMonth(10). " $y";
//            $dDate = $this->getShortMonth(date('n'));
        }

        return $dDate;
    }

    public function getThaiShortNow() {
        $y = date('Y') + 543;
        $m = $this->getShortMonth(date('n'));
        $d = date('j');

        return "$d $m $y";
    }

    public function getThaiLongNow() {
        $y = date('Y') + 543;
        $m = $this->getMonth(date('n'));
        $d = date('j');

        return "$d $m $y";
    }

    public static function getBillDateTime() {

        $y = date('Y') + 543;
        $m = date('m');
        $d = date('j');
        $time = date('H:i');

        return "$d/$m/$y $time";
    }

    public function getThaiShortDate($date) {
        if ($date != '') {
            $arr = explode(" ", $date);
            $arrDate = explode("-", $arr[0]);
            $y = $arrDate[0] + 543;
            $mn = (int) $arrDate[1];
            $m = $this->getShortMonth($mn);
            $d = $arrDate[2];
            $dDate = "$d $m $y";
            if ($d == '0')
                $dDate = '';

            return $dDate;
        } else {
            return '';
        }
    }

    public function getThaiLongDate($date) {
        if ($date != '') {
            $arr = explode(" ", $date);
            $arrDate = explode("-", $arr[0]);
            $y = $arrDate[0] + 543;
            $mn = (int) $arrDate[1];
            $m = $this->getMonth($mn);
            $d = $arrDate[2];
            $dDate = "$d $m $y";
            if ($d == '0')
                $dDate = '';

            return $dDate;
        } else {
            return '';
        }
    }

    public function yearDropDown() {
        $now = date('Y') + 543;
        $data = array();
        for ($i = $now - 8; $i <= ($now + 2); $i++) {
            $data[$i] = $i;
        }

        return $data;
    }

    public function monthDropDown() {
        $data = [
            '1' => "มกราคม",
            '2' => "กุมภาพันธ์",
            '3' => "มีนาคม",
            '4' => "เมษายน",
            '5' => "พฤษภาคม",
            '6' => "มิถุนายน",
            '7' => "กรกฏาคม",
            '8' => "สิงหาคม",
            '9' => "กันยายน",
            '10' => "ตุลาคม",
            '11' => "พฤศจิกายน",
            '12' => "ธันวาคม"
        ];

        return $data;
    }

    public function getTermsMonth($term) {
        if ($term == 1) {
            $data = ['8' => "ส.ค.", '9' => "ก.ย.", '10' => "ตุ.ค.", '11' => "พ.ย.", '12' => "ธ.ค."];
        } else if ($term == 2) {
            $data = ['1' => "ม.ค.", '2' => "ก.พ.", '3' => "มี.ค.", '4' => "เม.ย.", '5' => "พ.ค."];
        } else if ($term == 3) {
            $data = ['6' => "มิ.ย.", '7' => "ก.ค."];
        }

        return $data;
    }

    function getWorkingDays($startDate, $endDate, $holidays) {
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);


        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week)
                $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week)
                $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)
            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            } else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0) {
            $workingDays += $no_remaining_days;
        }

        //We subtract the holidays
        foreach ($holidays as $holiday) {
            $time_stamp = strtotime($holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N", $time_stamp) != 6 && date("N", $time_stamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }

    public function convertMoney($number) {
        $txtnum1 = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ');
        $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
        $number = str_replace(",", "", $number);
        $number = str_replace(" ", "", $number);
        $number = str_replace("บาท", "", $number);
        $number = explode(".", $number);
        if (sizeof($number) > 2) {
            return 'ทศนิยมหลายตัวนะจ๊ะ';
            exit;
        }
        $strlen = strlen($number[0]);
        $convert = '';
        for ($i = 0; $i < $strlen; $i++) {
            $n = substr($number[0], $i, 1);
            if ($n != 0) {
                if ($i == ($strlen - 1) AND $n == 1) {
                    $convert .= 'เอ็ด';
                } elseif ($i == ($strlen - 2) AND $n == 2) {
                    $convert .= 'ยี่';
                } elseif ($i == ($strlen - 2) AND $n == 1) {
                    $convert .= '';
                } else {
                    $convert .= $txtnum1[$n];
                }
                $convert .= $txtnum2[$strlen - $i - 1];
            }
        }

        $convert .= 'บาท';
        if ($number[1] == '0' OR $number[1] == '00' OR
                $number[1] == '') {
            $convert .= 'ถ้วน';
        } else {
            $strlen = strlen($number[1]);
            for ($i = 0; $i < $strlen; $i++) {
                $n = substr($number[1], $i, 1);
                if ($n != 0) {
                    if ($i == ($strlen - 1) AND $n == 1) {
                        $convert
                                .= 'เอ็ด';
                    } elseif ($i == ($strlen - 2) AND
                            $n == 2) {
                        $convert .= 'ยี่';
                    } elseif ($i == ($strlen - 2) AND
                            $n == 1) {
                        $convert .= '';
                    } else {
                        $convert .= $txtnum1[$n];
                    }
                    $convert .= $txtnum2[$strlen - $i - 1];
                }
            }
            $convert .= 'สตางค์';
        }
        return $convert;
    }

    /**
     * สร้างข้อมูลปี พ.ศ. สำหรับ DROP DOWN LIST
     * 
     * @param boolean $reverse สำหรับเรียงปี true คือ เรียงมาก -> น้อย, false คือ เรียงน้อย -> มาก
     * @param integer $before กำหนดให้แสดงย้อนไปกี่ปี
     * @param integer $after กำหนดให้แสดงไปข้างหน้ากี่ปี
     * 
     * @return เป็นอาร์เรย์
     */
    static function makeDDYear($reverse = FALSE, $before = 3, $after = 1) {

        $this_y = (date("Y") < 2557) ? date("Y") + 543 : date("Y");

        $data = array();
        if (!$reverse) {
            for ($i = $this_y - $before; $i <= ($this_y + $after); $i++) {
                $data[$i] = $i;
            }
        } else {
            for ($i = $this_y + $after; $i >= ($this_y - $before); $i--) {
                $data[$i] = $i;
            }
        }

        return $data;
    }

}
