<?php

namespace common\models\manpower;

use Yii;

/**
 * This is the model class for table "mpw_number".
 *
 * @property integer $mpw_id
 * @property string $position_no
 * @property integer $dept_id
 * @property integer $position_id
 * @property integer $position_group_id
 * @property integer $position_type_id
 * @property integer $position_subtype_id
 * @property string $program
 * @property string $status
 * @property string $citizen_id
 */
class mpwNumber extends \yii\db\ActiveRecord {

    public $search, $dept, $sstatus;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mpw_number';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dept_id', 'position_id', 'position_group_id', 'position_type_id', 'position_subtype_id'], 'required'],
            [['dept_id', 'position_id', 'position_group_id', 'position_type_id', 'position_subtype_id'], 'integer'],
            [['position_no'], 'string', 'max' => 6],
            [['program'], 'string', 'max' => 150],
            [['status'], 'string', 'max' => 1],
            [['citizen_id'], 'string', 'max' => 13]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mpw_id' => 'Mpw ID',
            'position_no' => 'เลขอัตรา',
            'dept_id' => 'คณะ / หน่วยงาน',
            'position_id' => 'ตำแหน่ง',
            'position_group_id' => 'สาย',
            'position_type_id' => 'ประเภทตำแหน่ง',
            'position_subtype_id' => 'แหล่งงบประมาณ',
            'program' => 'สาขาที่เปิดรับ',
            'status' => 'สถานะ',
        ];
    }

    public function getDepart() {
        return $this->hasOne(\common\models\tblDepartment::className(), ['id' => 'dept_id']);
    }

    public function getDeptName() {
        return $this->depart->title;
    }

    public function getPosition() {
        return $this->hasOne(\common\models\tblPosition::className(), ['position_id' => 'position_id']);
    }

    public function getPositionName() {
        return $this->position->position;
    }

    public function getPositiongroup() {
        return $this->hasOne(\common\models\tblPositionGroup::className(), ['position_group_id' => 'position_group_id']);
    }

    public function getPositionGroupName() {
        return $this->positiongroup->position_group;
    }

    public function getPositiontype() {
        return $this->hasOne(\common\models\tblPositionType::className(), ['position_type_id' => 'position_type_id']);
    }

    public function getPositionTypeName() {
        return $this->positiontype->position_type;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->modifiedby = \Yii::$app->user->identity->citizen_id;
            return true;
        }
        return false;
    }

    public function lists($search = null, $dept = NULL, $sstatus = NULL) {

        $query = mpwNumber::find()
                ->joinWith("depart")
                ->joinWith("position")
                ->joinWith("positiontype")
                ->andFilterWhere(["position_no" => $search])
                ->orFilterWhere(["like", "position", $search]);
        
        if ($dept > 0) {
            $query->andFilterWhere(["tbl_department.id" => $dept])
                    ->orFilterWhere(["tbl_department.parent_id" => $dept]);
        }

        $query->andFilterWhere(["mpw_number.status" => $sstatus]);
                
        return $query;

    }

    public static function makeDD($empty = false) {

        $results = mpwNumber::find()
                ->joinWith("depart")
                ->joinWith("position");

        if ($empty) {
            $results->andWhere(["status" => 0]);
        }

        $query = $results->orderBy(["dept_id" => SORT_ASC])->all();

        $data = array();
        foreach ($query as $value) {

            $title = " {$value->dept["title"]} ";
            $title .= (!empty($value->program)) ? "สาขา{$value->program}" : "{$value->position->position}";
            $title .= ($value->position_no == "") ? " []" : " [{$value->position_no}]";
            $data[$value->mpw_id] = $title;
        }

        return $data;
    }
    
    public static function getSitman($mpw_id) {
        
        $query = NEW \yii\db\Query();
        $query->select(["CONCAT(prefixname, first_thname, '   ', last_thname) AS fullname"])
                ->from("tbl_staff_position")
                ->innerJoin("tbl_staff", "tbl_staff.citizen_id = tbl_staff_position.citizen_id")
                ->innerJoin("std_prefix", "std_prefix.prefixid = tbl_staff.prefixid")
                ->where(["mpw_id" => $mpw_id]);
        
        $result = $query->createCommand()->queryOne();
        return $result["fullname"];
        
    }

}
