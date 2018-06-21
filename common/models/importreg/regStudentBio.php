<?php

namespace common\models\importreg;

use Yii;
//use yii\data\ArrayDataProvider;

class regStudentBio extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    
    public static function getDb() {
        return Yii::$app->dbreg;
    }

    public static function tableName() {
        return 'studentbio';
    }

//    public function getMaster() {
//        return $this->hasOne(regStudentBio::className(), ['STUDENTID' => 'STUDENTID']);
//                //->where("std_studentmaster.studentID ");
//    }
}