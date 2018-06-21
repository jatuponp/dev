<?php

namespace common\models\importreg;

use Yii;
//use app\modules\assess\models\assClassinstructor;

class regClassinstructor extends \yii\db\ActiveRecord {

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
        return 'classinstructor';
    }

    public function getRegclass() {
        return $this->hasOne(regClass::className(), ['CLASSID' => 'CLASSID']);
    }

    public static function chkNum($acadyear, $semester) {

        $query = regEnroll::find()
                        
                        ->where(["campusid" => '2'])
                        ->andWhere([
                            "acadyear" => $acadyear,
                            "semester" => $semester,
                        ])->all();

        $countNew = 0;
        $countUpdate = 0;
        foreach ($query as $q) {
//            $assclassinstructor = assClassinstructor::findOne(["classid" => $q->CLASSID]);
//
//            if (empty($assclassinstructor)) {
//                $countNew++;
//            } elseif ($q->CLASSSTATUS != $assclassinstructor->classstatus || $q->ENROLLSEAT != $assclass->enrollseat) {
//                $countUpdate++;
//            }
        }

        $data["new"] = $countNew;
        $data["update"] = $countUpdate;
        return $data;
    }

}
