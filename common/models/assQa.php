<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ass_qa".
 *
 * @property integer $qaid
 * @property string $studentid
 * @property string $studentcode
 * @property integer $classid
 * @property integer $itemid
 * @property integer $score
 *
 * @property AssClass $class
 */
class assQa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ass_qa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentid', 'classid', 'itemid', 'score'], 'required'],
            [['studentid', 'classid', 'itemid', 'score'], 'integer'],
            [['studentcode'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qaid' => 'Qaid',
            'studentid' => 'Studentid',
            'studentcode' => 'Studentcode',
            'classid' => 'Classid',
            'itemid' => 'Itemid',
            'score' => 'Score',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(AssClass::className(), ['classid' => 'classid']);
    }
}
