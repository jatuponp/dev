<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "drm_room".
 *
 * @property string $id
 * @property integer $dorm_id
 * @property integer $capacity
 * @property integer $toilet
 * @property string $room_type
 * @property string $room_picture
 * @property integer $room_status
 */
class drmRoom extends \yii\db\ActiveRecord
{
    public $sum;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drm_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dorm_id', 'capacity', 'toilet', 'room_type', 'room_status'], 'required'],
            [['dorm_id', 'capacity', 'toilet', 'room_status'], 'integer'],
            [['room_type'], 'string'],
            [['id'], 'string', 'max' => 100],
            [['room_picture'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'หมายเลขห้อง',
            'dorm_id' => 'หอพัก',
            'capacity' => 'ความจุ',
            'toilet' => 'ห้องน้ำ',
            'room_type' => 'ประเภทห้อง',
            'room_picture' => 'Room Picture',
            'room_status' => 'สถานะห้อง',
        ];
    }
}
