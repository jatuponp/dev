<?php

namespace common\models\education;

use Yii;

/**
 * This is the model class for table "edu_mailbox".
 *
 * @property integer $id
 * @property string $mail_from
 * @property string $mail_to
 * @property string $mail_subject
 * @property string $mail_body
 * @property integer $mail_read
 * @property string $read_date
 * @property string $send_date
 */
class EduMailbox extends \yii\db\ActiveRecord
{
    public $selectall;
    public $to;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edu_mailbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail_from', 'mail_to', 'mail_subject', 'mail_body'], 'required'],
            [['mail_body'], 'string'],
            [['mail_read'], 'integer'],
            [['read_date', 'send_date'], 'safe'],
            [['mail_from'], 'string', 'max' => 100],
            [['mail_to'], 'string', 'max' => 20],
            [['mail_subject'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mail_from' => 'จาก',
            'mail_to' => 'ถึง',
            'to' => '',
            'mail_subject' => 'ชื่อเรื่อง',
            'mail_body' => 'เนื้อความ',
            'mail_read' => 'สถานะ',
            'read_date' => 'อ่านวันที่',
            'send_date' => 'ส่งวันที่',
            'selectall' => 'ถึงทุกคน'
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {                
                $this->send_date = $now;
            }
            return true;
        }
        return false;
    }
}
