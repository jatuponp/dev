<?php

namespace common\models\flat;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "flat_booking".
 *
 * @property integer $id
 * @property string $room_id
 * @property string $citizen_id
 * @property string $fullname
 * @property string $live_begin
 * @property string $status
 * @property integer $createBy
 * @property string $note
 */
class FlatBooking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'flat_booking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id', 'status'], 'required'],
            [['live_begin'], 'safe'],
            [['status'], 'string'],
            [['createBy'], 'integer'],
            [['room_id', 'citizen_id'], 'string', 'max' => 20],
            [['fullname'], 'string', 'max' => 250],
            [['note'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_id' => 'Room ID',
            'citizen_id' => 'Citizen ID',
            'fullname' => 'Fullname',
            'live_begin' => 'Live Begin',
            'status' => 'Status',
            'createBy' => 'Create By',
            'note' => 'Note',
        ];
    }
    
    public function search($room_id = null) {
        $query = $this->find();
        $query->where(['room_id' => $room_id]);

        $query->orderBy('id ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
}
