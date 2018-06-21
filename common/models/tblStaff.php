<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use common\models\Staff;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class tblStaff extends Staff {

//    public function getStaffposit() {
//        return $this->hasMany(tblStaffPosition::className(), ['citizen_id' => 'citizen_id']);
//    }
//
//    public function getPosit() {
//        return $this->hasOne(tblPosition::className(), ['position_id' => 'position_id'])
//                        ->via("staffposit");
//    }
//
//    public function getPositionname() {
//        return $this->posit->position;
//    }
//
//    public function getPositgroup() {
//        return $this->hasOne(tblPositionGroup::className(), ['position_group_id' => 'position_group_id'])
//                        ->via("staffposit");
//    }
//    public function getPositgroupname() {
//        return $this->positgroup->position_group;
//    }
//    public function getLaststatus() {
//        return $this->hasOne(vwLastdatestatusStaff::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id']);
//    }
//
//    public function getHistory() {
//        return $this->hasOne(tblStaffHistory::className(), ['citizen_id' => 'citizen_id', 'staff_id' => 'staff_id', 'status_date' => 'lastupdate'])
//                        ->via("laststatus");
//    }
//
//    public function getStaffstatus() {
//        return $this->hasOne(tblStaffStatus::className(), ['status_id' => 'status_id'])
//                        ->via("history");
//    }
//
//    public function getStatus() {
//        return $this->staffstatus->title;
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getPositiontype() {
//        return $this->hasOne(tblPositionType::className(), ['position_type_id' => 'position_type_id'])
//                        ->via("staffpost");
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getPositionsubtype() {
//        return $this->hasOne(tblPositionSubtype::className(), ['position_subtype_id' => 'position_subtype_id'])
//                        ->via("staffpost");
//    }
//    

    public $dept, $sstatus, $positiongroup;

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->update_by = \Yii::$app->user->identity->citizen_id;

            return true;
        }
        return false;
    }

    public function lists($dept = NULL, $sstatus = NULL, $positiongroup = NULL, $dataProvi = true) {

        $query = NEW Query();
        $query->select([
                    "CONCAT(prefixname,first_thname, ' ', last_thname) AS fullname",
                    "tbl_staff.citizen_id", "tbl_staff_position.staff_id", "tbl_position.position", "date_work",
                    "mpw_number.position_no", "tbl_staff_status.title",
                    "program", "tbl_department.title AS deptname", "tbl_staff_position.position_group_id",
                    "position_type", "position_subtype", "tbl_position_type.position_type_id", "tbl_position_subtype.position_subtype_id"
                ])
                ->from("tbl_staff")
                ->innerJoin("std_prefix", "std_prefix.prefixid = tbl_staff.prefixid")
                ->leftJoin("tbl_staff_position", "tbl_staff.citizen_id = tbl_staff_position.citizen_id")
                ->leftJoin("tbl_position", "tbl_staff_position.position_id = tbl_position.position_id")
                ->leftJoin("tbl_position_type", "tbl_staff_position.position_type_id = tbl_position_type.position_type_id")
                ->leftJoin("tbl_position_subtype", "tbl_staff_position.position_subtype_id = tbl_position_subtype.position_subtype_id")
                ->leftJoin("mpw_number", "tbl_staff_position.mpw_id = mpw_number.mpw_id")
                ->leftJoin("vw_lastdatestatus_staff", "tbl_staff_position.citizen_id = vw_lastdatestatus_staff.citizen_id and tbl_staff_position.staff_id = vw_lastdatestatus_staff.staff_id")
                ->leftJoin("tbl_staff_history", "vw_lastdatestatus_staff.citizen_id = tbl_staff_history.citizen_id and vw_lastdatestatus_staff.staff_id = tbl_staff_history.staff_id and lastupdate = status_date")
                ->leftJoin("tbl_belongto", "tbl_staff_position.citizen_id = tbl_belongto.citizen_id AND tbl_staff_position.staff_id = tbl_belongto.staff_id")
                ->leftJoin("tbl_department", "tbl_belongto.depart_id = tbl_department.id")
                ->leftJoin("tbl_staff_status", "tbl_staff_history.status_id = tbl_staff_status.status_id")
                ->orderBy([
                    "tbl_staff_position.position_type_id" => SORT_ASC,
                    "tbl_staff_position.position_subtype_id" => SORT_ASC,
                    "first_thname" => SORT_ASC,
                    "last_thname" => SORT_ASC,
        ]);

        if ($dept > 0) {
            $query->andFilterWhere(["tbl_department.id" => $dept])
                    ->orFilterWhere(["tbl_department.parent_id" => $dept]);
        }
        if ($sstatus > 0) {
            $query->andFilterWhere(["tbl_staff_history.status_id" => $sstatus]);
        }
        if ($positiongroup > 0) {
            $query->andFilterWhere(["tbl_staff_position.position_group_id" => $positiongroup]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        if ($dataProvi) {
            return $dataProvider;
        } else {

            return $query->createCommand()->queryAll();
        }
    }

    public static function getStaffID($citizen_id) {

        $query = tblStaffPosition::find()
                ->where(["citizen_id" => $citizen_id])
                ->orderBy(["date_work" => SORT_DESC])
                ->one();
        return $query->staff_id;
    }

    public static function getLaststatus($citizen_id, $staff_id) {

        $query = NEW Query();
        $query->select(["tbl_staff_status.title"])
                ->from("vw_lastdatestatus_staff")
                ->leftJoin("tbl_staff_history", "vw_lastdatestatus_staff.citizen_id = tbl_staff_history.citizen_id and vw_lastdatestatus_staff.staff_id = tbl_staff_history.staff_id and lastupdate = status_date")
                ->leftJoin("tbl_staff_status", "tbl_staff_history.status_id = tbl_staff_status.status_id")
                ->where([
                    "tbl_staff_history.citizen_id" => $citizen_id,
                    "tbl_staff_history.staff_id" => $staff_id,
        ]);

        $result = $query->createCommand()->queryOne();
//        print_r($result);
//        die();

        return $result["title"];
    }

}
