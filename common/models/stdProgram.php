<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "std_program".
 *
 * @property string $programid
 * @property string $programcode
 * @property string $programtype
 * @property integer $programyear
 * @property string $revisioncode
 * @property integer $facultyid
 * @property integer $departmentid
 * @property integer $degreeid
 * @property integer $levelid
 * @property string $programname
 * @property string $programnameeng
 * @property string $programabb
 * @property string $programabbeng
 * @property string $credittotal
 * @property string $description
 * @property string $descriptioneng
 * @property integer $studyyearmax
 * @property string $gradepointmin
 * @property string $createdatetime
 * @property string $createuserid
 * @property string $lastupdatedatetime
 * @property string $lastupdateuserid
 * @property string $studyperiod
 * @property string $controllername
 * @property string $controllerposition
 * @property string $programnamecertify
 * @property integer $semesterperyear
 * @property integer $programstatus
 * @property string $programversion
 * @property string $opendate
 * @property string $closedate
 * @property string $passcondition
 * @property integer $studyyear
 * @property integer $offsetyear
 * @property string $isced_id
 * @property string $program_id
 * @property integer $programsem
 * @property string $versiondate
 * @property integer $studentnum
 * @property string $kopendate
 * @property string $program_mapping
 * @property integer $programgroup
 * @property string $t_ref_group
 */
class stdProgram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'std_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['programid', 'programcode', 'programtype', 'programyear', 'revisioncode', 'facultyid', 'departmentid', 'degreeid', 'levelid', 'programname', 'programnameeng', 'programabb', 'programabbeng', 'credittotal', 'description', 'descriptioneng', 'studyyearmax', 'gradepointmin', 'createdatetime', 'createuserid', 'lastupdatedatetime', 'lastupdateuserid', 'studyperiod', 'controllername', 'controllerposition', 'programnamecertify', 'semesterperyear', 'programstatus', 'programversion', 'opendate', 'closedate', 'passcondition', 'studyyear', 'offsetyear', 'isced_id', 'program_id', 'programsem', 'versiondate', 'studentnum', 'kopendate', 'program_mapping', 'programgroup', 't_ref_group'], 'required'],
            [['programid', 'programyear', 'facultyid', 'departmentid', 'degreeid', 'levelid', 'studyyearmax', 'semesterperyear', 'programstatus', 'studyyear', 'offsetyear', 'programsem', 'studentnum', 'programgroup'], 'integer'],
            [['credittotal', 'gradepointmin'], 'number'],
            [['createdatetime', 'lastupdatedatetime', 'opendate', 'closedate', 'versiondate', 'kopendate'], 'safe'],
            [['programcode'], 'string', 'max' => 14],
            [['programtype', 'programversion'], 'string', 'max' => 1],
            [['revisioncode', 'isced_id', 'program_id', 't_ref_group'], 'string', 'max' => 4],
            [['programname', 'programnameeng', 'programabb', 'description', 'descriptioneng', 'controllername', 'controllerposition', 'programnamecertify', 'passcondition', 'program_mapping'], 'string', 'max' => 100],
            [['programabbeng'], 'string', 'max' => 32],
            [['createuserid', 'lastupdateuserid'], 'string', 'max' => 16],
            [['studyperiod'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'programid' => 'Programid',
            'programcode' => 'Programcode',
            'programtype' => 'Programtype',
            'programyear' => 'Programyear',
            'revisioncode' => 'Revisioncode',
            'facultyid' => 'Facultyid',
            'departmentid' => 'Departmentid',
            'degreeid' => 'Degreeid',
            'levelid' => 'Levelid',
            'programname' => 'Programname',
            'programnameeng' => 'Programnameeng',
            'programabb' => 'Programabb',
            'programabbeng' => 'Programabbeng',
            'credittotal' => 'Credittotal',
            'description' => 'Description',
            'descriptioneng' => 'Descriptioneng',
            'studyyearmax' => 'Studyyearmax',
            'gradepointmin' => 'Gradepointmin',
            'createdatetime' => 'Createdatetime',
            'createuserid' => 'Createuserid',
            'lastupdatedatetime' => 'Lastupdatedatetime',
            'lastupdateuserid' => 'Lastupdateuserid',
            'studyperiod' => 'Studyperiod',
            'controllername' => 'Controllername',
            'controllerposition' => 'Controllerposition',
            'programnamecertify' => 'Programnamecertify',
            'semesterperyear' => 'Semesterperyear',
            'programstatus' => 'Programstatus',
            'programversion' => 'Programversion',
            'opendate' => 'Opendate',
            'closedate' => 'Closedate',
            'passcondition' => 'Passcondition',
            'studyyear' => 'Studyyear',
            'offsetyear' => 'Offsetyear',
            'isced_id' => 'Isced ID',
            'program_id' => 'Program ID',
            'programsem' => 'Programsem',
            'versiondate' => 'Versiondate',
            'studentnum' => 'Studentnum',
            'kopendate' => 'Kopendate',
            'program_mapping' => 'Program Mapping',
            'programgroup' => 'Programgroup',
            't_ref_group' => 'T Ref Group',
        ];
    }
}
