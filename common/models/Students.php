<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_students".
 *
 * @property integer $id
 * @property integer $campus_id
 * @property integer $student_id
 * @property string $student_code
 * @property string $sex
 * @property string $prefix_name
 * @property string $first_thname
 * @property string $last_thname
 * @property string $first_enname
 * @property string $last_enname
 * @property integer $status
 * @property string $academic_year
 * @property integer $program_id
 * @property string $program_enname
 * @property string $program_thname
 * @property string $birthdate
 * @property string $citizen_id
 */
class Students extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_students';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campus_id', 'student_id', 'student_code', 'sex', 'prefix_name', 'first_thname', 'last_thname', 'first_enname', 'last_enname', 'status', 'academic_year', 'program_id', 'program_enname', 'program_thname', 'birthdate', 'citizen_id'], 'required'],
            [['campus_id', 'student_id', 'status', 'program_id'], 'integer'],
            [['birthdate'], 'safe'],
            [['student_code'], 'string', 'max' => 20],
            [['sex', 'academic_year'], 'string', 'max' => 10],
            [['prefix_name', 'program_enname'], 'string', 'max' => 100],
            [['first_thname', 'last_thname', 'first_enname', 'last_enname'], 'string', 'max' => 300],
            [['program_thname'], 'string', 'max' => 200],
            [['citizen_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campus_id' => 'Campus ID',
            'student_id' => 'Student ID',
            'student_code' => 'Student Code',
            'sex' => 'Sex',
            'prefix_name' => 'Prefix Name',
            'first_thname' => 'First Thname',
            'last_thname' => 'Last Thname',
            'first_enname' => 'First Enname',
            'last_enname' => 'Last Enname',
            'status' => 'Status',
            'academic_year' => 'Academic Year',
            'program_id' => 'Program ID',
            'program_enname' => 'Program Enname',
            'program_thname' => 'Program Thname',
            'birthdate' => 'Birthdate',
            'citizen_id' => 'Citizen ID',
        ];
    }
    
    public function getStudentName($student_code){
        $q = Students::findOne(['student_code'=>$student_code]);
        return $q->prefix_name.$q->first_thname.' '.$q->last_thname;
    }
}
