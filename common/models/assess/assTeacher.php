<?php

namespace common\models\assess;

use yii\db\Query;
use yii\data\ActiveDataProvider;

class assTeacher extends \yii\db\ActiveRecord {

    public $citizen_id,$acadyear, $semester;
    public $coursecode, $coursename, $section;

    public function lists($citizen_id = NULL, $acadyear = NULL, $semester = NULL, $provider = true) {

        $query = NEW Query();

        //$citizen_id = "3460500454371";
        $query->select([ "ass_class.*", "ass_class.classid AS classid", "tbl_course.courseid AS courseid", "tbl_course.coursecode AS coursecode", "coursename", "ass_class.section", "ass_classinstructor.citizen_id AS citizen_id", "ass_class.acadyear AS acadyear", "ass_class.semester AS semester"])
                ->from(["ass_class"])
                ->innerJoin("ass_classinstructor", "ass_class.classid = ass_classinstructor.classid")
                ->innerJoin("tbl_course", "ass_class.courseid = tbl_course.courseid")
                ->where([
                    "ass_classinstructor.citizen_id" => $citizen_id,
                    "ass_class.acadyear" => $acadyear,
                    "ass_class.semester" => $semester
                ])
                ->andWhere("ass_class.classstatus <> 'C' ")
                ->andWhere("ass_class.enrollseat <> 0")
                ->orderBy('coursecode ASC, section ASC');

//        if ($citizen_id) {
//            $query->andWhere(["citizen_id" => $citizen_id]);
//        }
        
//        echo $query->createCommand()->rawSql;
//        exit();

        if ($provider) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

//        $dataProvider=$query->createCommand()->rawSql;

            return $dataProvider;
        } else {
            return $query->createCommand()->queryAll();
        }
    }

    public function listsCourse($acadyear = NULL, $semester = NULL, $courseid = NULL, $coursename = NULL, $section = NULL) {

        $query = NEW Query();

        $query->select(["ass_class.*", "ass_class.classid AS classid", "tbl_course.courseid AS courseid", "tbl_course.coursecode AS coursecode", "coursename", "ass_class.section"])
                ->from(["ass_class"])
                ->innerJoin("tbl_course", "ass_class.courseid = tbl_course.courseid")
                ->where([
                    "ass_class.acadyear" => $acadyear,
                    "ass_class.semester" => $semester
                ])
                ->andWhere("ass_class.classstatus <> 'C' ")
                ->andWhere("ass_class.enrollseat <> 0")
                ->andWhere("tbl_course.coursecode LIKE '%$courseid%' ")
                ->andWhere("tbl_course.coursename LIKE '%$coursename%' ")
                ->orderBy('coursecode ASC, section ASC');

        if ($section) {
            $query->andWhere(["ass_class.section" => $section]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
    
    public function makeDDTeacher($all = FALSE) {
        
        $query = NEW Query();
        $query->select(["citizen_id", "CONCAT(prefixname, first_thname, ' ', last_thname) AS fullname"])
                ->from("tbl_staff")
                ->innerJoin("std_prefix", "tbl_staff.prefixid = std_prefix.prefixid");
//                ->where([
//                    "tbl_staff.position_subtype" => "วิชาการ"
//                ])
//                ->andWhere("status NOT IN ('หมดสัญญาจ้าง', 'เกษียณอายุราชการรับบำเหน็จ', 'ลาออก', 'ให้โอน', 'ยกเลิกการจ้าง', 'เกษียณอายุราชการ', 'ลาศึกษา ณ ต่างประเทศ', 'เลิกจ้าง')");
                 
        $results = $query->createCommand()->queryAll();
        $data = array();
        
        if ($all)
            $data['all'] = "ทั้งหมด";
        foreach ($results as $row) {
             $data[$row["citizen_id"]] = $row["fullname"];
             

        }

        return $data;
    }

    public static function getCountAssess($citizen_id = NULL, $classid = NULL) {

        //"SELECT count(distinct studentcode) from ass_qa where classid = '472910'"
        $query = NEW Query();
        $query->select("count(distinct studentcode)")
                ->from("ass_qa")
                //->innerJoin("ass_classinstructor", "ass_classinstructor.classid = ass_qa.classid")
                ->where([
                    "citizen_id" => $citizen_id,
                    "classid" => $classid
        ]);

        $result = $query->createCommand()->queryScalar();

        return $result;
    }

    public function getEnrollseat($citizen_id = NULL, $classid = NULL) {
        $query = NEW Query();
        $query->select(["enrollseat"])
                ->from("ass_class")
                ->innerJoin("ass_classinstructor", "ass_classinstructor.classid = ass_class.classid")
                ->where([
                    "citizen_id" => $citizen_id,
                    "ass_class.classid" => $classid
        ]);

        $result = $query->createCommand()->queryScalar();

        return $result;
    }

    public static function getAverage($citizen_id = NULL, $classid = NULL) {
//        SELECT avg(score) FROM `ass_qa`, ass_item, ass_classinstructor, ass_class WHERE ass_qa.itemid = ass_item.item_id and ass_qa.classid = ass_classinstructor.classid and ass_qa.classid = ass_class.classid and ass_qa.classid = '472910' and ass_classinstructor.citizen_id = '3409900700814'

        $query = NEW Query();

        $query->select("avg(score)")
                ->from("ass_qa")
                ->innerJoin("ass_item", "ass_qa.itemid = ass_item.item_id")
//                ->innerJoin("ass_classinstructor", "ass_qa.classid = ass_classinstructor.classid ")
                ->where([
                    "citizen_id" => $citizen_id,
                    "ass_qa.classid" => $classid
        ]);

        $result = $query->createCommand()->queryScalar();

        return $result;
    }

    public static function getItemAssess($citizen_id = NULL, $classid = NULL, $studycode = NULL) {
        $query = NEW Query();
        $query->select(['item_name_th', 'AVG(score) AS avg_subscore', 'MAX(score) AS max_subscore', 'MIN(score) AS min_subscore', 'STD(score) AS std_subscore'])
                ->from('ass_qa')
                ->innerJoin('ass_item', 'ass_qa.itemid = ass_item.item_id')
//                ->innerJoin("ass_classinstructor", "ass_classinstructor.classid = ass_qa.classid")
                ->where([
                    'citizen_id' => $citizen_id
                    , 'classid' => $classid
                        //, 'studycode' => $studycode
                ])
                ->addGroupBy('item_name_th')
                ->orderBy("item_name_th ASC");
        
        $results = $query->createCommand()->queryAll();
        return $results;
    }

    public static function getComments($citizen_id = NULL, $classid = NULL) {

        $query = NEW Query();
        $query->select(["comments"])
                ->from('ass_qa_comments')
//                ->innerJoin("ass_classinstructor", "ass_classinstructor.classid = ass_qa_comments.classid")
                ->where([
                    'citizen_id' => $citizen_id
                    , 'classid' => $classid
        ]);

        $results = $query->createCommand()->queryAll();

        return $results;
    }

}
