<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "drm_meter".
 *
 * @property integer $id
 * @property integer $months
 * @property integer $terms
 * @property string $years
 * @property string $room_id
 * @property string $meter_start
 * @property string $meter_end
 * @property integer $pay_type
 * @property string $meter_date
 * @property string $meter_user
 * @property integer $ispay
 * @property string $pay_date
 * @property string $pay_billno
 * @property string $pay_amount
 * @property string $pay_user
 */
class DrmMeter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drm_meter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['months', 'room_id'], 'required'],
            [['months', 'terms', 'pay_type', 'ispay'], 'integer'],
            [['meter_start', 'meter_end', 'pay_amount'], 'number'],
            [['meter_date', 'pay_date'], 'safe'],
            [['years', 'room_id'], 'string', 'max' => 10],
            [['meter_user', 'pay_user'], 'string', 'max' => 15],
            [['pay_billno'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'months' => 'ประจำเดือน',
            'terms' => 'เทอม',
            'years' => 'ปีการศึกษา',
            'room_id' => 'หมายเลขห้อง',
            'meter_start' => 'เลขมิเตอร์',
            'meter_end' => 'เลขมิเตอร์',
            'pay_type' => 'Pay Type',
            'meter_date' => 'Meter Date',
            'meter_user' => 'Meter User',
            'ispay' => 'Ispay',
            'pay_date' => 'Pay Date',
            'pay_billno' => 'Pay Billno',
            'pay_amount' => 'Pay Amount',
            'pay_user' => 'Pay User',
        ];
    }
    
    public function scenarios()
    {
        return [
            'default' => ['id', 'months', 'terms', 'years', 'room_id', 'meter_start', 'meter_end', 'pay_type', 'meter_date', 'meter_user'],
            'payment' => ['ispay', 'pay_date', 'pay_billno', 'pay_amount', 'pay_user'],
        ];
    }
}
