<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "std_levelid".
 *
 * @property integer $levelid
 * @property string $levelname
 * @property string $levelnameeng
 * @property string $levelabb
 * @property string $levelabbeng
 * @property integer $currentacadyear
 * @property integer $currentsemester
 * @property integer $firstyear
 * @property integer $enrollacadyear
 * @property integer $enrollsemester
 * @property integer $ftesbase
 * @property string $timetablestatus
 * @property integer $timetableacadyear
 * @property integer $timetablesemester
 * @property integer $levelgroupid
 * @property string $lev_id
 * @property string $creditgroup
 * @property integer $kkugroupid
 * @property integer $revenuegroupid
 * @property integer $programgroupid
 * @property string $stdreportwebpagegroupcode01
 * @property string $stdreportwebpagegroupcode02
 * @property integer $graduaterequestacadyear
 * @property integer $graduaterequestsemester
 * @property string $graduaterequestdatefrom
 * @property string $graduaterequestdateto
 */
class stdLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'std_levelid';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['levelid', 'levelname', 'levelnameeng', 'levelabb', 'levelabbeng', 'currentacadyear', 'currentsemester', 'firstyear', 'enrollacadyear', 'enrollsemester', 'ftesbase', 'timetablestatus', 'timetableacadyear', 'timetablesemester', 'levelgroupid', 'lev_id', 'creditgroup', 'kkugroupid', 'revenuegroupid', 'programgroupid', 'stdreportwebpagegroupcode01', 'stdreportwebpagegroupcode02', 'graduaterequestacadyear', 'graduaterequestsemester', 'graduaterequestdatefrom', 'graduaterequestdateto'], 'required'],
            [['levelid', 'currentacadyear', 'currentsemester', 'firstyear', 'enrollacadyear', 'enrollsemester', 'ftesbase', 'timetableacadyear', 'timetablesemester', 'levelgroupid', 'kkugroupid', 'revenuegroupid', 'programgroupid', 'graduaterequestacadyear', 'graduaterequestsemester'], 'integer'],
            [['graduaterequestdatefrom', 'graduaterequestdateto'], 'safe'],
            [['levelname', 'levelnameeng'], 'string', 'max' => 100],
            [['levelabb', 'levelabbeng', 'stdreportwebpagegroupcode01', 'stdreportwebpagegroupcode02'], 'string', 'max' => 50],
            [['timetablestatus', 'lev_id', 'creditgroup'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'levelid' => 'Levelid',
            'levelname' => 'Levelname',
            'levelnameeng' => 'Levelnameeng',
            'levelabb' => 'Levelabb',
            'levelabbeng' => 'Levelabbeng',
            'currentacadyear' => 'Currentacadyear',
            'currentsemester' => 'Currentsemester',
            'firstyear' => 'Firstyear',
            'enrollacadyear' => 'Enrollacadyear',
            'enrollsemester' => 'Enrollsemester',
            'ftesbase' => 'Ftesbase',
            'timetablestatus' => 'Timetablestatus',
            'timetableacadyear' => 'Timetableacadyear',
            'timetablesemester' => 'Timetablesemester',
            'levelgroupid' => 'Levelgroupid',
            'lev_id' => 'Lev ID',
            'creditgroup' => 'Creditgroup',
            'kkugroupid' => 'Kkugroupid',
            'revenuegroupid' => 'Revenuegroupid',
            'programgroupid' => 'Programgroupid',
            'stdreportwebpagegroupcode01' => 'Stdreportwebpagegroupcode01',
            'stdreportwebpagegroupcode02' => 'Stdreportwebpagegroupcode02',
            'graduaterequestacadyear' => 'Graduaterequestacadyear',
            'graduaterequestsemester' => 'Graduaterequestsemester',
            'graduaterequestdatefrom' => 'Graduaterequestdatefrom',
            'graduaterequestdateto' => 'Graduaterequestdateto',
        ];
    }
}
