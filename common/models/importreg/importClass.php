<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\importreg;

use Yii;
use common\models\importreg\regClass;
use common\models\importreg\regCourse;
use common\models\importreg\regEnroll;

class importClass extends \yii\base\Model {

    public $acadyear, $semester;
    public $new, $update;

    public function chkNum($acadyear = NULL, $semester = NULL, $provider = true) {

        $data["class"] = regClass::chkNum($acadyear, $semester);
//        $data["classinstructor"] = $this->dataClassinstructor($acadyear, $semester);
        $data["enroll"] = regEnroll::chkNum($acadyear, $semester);
        $data["course"] = regCourse::chkNum();

        return $data;
    }
    
    

}
