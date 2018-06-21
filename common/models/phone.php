<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_phone".
 *
 * @property integer $phone_id
 * @property string $phone_number_in
 * @property string $phone_number_ex
 * @property string $mobile
 * @property string $fax
 * @property integer $dept_id
 * @property string $comments
 * @property string $status
 * @property integer $user_id
 * @property string $modify
 *
 * @property Department $dept
 * @property PhoneOwner[] $phoneOwners
 */
class phone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_number_in', 'dept_id', 'modify'], 'required'],
            [['dept_id', 'user_id'], 'integer'],
            [['modify'], 'safe'],
            [['phone_number_in'], 'string', 'max' => 6],
            [['phone_number_ex', 'mobile', 'fax'], 'string', 'max' => 20],
            [['comments'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone_id' => 'Phone ID',
            'phone_number_in' => 'Phone Number In',
            'phone_number_ex' => 'Phone Number Ex',
            'mobile' => 'Mobile',
            'fax' => 'Fax',
            'dept_id' => 'Dept ID',
            'comments' => 'Comments',
            'status' => 'Status',
            'user_id' => 'User ID',
            'modify' => 'Modify',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDept()
    {
        return $this->hasOne(Department::className(), ['id' => 'dept_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneOwners()
    {
        return $this->hasMany(PhoneOwner::className(), ['phone_id' => 'phone_id']);
    }
}
