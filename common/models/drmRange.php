<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "drm_range".
 *
 * @property integer $id
 * @property string $years
 * @property string $terms
 * @property string $booking_begin
 * @property string $booking_end
 * @property string $condition
 * @property integer $building
 * @property integer $allows
 */
class drmRange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drm_range';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['years', 'terms', 'booking_begin', 'booking_end', 'allows'], 'required'],
            [['booking_begin', 'booking_end'], 'safe'],
            [['building', 'allows'], 'integer'],
            [['years'], 'string', 'max' => 10],
            [['terms'], 'string', 'max' => 5],
            [['condition'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'years' => 'Years',
            'terms' => 'Terms',
            'booking_begin' => 'เปิดจองวันที่',
            'booking_end' => 'ปิดจองวันที่',
            'condition' => 'เงื่อนไข',
            'building' => 'Building',
            'allows' => 'เปิด/ปิด การจอง',
        ];
    }
}
