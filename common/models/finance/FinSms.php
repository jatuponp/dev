<?php

namespace common\models\finance;

use Yii;

/**
 * This is the model class for table "fin_sms".
 *
 * @property int $id
 * @property string $room_id
 * @property string $mobile
 * @property string $message
 * @property string $created
 */
class FinSms extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'fin_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['room_id', 'mobile', 'message'], 'required'],
            [['created'], 'safe'],
            [['room_id'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 10],
            [['message'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'room_id' => 'Room ID',
            'mobile' => 'เบอร์โทรศัพท์',
            'message' => 'ข้อความ',
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
