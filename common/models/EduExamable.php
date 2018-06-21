<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "edu_examable".
 *
 * @property integer $id
 * @property integer $NOX
 * @property integer $Running
 * @property string $COURSECODE
 * @property integer $SECTION
 * @property integer $STUDENTID
 * @property string $STUDENTCODE
 * @property string $COURSENAMEENG
 * @property string $DateMid
 * @property string $TimeMid
 * @property string $RunCode
 * @property string $CHK
 * @property string $CLASSID
 * @property string $CODEX
 * @property string $Enroll148_STUDENTID
 * @property string $PREFIXNAME
 * @property string $STUDENTNAME
 * @property string $STUDENTSURNAME
 * @property string $RoomID
 * @property string $Number
 * @property string $PROGRAMNAME
 * @property string $STUDENTYEAR
 * @property string $ACADYEAR
 * @property string $SEMESTER
 * @property string $FINANCESTATUS
 * @property string $PROGRAMABBENG
 * @property string $ExamType
 * @property string $Semaster
 * @property string $ExamYear
 * @property string $ExamYearX
 * @property string $BYTEDES
 * @property string $Comment
 */
class EduExamable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edu_examable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NOX', 'Running', 'SECTION', 'STUDENTID'], 'integer'],
            [['COURSECODE', 'STUDENTCODE', 'CHK', 'Enroll148_STUDENTID', 'Number', 'ExamYear'], 'string', 'max' => 20],
            [['COURSENAMEENG', 'PROGRAMNAME'], 'string', 'max' => 200],
            [['DateMid', 'TimeMid', 'RunCode', 'CLASSID', 'CODEX', 'PREFIXNAME', 'STUDENTNAME', 'STUDENTSURNAME', 'ExamYearX'], 'string', 'max' => 100],
            [['RoomID', 'PROGRAMABBENG'], 'string', 'max' => 50],
            [['STUDENTYEAR', 'ACADYEAR', 'SEMESTER', 'ExamType', 'Semaster', 'BYTEDES'], 'string', 'max' => 10],
            [['FINANCESTATUS'], 'string', 'max' => 5],
            [['Comment'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'NOX' => 'Nox',
            'Running' => 'Running',
            'COURSECODE' => 'Coursecode',
            'SECTION' => 'Section',
            'STUDENTID' => 'Studentid',
            'STUDENTCODE' => 'Studentcode',
            'COURSENAMEENG' => 'Coursenameeng',
            'DateMid' => 'Date Mid',
            'TimeMid' => 'Time Mid',
            'RunCode' => 'Run Code',
            'CHK' => 'Chk',
            'CLASSID' => 'Classid',
            'CODEX' => 'Codex',
            'Enroll148_STUDENTID' => 'Enroll148  Studentid',
            'PREFIXNAME' => 'Prefixname',
            'STUDENTNAME' => 'Studentname',
            'STUDENTSURNAME' => 'Studentsurname',
            'RoomID' => 'Room ID',
            'Number' => 'Number',
            'PROGRAMNAME' => 'Programname',
            'STUDENTYEAR' => 'Studentyear',
            'ACADYEAR' => 'Acadyear',
            'SEMESTER' => 'Semester',
            'FINANCESTATUS' => 'Financestatus',
            'PROGRAMABBENG' => 'Programabbeng',
            'ExamType' => 'Exam Type',
            'Semaster' => 'Semaster',
            'ExamYear' => 'Exam Year',
            'ExamYearX' => 'Exam Year X',
            'BYTEDES' => 'Bytedes',
            'Comment' => 'Comment',
        ];
    }
}
