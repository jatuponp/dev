<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "std_faculty".
 *
 * @property integer $facultyid
 * @property string $facultyname
 * @property string $facultynameeng
 * @property string $facultyabb
 * @property string $facultyabbeng
 * @property string $dean
 * @property string $deaneng
 * @property string $facultytype
 * @property string $fac_id
 * @property string $facultygroup
 * @property integer $coursegroupid
 */
class stdFaculty extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'std_faculty';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['facultyid', 'facultyname', 'facultynameeng', 'facultyabb', 'facultyabbeng', 'dean', 'deaneng', 'facultytype', 'fac_id', 'facultygroup', 'coursegroupid'], 'required'],
            [['facultyid', 'coursegroupid'], 'integer'],
            [['facultyname', 'facultynameeng', 'dean', 'deaneng'], 'string', 'max' => 100],
            [['facultyabb', 'facultyabbeng'], 'string', 'max' => 50],
            [['facultytype', 'fac_id', 'facultygroup'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'facultyid' => 'Facultyid',
            'facultyname' => 'Facultyname',
            'facultynameeng' => 'Facultynameeng',
            'facultyabb' => 'Facultyabb',
            'facultyabbeng' => 'Facultyabbeng',
            'dean' => 'Dean',
            'deaneng' => 'Deaneng',
            'facultytype' => 'Facultytype',
            'fac_id' => 'Fac ID',
            'facultygroup' => 'Facultygroup',
            'coursegroupid' => 'Coursegroupid',
        ];
    }

    public static function makeDDFaculty($all = TRUE) {

        //'facid' => [4, 8, 15, 16, 23, 42],
        $faculty_id = array(41, 42, 43, 44, 45);

        $results = stdFaculty::find()
                        ->select(["facultyid", "facultyname"])
                        ->where([
                            'facultyid' => $faculty_id
                        ])->all();

        $data = array();

        ($all)? $data[""] = "ทั้งหมด":"";
        
        foreach ($results as $value) {
            $data[$value->facultyid] = $value->facultyname;
        }
        
        $data["1"] = "สำนักงานวิทยาเขตหนองคาย";

        return $data;
    }
    
    public static function getName($id) {
        return stdFaculty::findOne(['facultyid' => $id])->facultyname;
        
    }

}
