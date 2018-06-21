<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ass_qa_comments".
 *
 * @property integer $id
 * @property string $studentid
 * @property string $studentcode
 * @property integer $classid
 * @property string $comments
 */
class assQaComments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ass_qa_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentid', 'classid'], 'required'],
            [['studentid', 'classid'], 'integer'],
            [['comments'], 'string'],
            [['studentcode'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'studentid' => 'Studentid',
            'studentcode' => 'Studentcode',
            'classid' => 'Classid',
            'comments' => 'Comments',
        ];
    }
}
