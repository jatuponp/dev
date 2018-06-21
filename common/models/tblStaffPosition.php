<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_staff_position".
 *
 * @property string $citizen_id
 * @property integer $position_id
 * @property integer $position_group_id
 * @property integer $position_type_id
 * @property integer $position_subtype_id
 * @property string $date_work
 * @property string $postion_no
 * @property string $staff_id
 * @property string $modifiedby
 * @property string $modifieddate
 *
 * @property PositionGroup $positionGroup
 * @property Staff $citizen
 * @property Position $position
 * @property PositionType $positionType
 * @property PositionSubtype $positionSubtype
 */
class tblStaffPosition extends \yii\db\ActiveRecord {

    public $dept, $search, $sstatus, $positiongroup, $search_year, $search_day, $search_faculty;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_position';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['citizen_id', 'position_id', 'position_group_id', 'position_type_id', 'position_subtype_id', 'date_work', 'staff_id','search_day'], 'required'],
            
            [['position_id', 'position_group_id', 'position_type_id', 'position_subtype_id', 'mpw_id'], 'integer'],
            [['date_work', 'modifieddate'], 'safe'],
            [['citizen_id', 'modifiedby'], 'string', 'max' => 13],
            [['staff_id'], 'string', 'max' => 8],
            [['staff_id'], 'unique'],
            
//            [['citizen_id', 'position_id', 'position_group_id', 'position_type_id', 'position_subtype_id', 'date_work', 'mpw_id', 'staff_id','search_day'], 'required'],
//            
//            [['citizen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::className(), 'targetAttribute' => ['citizen_id' => 'citizen_id']],
//            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'position_id']],
//            [['position_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PositionType::className(), 'targetAttribute' => ['position_type_id' => 'position_type_id']],
//            [['position_subtype_id'], 'exist', 'skipOnError' => true, 'targetClass' => PositionSubtype::className(), 'targetAttribute' => ['position_subtype_id' => 'position_subtype_id']],
//            [['position_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => PositionGroup::className(), 'targetAttribute' => ['position_group_id' => 'position_group_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'citizen_id' => 'เลขบัตร ปชช.',
            'position_id' => 'ตำแหน่ง',
            'position_group_id' => 'สายตำแหน่ง',
            'position_type_id' => 'ประเภท',
            'position_subtype_id' => 'แหล่งเงินจ้าง',
            'date_work' => 'วันที่บรรจุ',
            'mpw_id' => 'อัตรากำลัง',
            'staff_id' => 'รหัสประจำตัวพนักงาน',
            'modifiedby' => 'Modifiedby',
            'modifieddate' => 'Modifieddate',
            'staffname' => 'ชื่อ - นามสกุล',
        ];
    }

    public function scenarios() {
        return [
            'position' => ['position_id'],
            'default' => ['citizen_id', 'position_id', 'position_group_id', 'position_type_id', 'position_subtype_id', 'date_work', 'mpw_id', 'staff_id'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff() {
        return $this->hasOne(Staff::className(), ['citizen_id' => 'citizen_id']);
    }

    public function getFullname() {

        return $this->prefix->prefixname . $this->staff->first_thname . "&nbsp;&nbsp;&nbsp;" . $this->staff->last_thname;
    }

    public function getPrefix() {
        return $this->hasOne(stdPrefix::className(), ['prefixid' => 'prefixid'])
                        ->via('staff');
    }

    public function getPosit() {
        return $this->hasOne(tblPosition::className(), ['position_id' => 'position_id']);
    }

    public function getPositgroup() {
        return $this->hasOne(tblPositionGroup::className(), ['position_group_id' => 'position_group_id']);
    }

    public function getLaststatus() {
        return $this->hasMany(person\vwLastdatestatusStaff::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id']);
    }

    public function getHistory() {
        return $this->hasOne(tblStaffHistory::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id', 'status_date' => 'lastupdate'])
                        ->via("laststatus");
    }

//    public function getStaffstatus() {
//        return $this->hasOne(tblStaffStatus::className(), ['status_id' => 'status_id'])
//                        ->via("history");
//    }

    public function getBelongto() {
        return $this->hasOne(TblBelongto::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id']);
    }

    public function getDepart() {
        return $this->hasOne(tblDepartment::className(), ['id' => 'depart_id'])
                        ->via("belongto");
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->modifiedby = \Yii::$app->user->identity->citizen_id;
            return true;
        }
        return false;
    }

    public static function lists($search = NULL, $dept = NULL, $sstatus = NULL, $positiongroup = NULL) {
        //$positiongroup = 2;
        $query = tblStaffPosition::find()
                ->select(["*", "tbl_staff.*", "tbl_staff_position.staff_id"])
                ->joinWith("staff", true, "RIGHT JOIN")
                ->joinWith("history")
//                ->joinWith("laststatus")
//                ->joinWith("staffstatus")
                ->joinWith("depart")
//                ->where(["tbl_staff_position.staff_id" => NULL])
                ->andFilterWhere(['or',
            ["tbl_staff.citizen_id" => $search],
            ["like", "tbl_staff.first_thname", $search],
            ["like", "tbl_staff.last_thname", $search]
        ]);

        if ($sstatus > 0) {
            $query->andFilterWhere(["tbl_staff_history.status_id" => $sstatus]);
        }

        if ($dept > 0) {
            $query->andFilterWhere([
                'or',
                ["tbl_department.id" => $dept],
                ["tbl_department.parent_id" => $dept]
            ]);
        }

        if ($positiongroup > 0) {
            $query->andFilterWhere(["tbl_staff_position.position_group_id" => $positiongroup]);
        }


//        die($query->createCommand()->rawSql);
//        print_r($query->with("staff")->one());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

    public static function countAll($dept, $positiongroup = NULL, $positiontype = NULL, $search_day = NULL) {

        $query = tblStaffPosition::find()
                ->joinWith("history")
                ->joinWith("depart")
                ->where(["tbl_staff_history.status_id" => [1, 4, 12, 16, 17]])
                ->andFilterWhere([
                    'or',
                    ["tbl_department.id" => $dept],
                    ["tbl_department.parent_id" => $dept]
                ])
                ->andFilterWhere(["tbl_staff_position.position_group_id" => $positiongroup])
                ->andFilterWhere(["tbl_staff_position.position_type_id" => $positiontype])
                ->andFilterWhere(["<=", "tbl_staff_position.date_work", $search_day]);
                //->andFilterWhere([">", "vw_lastdatestatus_staff.lastupdate", $search_day]);
//                ->andWhere("date_format(tbl_staff_position.date_work, '%Y') <= '2015'");


        $empty = tblStaffPosition::countEmpty($dept, $positiongroup, $positiontype);
        return $query->count() + $empty;
    }

    public static function countReal($dept, $positiongroup = NULL, $positiontype = NULL, $search_day = NULL) {

        $work = tblStaffPosition::find()
                ->joinWith("history")
                ->joinWith("depart")
                ->where(["tbl_staff_history.status_id" => [1]])
                ->andFilterWhere([
                    'or',
                    ["tbl_department.id" => $dept],
                    ["tbl_department.parent_id" => $dept]
                ])
                ->andFilterWhere(["tbl_staff_position.position_group_id" => $positiongroup])
                ->andFilterWhere(["tbl_staff_position.position_type_id" => $positiontype])
                ->andFilterWhere(["<=", "tbl_staff_position.date_work", $search_day]);
        return $work->count();
    }

    public static function countEmpty($dept, $positiongroup = NULL, $positiontype = NULL) {

        $empty = NEW \yii\db\Query();
        $empty->from("mpw_number")
                ->leftJoin("tbl_staff_position", "mpw_number.mpw_id = tbl_staff_position.mpw_id")
                ->where([
                    "tbl_staff_position.mpw_id" => NULL
                ])
                ->andFilterWhere(["dept_id" => $dept])
                ->andFilterWhere(["mpw_number.position_group_id" => $positiongroup])
                ->andFilterWhere(["mpw_number.position_type_id" => $positiontype]);

        return $empty->count();
    }

    public static function countLA($dept, $positiongroup = NULL, $positiontype = NULL, $search_day = NULL) {

        $la = tblStaffPosition::find()
                ->joinWith("history")
                ->joinWith("depart")
                ->where(["tbl_staff_history.status_id" => [4, 12, 16, 17]])
                ->andFilterWhere([
                    'or',
                    ["tbl_department.id" => $dept],
                    ["tbl_department.parent_id" => $dept]
                ])
                ->andFilterWhere(["tbl_staff_position.position_group_id" => $positiongroup])
                ->andFilterWhere(["tbl_staff_position.position_type_id" => $positiontype])
                ->andFilterWhere(["<=", "tbl_staff_position.date_work", $search_day]);;

        return $la->count();
    }

}
