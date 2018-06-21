<?php

namespace common\models\car;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "car_booking".
 *
 * @property int $id
 * @property int $reqId
 * @property string $title
 * @property string $travel_date
 * @property string $travel_time
 * @property string $return_date
 * @property string $return_time
 * @property string $pickup_at
 * @property string $pickup_time
 * @property string $place
 * @property string $amphoe
 * @property string $province
 * @property string $note
 * @property int $car_id
 * @property int $driver_id
 * @property string $other_request
 * @property string $create_by
 * @property string $created
 * @property string $modified
 */
class CarBooking extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    var $driver;

    public static function tableName() {
        return 'car_booking';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'travel_date', 'travel_time', 'return_date', 'return_time', 'car_id', 'driver_id', 'kms'], 'required'],
            [['reqId', 'car_id'], 'integer'],
            [['travel_date', 'travel_time', 'return_date', 'return_time', 'pickup_time', 'created', 'modified'], 'safe'],
            [['note'], 'string'],
            [['title'], 'string', 'max' => 500],
            [['pickup_at', 'place', 'other_request', 'driver_id'], 'string', 'max' => 200],
            [['amphoe', 'province'], 'string', 'max' => 100],
            [['create_by', 'kms'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'reqId' => 'Req ID',
            'title' => 'ใช้เพื่อ',
            'travel_date' => 'วันที่',
            'travel_time' => 'เวลา',
            'return_date' => 'กลับวันที่',
            'return_time' => 'เวลา',
            'pickup_at' => 'รับที่',
            'pickup_time' => 'เวลา',
            'place' => 'สถานที่',
            'amphoe' => 'อำเภอ',
            'province' => 'จังหวัด',
            'kms' => 'ระยะทาง',
            'note' => 'การนัดหมาย',
            'car_id' => 'รถยนต์',
            'driver_id' => 'พนักงาน',
            'other_request' => 'Other Request',
            'create_by' => 'Create By',
            'created' => 'Created',
            'modified' => 'Modified',
            'driver' => 'พนักงาน'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->create_by = Yii::$app->user->identity->citizen_id;
                $this->created = $now;
                $this->modified = $now;
            } else {
                $this->modified = $now;
            }
            return true;
        }
        return false;
    }

    public function search() {
        $query = $this->find();
        $query->where(['parent' => 0]);
        $query->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        //var_dump($query->createCommand()->rawSql);

        return $dataProvider;
    }

    public function searchBook() {
        //ค้นหารายการจัดรถยนต์ที่อยู่ในวันเดียวกัน
        $reqId = Yii::$app->request->get('reqId');
        $req = CarRequestcar::findOne($reqId);

        $query = $this->find();
        $query->where(['parent' => 0]);
        $query->andWhere(['OR', ['BETWEEN', 'travel_date', $req->travel_date, $req->return_date], ['BETWEEN', 'return_date', $req->travel_date, $req->return_date]]);
        $query->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        //var_dump($query->createCommand()->rawSql);

        return $dataProvider;
    }

    public function getScheduler($carid, $startDate, $endDate) {
        $query = CarBooking::find();
        $query->select(['id', 'title', 'travel_date', 'travel_time', 'return_date', 'return_time']);
        $query->where(['car_id' => $carid]);
        $query->andWhere(['AND', ['<=', 'travel_date', $endDate], ['>=', 'return_date', $startDate]]);

        return $query->all();
    }

}
