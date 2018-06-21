<?php

namespace common\models\assess;

use Yii;
use common\models\stdFaculty;

/**
 * This is the model class for table "tbl_faculty_nkc".
 *
 * @property integer $id
 * @property integer $facultyid
 *
 * @property StdFaculty $faculty
 */
class tblFacultyNkc extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_faculty_nkc';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['facultyid'], 'required'],
            [['facultyid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'facultyid' => 'Facultyid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaculty() {
        return $this->hasOne(stdFaculty::className(), ['facultyid' => 'facultyid']);
    }

    public function makeDDFacultyNKC($all = FALSE) {

        $results = tblFacultyNkc::find()
                ->select(["std_faculty.facultyid", "facultyname"])
                ->joinWith("faculty")
                ->asArray()
                ->all();

        $data = array();

        if ($all)
            $data["all"] = "ทั้งหมด";
        
        foreach ($results as $value) {
            $data[$value["facultyid"]] = $value["facultyname"];
//            echo "<pre>";
//            print_r($value["facultyname"]);
//            echo "</pre>";
//            exit();
        }

        return $data;

//        return $results;
    }

}
