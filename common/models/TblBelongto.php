<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_belongto".
 *
 * @property integer $id
 * @property string $citizen_id
 * @property string $staff_id
 * @property integer $depart_id
 *
 * @property Department $depart
 * @property Staff $citizen
 */
class TblBelongto extends \yii\db\ActiveRecord {

    public $dept, $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_belongto';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['citizen_id', 'depart_id'], 'required'],
//            [['depart_id'], 'integer'],
//            [['citizen_id'], 'string', 'max' => 13]


            [['citizen_id', 'staff_id', 'depart_id'], 'required'],
            [['depart_id'], 'integer'],
            [['citizen_id'], 'string', 'max' => 13],
            [['staff_id'], 'string', 'max' => 8],
//            [['citizen_id'], 'unique', 'targetAttribute' => ['citizen_id', 'staff_id', 'depart_id'], 'message' => 'ข้อมูลซ้ำ citizen_id, staff_id, depart_id'],
//            [['depart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['depart_id' => 'id']],
//            [['citizen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::className(), 'targetAttribute' => ['citizen_id' => 'citizen_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'fullname' => 'ชื่อ - นามสกุล',
            'citizen_id' => 'ชื่อ - นามสกุล',
            'depart_id' => 'คณะ / หน่วยงาน',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepart() {
        return $this->hasOne(tblDepartment::className(), ['id' => 'depart_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition() {
        return $this->hasOne(tblStaffPosition::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosit() {
        return $this->hasOne(tblPosition::className(), ['position_id' => 'position_id'])
                        ->via("position");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff() {
        return $this->hasOne(Staff::className(), ['citizen_id' => 'citizen_id'])
                        ->via("position");
    }

    public function getPrefix() {
        return $this->hasOne(stdPrefix::className(), ['prefixid' => 'prefixid'])
                        ->via("staff");
    }

    public function getFullname() {
        return $this->prefix->prefixname . $this->staff->first_thname . ' ' . $this->staff->last_thname;
    }

    public function getLaststatus() {
        return $this->hasMany(person\vwLastdatestatusStaff::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id']);
    }

    public function getHistory() {
        return $this->hasOne(tblStaffHistory::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id', 'status_date' => 'lastupdate'])
                        ->via("laststatus");
    }

//    public function beforeSave($insert) {
//        if (parent::beforeSave($insert)) {
//            
//            //$staff_id = tblStaff::get
//            $this->staff_id = "xx";
//            //$this->modifiedby = \Yii::$app->user->identity->citizen_id;
//            return true;
//        }
//        return false;
//    }


    public static function lists($search = NULL, $dept = NULL) {

        $query = TblBelongto::find()
                ->joinWith("staff")
                ->joinWith('history')
                ->joinWith("depart")
                ->where(["tbl_staff_history.status_id" => [1, 4, 12, 16, 17]])
                ->andFilterWhere([
            'or',
            ["tbl_staff.citizen_id" => $search],
            ["like", "tbl_staff.first_thname", $search],
            ["like", "tbl_staff.last_thname", $search]
        ]);
//                ->orFilterWhere(["like", "tbl_staff.first_thname", $search])
//                ->orFilterWhere(["like", "tbl_staff.last_thname", $search]);

        if ($dept > 0) {
            $query->andFilterWhere([
                'or',
                ["tbl_department.id" => $dept],
                ["tbl_department.parent_id" => $dept]
            ]);
//                    ->orFilterWhere(["tbl_department.parent_id" => $dept]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }
    
    public static function getDept() {
        $staff_id = Staff::getStaffID();
        
        return self::find()->where(['staff_id' => $staff_id])->one()->depart_id;
    }

}
