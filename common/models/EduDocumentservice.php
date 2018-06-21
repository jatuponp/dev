<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "edu_documentservice".
 *
 * @property integer $AUTOID
 * @property string $BATCHNO
 * @property string $REQUESTDATE
 * @property string $STUDENTCODE
 * @property string $STUDENTNAME
 * @property string $STUDENTSURNAME
 * @property string $ACADYEAR
 * @property string $SEMESTER
 * @property string $FEEID
 * @property string $FEEIDNAME
 * @property string $FEEIDWEB
 * @property integer $QUANTITY
 * @property string $REASON
 * @property string $REMARK
 * @property string $STATUS
 */
class EduDocumentservice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edu_documentservice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AUTOID'], 'required'],
            [['AUTOID', 'QUANTITY'], 'integer'],
            [['REQUESTDATE'], 'safe'],
            [['BATCHNO'], 'string', 'max' => 100],
            [['STUDENTCODE'], 'string', 'max' => 20],
            [['STUDENTNAME', 'STUDENTSURNAME', 'FEEIDNAME', 'FEEIDWEB'], 'string', 'max' => 200],
            [['ACADYEAR'], 'string', 'max' => 10],
            [['SEMESTER'], 'string', 'max' => 5],
            [['FEEID', 'STATUS'], 'string', 'max' => 50],
            [['REASON', 'REMARK'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AUTOID' => 'Autoid',
            'BATCHNO' => 'Batchno',
            'REQUESTDATE' => 'Requestdate',
            'STUDENTCODE' => 'Studentcode',
            'STUDENTNAME' => 'Studentname',
            'STUDENTSURNAME' => 'Studentsurname',
            'ACADYEAR' => 'Acadyear',
            'SEMESTER' => 'Semester',
            'FEEID' => 'Feeid',
            'FEEIDNAME' => 'Feeidname',
            'FEEIDWEB' => 'Feeidweb',
            'QUANTITY' => 'Quantity',
            'REASON' => 'Reason',
            'REMARK' => 'Remark',
            'STATUS' => 'Status',
        ];
    }
}
