<?php

namespace common\models\flat;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\flat\FlatBooking;
use common\models\Staff;

/**
 * This is the model class for table "flat_room".
 *
 * @property string $id
 * @property string $building
 * @property integer $floor
 * @property string $room_no
 * @property integer $capacity
 * @property string $room_kind
 * @property string $room_type
 * @property integer $room_status
 */
class FlatRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $search;
    public $month;
    public $year;


    public static function tableName()
    {
        return 'flat_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'building', 'room_no', 'capacity', 'room_kind', 'room_type', 'room_status'], 'required'],
            [['building', 'room_kind', 'room_type'], 'string'],
            [['floor', 'capacity', 'room_status'], 'integer'],
            [['id'], 'string', 'max' => 100],
            [['room_id'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'building' => 'Building',
            'floor' => 'Floor',
            'room_no' => 'Room ID',
            'capacity' => 'Capacity',
            'room_kind' => 'Room Kind',
            'room_type' => 'Room Type',
            'room_status' => 'Room Status',
        ];
    }
    
    public function search() {
        $query = $this->find();

        if ($this->floor) {
            $query->where(['floor' => $this->floor]);
        }

        if ($this->building) {
            $query->andWhere(['building' => $this->building]);
        }

        if ($this->search) {
            $query->andWhere(['LIKE', 'room_no', $this->search]);
        }

        $query->orderBy('room_no ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);
        //var_dump($query->createCommand()->rawSql);

        return $dataProvider;
    }
    
    public function listGuest($id) {
        $booking = FlatBooking::find()->where(['room_id' => $id])
                ->andWhere("status != 'cancel'")
                ->all();
        $i = 1;
        $html = '<table width="100%">';
        foreach ($booking as $b) {            
            $html .= '<tr><td>';
            $html .= Staff::getStaffNameById($b->citizen_id);

            $html .= '</td></tr>';
            $i++;
        }
        $html .= '</table>';
        return $html;
    }
}
