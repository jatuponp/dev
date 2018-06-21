<?php

namespace common\models\phone;

/**
 * This is the model class for table "tbl_phone_owner".
 *
 * @property integer $id
 * @property integer $phone_id
 * @property integer $staff_id
 *
 * @property Staff $staff
 * @property Phone $phone
 */
class tblPhoneOwner extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_phone_owner';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['phone_id', 'staff_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'phone_id' => 'Phone ID',
            'staff_id' => 'Staff ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhone() {
        return $this->hasOne(Phone::className(), ['phone_id' => 'phone_id']);
    }

}
