<?php

namespace common\models\importreg;

use Yii;
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
class regCourse extends \yii\db\ActiveRecord {

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
        return 'course';
    }

    public static function chkNum() {

        $query = regCourse::find()
                ->select(["COURSEID", "COURSESTATUS"])
                ->where('date_format(LASTUPDATEDATETIME, "%Y")=date_format(now(), "%Y")')
                ->all();

//        print_r($query);
//        exit();
        $countNew = 0;
        $countUpdate = 0;
        $tblcourse = NEW tblCourse();
        foreach ($query as $q) {
            $course = $tblcourse->findOne(["courseid" => $q->COURSEID]);

            if (empty($course)) {
                $countNew++;
            } elseif ($q->COURSESTATUS != $course->coursestatus) {
                $countUpdate++;
            }
        }

        $data["new"] = $countNew;
        $data["update"] = $countUpdate;
        return $data;
    }

}
