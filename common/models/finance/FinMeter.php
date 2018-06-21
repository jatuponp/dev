<?php

namespace common\models\finance;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\flat\FlatBooking;
use common\models\Staff;

/**
 * This is the model class for table "fin_meter".
 *
 * @property integer $id
 * @property string $building
 * @property string $room_id
 * @property integer $month
 * @property integer $year
 * @property integer $meter_type
 * @property string $meter_start
 * @property string $meter_end
 * @property string $meter_date
 * @property string $meter_user
 * @property integer $ispay
 * @property string $pay_date
 * @property string $pay_billno
 * @property string $pay_amount
 * @property string $pay_user
 * @property integer $app
 */
class FinMeter extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $mth;
    public $yrs;
    public $mobile;
    public $message;
    public $search;

    public static function tableName() {
        return 'fin_meter';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['room_id', 'month'], 'required'],
            [['building'], 'string'],
            [['month', 'year', 'ispay', 'app'], 'integer'],
            [['meter_start', 'meter_end', 'pay_amount'], 'number'],
            [['meter_date', 'pay_date'], 'safe'],
            [['room_id'], 'string', 'max' => 100],
            [['meter_user', 'pay_user'], 'string', 'max' => 15],
            [['pay_billno'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'building' => 'Building',
            'room_id' => 'Room ID',
            'month' => 'Month',
            'year' => 'Year',
            'meter_type' => 'Meter Type',
            'meter_start' => 'Meter Start',
            'meter_end' => 'Meter End',
            'meter_date' => 'Meter Date',
            'meter_user' => 'Meter User',
            'ispay' => 'Ispay',
            'pay_date' => 'Pay Date',
            'pay_billno' => 'Pay Billno',
            'pay_amount' => 'Pay Amount',
            'pay_user' => 'Pay User',
            'app' => 'App',
            'mobile' => 'เบอร์โทร',
            'message' => 'ข้อความ',
            'search' => 'ค้นหาเลขห้อง'
        ];
    }

    public function scenarios() {
        return [
            'default' => ['id', 'building', 'month', 'year', 'room_id', 'meter_type', 'meter_start', 'meter_end', 'pay_type', 'meter_date', 'meter_user'],
            'payment' => ['ispay', 'pay_date', 'pay_billno', 'pay_amount', 'pay_user'],
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                //$this->meter_user = Yii::$app->user->identity->citizen_id;
                if ($this->meter_type == 'power') {
                    $this->meter_rate = 3.423; //อัตราค่าไฟฟ้า
                } else {
                    $this->meter_rate = 11.375; //อัตราค่าน้ำ
                }
                $this->meter_date = $now;
                //$this->year = Yii::$app->getModule('finance')->year;
                $this->meter_start = $this->getLastMeter($this->room_id, $this->month, $this->year, $this->meter_type, $this->meter_end, $this->building);
            }
            return true;
        }
        return false;
    }

    public function search($room_id, $building) {
        $query = FinMeter::find()
                ->where(['building' => $building, 'room_id' => $room_id, 'month' => $this->month, 'year' => $this->year])
                ->orderBy('month ASC')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    public function searchByYear($room_id, $building) {
        $query = FinMeter::find()
                ->where(['building' => $building, 'room_id' => $room_id, 'year' => $this->year])
                ->orderBy('month ASC')
        ;
        //echo $query->createCommand()->getRawSql();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    public function getLastMeter($room_id, $month, $year, $type, $meter_end, $building) {
        $lastMonth = date("n", mktime(0, 0, 0, $month - 1, 15, ($year - 543)));
        $lastYear = date("Y", mktime(0, 0, 0, $month - 1, 15, ($year - 543)));
        $model = FinMeter::find()->where(['room_id' => $room_id, 'month' => $lastMonth, 'year' => ($lastYear + 543), 'meter_type' => $type, 'building' => $building])->one();
        if ($model->meter_end) {
            $lastMeter = $model->meter_end;
        } else {
            $lastMeter = $meter_end;
        }
        return $lastMeter;
    }

    public function amountTotal($provider) {
        $total = 0;

        foreach ($provider as $model) {
            $mCount = $model['meter_end'] - $model['meter_start'];
            if ($model['building'] == 'store' || $model['building'] == 'flat') {
                if ($model['meter_type'] == 'power') {
                    $amount = ($mCount * $model['meter_rate']) + (($mCount * $model['meter_rate']) * 0.07);
                } else if ($model['meter_type'] == 'water') {
                    $amount = ($mCount * $model['meter_rate']) + (($mCount * $model['meter_rate']) * 0.07);
                } else if ($model['meter_type'] == 'rental') {
                    $amount = $model['meter_start'];
                }
            } else {
                if ($model['meter_type'] == 'power') {
                    if ($mCount <= 20) {
                        $amount = 0;
                    } else {
                        $amount = (($mCount - 20) * $model['meter_rate']) + ((($mCount - 20) * $model['meter_rate']) * 0.07);
                    }
                } else if ($model['meter_type'] == 'water') {
                    if ($mCount <= 10) {
                        $amount = 0;
                    } else {
                        $amount = (($mCount - 10) * $model['meter_rate']) + ((($mCount - 10) * $model['meter_rate']) * 0.07);
                    }
                } else if ($model['meter_type'] == 'rental') {
                    $amount = $model['meter_start'];
                }
            }

            $total += $amount;
        }
        return number_format(ceil($total), 2);
    }

    public function totoalByMonth($building, $roomid, $month, $year, $type) {
        $model = FinMeter::find()
                ->where(['building' => $building, 'room_id' => $roomid, 'month' => $month, 'year' => $year, 'meter_type' => $type])
                ->one()
        ;
        $mCount = $model->meter_end - $model->meter_start;
        if ($model->building == 'store' || $model->building == 'flat') {
            if ($model->meter_type == 'power') {
                $amount = ($mCount * $model->meter_rate) + (($mCount * $model->meter_rate) * 0.07);
            } else if ($model->meter_type == 'water') {
                $amount = ($mCount * $model->meter_rate) + (($mCount * $model->meter_rate) * 0.07);
            } else if ($model->meter_type == 'rental') {
                $amount = $model->meter_start;
            }
        } else {
            if ($model->meter_type == 'power') {
                if ($mCount <= 20) {
                    $amount = 0;
                } else {
                    $amount = (($mCount - 20) * $model->meter_rate) + ((($mCount - 20) * $model->meter_rate) * 0.07);
                }
            } else if ($model->meter_type == 'water') {
                if ($mCount <= 10) {
                    $amount = 0;
                } else {
                    $amount = (($mCount - 10) * $model->meter_rate) + ((($mCount - 10) * $model->meter_rate) * 0.07);
                }
            } else if ($model->meter_type == 'rental') {
                $amount = $model->meter_start;
            }
        }
        return $amount;
    }

    public function getBillNo($building, $roomid, $month, $year, $type) {
        $model = FinMeter::find()
                ->where(['building' => $building, 'room_id' => $roomid, 'month' => $month, 'year' => $year, 'meter_type' => $type])
                ->one()
        ;
        return $model->pay_billno;
    }

    public function getFlatroom() {
        return $this->hasOne(\common\models\flat\FlatRoom::className(), ['id' => 'room_id']);
    }

    public function overdue($building, $search = null) {

        $query = FinMeter::find()
                ->select('fin_meter.*, flat_room.room_no')
                ->joinWith('flatroom');
        if ($building == 'chingchan') {
            $query->where('`fin_meter`.`meter_end` - `fin_meter`.`meter_start` > 20');
        } else {
            $query->where('`fin_meter`.`meter_end` - `fin_meter`.`meter_start` > 0');
        }
        if ($search) {
            $query->andWhere(['LIKE', '`flat_room`.`room_no`', $search]);
        }
        $query->andWhere(['`fin_meter`.`building`' => $building, '`fin_meter`.`ispay`' => 0])
                ->groupBy('`fin_meter`.`building`,`fin_meter`.`room_id`')
                ->orderBy('`flat_room`.`room_no` ASC')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

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

    public function listMonthsOver($building, $room_id, $list = false) {
        $query = FinMeter::find()->where(['building' => $building, 'room_id' => $room_id, 'ispay' => 0])
                ->groupBy('month, year')
                ->orderBy('year DESC, month DESC')
                ->all();
        $html = '';
        foreach ($query as $r) {
            $power = FinMeter::totoalByMonth($r->building, $room_id, $r->month, $r->year, 'power');
            $water = FinMeter::totoalByMonth($r->building, $room_id, $r->month, $r->year, 'water');
            $amount = ceil($power + $water);
            if ($amount > 0) {
                $html .= $r->month . '/' . $r->year . '(' . number_format($amount, 2) . ' บาท) <br/> ';
                $amt += $amount;
            }
        }
        if ($list) {
            return $amt;
        } else {
            return $html; //substr($html, 0, -2);
        }
    }

}
