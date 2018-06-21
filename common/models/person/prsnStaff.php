<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\person;

use common\models\Staff;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Description of prsnStaff
 *
 * @author bigkobz
 */
class prsnStaff extends Staff {

    public $dept, $search, $sstatus, $positiongroup;

    public function prsnLists($search, $dept = NULL, $sstatus = NULL, $positiongroup = NULL) {
        $query = NEW Query();
        $query->select([
                    "CONCAT(prefixname,first_thname, ' ', last_thname) AS fullname",
                    "tbl_staff.citizen_id", "tbl_staff_position.staff_id", "position", "date_work",
                    "position_type", "mpw_number.position_no", "tbl_staff_status.title"
                ])
                ->from("tbl_staff")
                ->innerJoin("std_prefix", "std_prefix.prefixid = tbl_staff.prefixid")
                ->leftJoin("tbl_staff_position", "tbl_staff.citizen_id = tbl_staff_position.citizen_id")
                ->leftJoin("tbl_position", "tbl_staff_position.position_id = tbl_position.position_id")
                ->leftJoin("tbl_position_type", "tbl_staff_position.position_type_id = tbl_position_type.position_type_id")
                ->leftJoin("mpw_number", "tbl_staff_position.mpw_id = mpw_number.mpw_id")
                ->leftJoin("vw_lastdatestatus_staff", "tbl_staff_position.citizen_id = vw_lastdatestatus_staff.citizen_id and tbl_staff_position.staff_id = vw_lastdatestatus_staff.staff_id")
                ->leftJoin("tbl_staff_history", "vw_lastdatestatus_staff.citizen_id = tbl_staff_history.citizen_id and vw_lastdatestatus_staff.staff_id = tbl_staff_history.staff_id and lastupdate = status_date")
                ->leftJoin("tbl_belongto", "tbl_staff_position.citizen_id = tbl_belongto.citizen_id AND tbl_staff_position.staff_id = tbl_belongto.staff_id")
                ->leftJoin("tbl_department", "tbl_belongto.depart_id = tbl_department.id")
                ->leftJoin("tbl_staff_status", "tbl_staff_history.status_id = tbl_staff_status.status_id")
                ->andFilterWhere(["tbl_staff.citizen_id" => $search])
                ->orFilterWhere(["like", "first_thname", $search])
                ->orFilterWhere(["like", "last_thname", $search])
                ->orderBy([
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

        return $dataProvider;
        //return $query->createCommand()->rawSql;
        //return $query->all();
    }

}
