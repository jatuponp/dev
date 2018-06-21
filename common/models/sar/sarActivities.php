<?php

namespace common\models\sar;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "sar_activities".
 *
 * @property integer $act_id
 * @property string $title
 * @property integer $dept_id
 * @property string $fiscal_year
 * @property integer $type_id
 * @property string $date_from
 * @property string $date_to
 * @property integer $target
 * @property integer $intent
 * @property integer $actual
 * @property string $place
 * @property string $owner
 * @property string $note
 * @property integer $status
 *
 * @property SarType $type
 * @property SarJoin[] $sarJoins
 * @property StaffPosition[] $staff
 */
class sarActivities extends \yii\db\ActiveRecord {

    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sar_activities';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'dept_id', 'fiscal_year', 'type_id', 'date_from', 'date_to', 'place'], 'required'],
            [['dept_id', 'type_id', 'target', 'intent', 'actual', 'status'], 'integer'],
            [['date_from', 'date_to'], 'safe'],
            [['note'], 'string'],
            [['title', 'place'], 'string', 'max' => 255],
            [['fiscal_year'], 'string', 'max' => 4],
            [['owner'], 'string', 'max' => 80],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => sarType::className(), 'targetAttribute' => ['type_id' => 'type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'act_id' => 'Act ID',
            'title' => 'กิจกรรม / โครงการ',
            'dept_id' => 'คณะ / หน่วยงาน',
            'fiscal_year' => 'ปีงบประมาณ',
            'type_id' => 'ประเภท',
            'date_from' => 'วัน เวลาที่เริ่ม',
            'date_to' => 'วัน เวลาที่สิ้นสุด',
            'target' => 'จำนวนเป้าหมาย',
            'intent' => 'จำนวนยื่นความประสงค์',
            'actual' => 'จำนวนเข้าร่วมจริง',
            'place' => 'สถานที่',
            'owner' => 'ผู้รับผิดชอบโครงการ',
            'note' => 'หมายเหตุ',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType() {
        return $this->hasOne(sarType::className(), ['type_id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDept() {
        return $this->hasOne(\common\models\tblDepartment::className(), ['id' => 'dept_id']);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getSarJoins() {
//        return $this->hasMany(SarJoin::className(), ['act_it' => 'act_id']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getStaff() {
//        return $this->hasMany(StaffPosition::className(), ['staff_id' => 'staff_id'])->viaTable('sar_join', ['act_it' => 'act_id']);
//    }



    public static function lists($search = NULL, $lastest = false) {

        $query = sarActivities::find()
                ->andFilterWhere([
            'or',
            ["like", "title", $search],
            ["like", "place", $search]
        ]);

        if ($lastest) {
            $today = date('Y-m-d');

            $query->andWhere(['>=', 'date_from', $today]);

            $query->orderBy('date_from asc');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => ($lastest) ? 5 : 25,
            ],
        ]);

        return $dataProvider;
    }

}
