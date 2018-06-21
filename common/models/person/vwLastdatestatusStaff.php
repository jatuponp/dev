<?php

namespace common\models\person;

use Yii;

/**
 * This is the model class for table "vw_lastdatestatus_staff".
 *
 * @property string $lastupdate
 * @property string $citizen_id
 * @property string $staff_id
 */
class vwLastdatestatusStaff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vw_lastdatestatus_staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lastupdate'], 'safe'],
            [['citizen_id', 'staff_id'], 'required'],
            [['citizen_id'], 'string', 'max' => 13],
            [['staff_id'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lastupdate' => 'Lastupdate',
            'citizen_id' => 'Citizen ID',
            'staff_id' => 'Staff ID',
        ];
    }
}
