<?php

namespace common\models\education;

use Yii;

/**
 * This is the model class for table "edu_feedback".
 *
 * @property integer $id
 * @property string $uid
 * @property string $title
 * @property string $suggest
 * @property string $created
 */
class EduFeedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edu_feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['created'], 'safe'],
            [['uid'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 200],
            [['suggest'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'title' => 'ชื่อเรื่อง',
            'suggest' => 'รายละเอียด',
            'created' => 'Created',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {                
                $this->created = $now;
            }
            return true;
        }
        return false;
    }
}
