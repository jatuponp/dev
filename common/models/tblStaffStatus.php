<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_status".
 *
 * @property integer $status_id
 * @property string $title
 *
 * @property StaffHistory[] $staffHistories
 */
class tblStaffStatus extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_status';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'status_id' => 'Status ID',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffHistories() {
        return $this->hasMany(StaffHistory::className(), ['status_id' => 'status_id']);
    }

    public static function makeDD($all = false) {

        //$results = amsProject::findAll(["acadyear" => $acadyear]);
        $results = tblStaffStatus::find()->all();

        $data = array();

        ($all)? $data[0] = "สถานะบุคลากรทั้งหมด":"";

//        $data[0] = "ไม่กำหนด";

        foreach ($results as $value) {

            $data[$value->status_id] = $value->title;
        }

        return $data;
    }

}
