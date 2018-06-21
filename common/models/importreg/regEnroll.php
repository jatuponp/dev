<?php

namespace common\models\importreg;

use Yii;
use common\models\assess\assEnrollsummary;


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
class regEnroll extends \yii\db\ActiveRecord {

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
        return 'enrollsummary';
    }

    public function getRegclass() {
        return $this->hasOne(regClass::className(), ['CLASSID' => 'CLASSID']);
    }

    public static function chkNum($acadyear, $semester) {

        $query = regEnroll::find()
                        ->joinWith("regclass")
                        ->where(["campusid" => '2'])
                        ->andWhere([
                            "class.acadyear" => $acadyear,
                            "class.semester" => $semester,
                        ])->all();

        $countNew = 0;
        $countUpdate = 0;
        foreach ($query as $q) {
            $enroll = assEnrollsummary::findOne(["classid" => $q->CLASSID]);

            if (empty($enroll)) {
                $countNew++;
            } 
        }

        $data["new"] = $countNew;
        $data["update"] = $countUpdate;
        return $data;
    }

}
