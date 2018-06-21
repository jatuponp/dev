<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "std_studentbio".
 *
 * @property string $studentid
 * @property integer $nationid
 * @property integer $religionid
 * @property string $schoolid
 * @property string $entrytype
 * @property string $entrydegree
 * @property string $bloodgroup
 * @property string $birthdate
 * @property integer $birthprovinceid
 * @property string $homeaddress1
 * @property string $homeaddress2
 * @property string $homedistrict
 * @property string $homezipcode
 * @property string $homephoneno
 * @property integer $homeprovinceid
 * @property string $officename
 * @property string $officeaddress1
 * @property string $officeaddress2
 * @property string $officedistrict
 * @property string $officezipcode
 * @property string $officephoneno
 * @property string $officefaxno
 * @property integer $officeprovinceid
 * @property string $workingstatus
 * @property string $workingposition
 * @property string $workingsalary
 * @property string $studentfathername
 * @property string $studentmothername
 * @property string $studentsex
 * @property string $studentcode
 * @property integer $admitacadyear
 * @property integer $admitsemester
 * @property string $bankaccount
 * @property string $entrygpa
 * @property string $entrydegreeeng
 * @property string $citizenid
 * @property string $parentname
 * @property string $parentrelation
 * @property string $parentaddress1
 * @property string $parentaddress2
 * @property string $parentdistrict
 * @property string $parentzipcode
 * @property string $parentphoneno
 * @property integer $parentprovinceid
 * @property string $contactaddress1
 * @property string $contactaddress2
 * @property string $contactdistrict
 * @property string $contactzipcode
 * @property string $contactphoneno
 * @property integer $contactprovinceid
 * @property string $contactperson
 * @property string $entrancepoint
 * @property string $cardexpirydate
 * @property string $currentaddress1
 * @property string $currentaddress2
 * @property string $currentdistrict
 * @property integer $currentprovinceid
 * @property string $currentzipcode
 * @property string $currentphoneno
 * @property string $currentrelation
 * @property string $jobless
 * @property string $webenroll
 * @property string $recruitdate
 * @property string $officedepartment
 * @property string $cardprinted
 *
 * @property StdStudentmaster $student
 */
class stdStudentBio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'std_studentbio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentid', 'nationid', 'religionid', 'schoolid', 'entrytype', 'entrydegree', 'bloodgroup', 'birthdate', 'birthprovinceid', 'homeaddress1', 'homeaddress2', 'homedistrict', 'homezipcode', 'homephoneno', 'homeprovinceid', 'officename', 'officeaddress1', 'officeaddress2', 'officedistrict', 'officezipcode', 'officephoneno', 'officefaxno', 'officeprovinceid', 'workingstatus', 'workingposition', 'workingsalary', 'studentfathername', 'studentmothername', 'studentsex', 'studentcode', 'admitacadyear', 'admitsemester', 'bankaccount', 'entrygpa', 'entrydegreeeng', 'citizenid', 'parentname', 'parentrelation', 'parentaddress1', 'parentaddress2', 'parentdistrict', 'parentzipcode', 'parentphoneno', 'parentprovinceid', 'contactaddress1', 'contactaddress2', 'contactdistrict', 'contactzipcode', 'contactphoneno', 'contactprovinceid', 'contactperson', 'entrancepoint', 'cardexpirydate', 'currentaddress1', 'currentaddress2', 'currentdistrict', 'currentprovinceid', 'currentzipcode', 'currentphoneno', 'currentrelation', 'jobless', 'webenroll', 'recruitdate', 'officedepartment', 'cardprinted'], 'required'],
            [['studentid', 'nationid', 'religionid', 'schoolid', 'birthprovinceid', 'homeprovinceid', 'officeprovinceid', 'admitacadyear', 'admitsemester', 'parentprovinceid', 'contactprovinceid', 'currentprovinceid'], 'integer'],
            [['birthdate', 'cardexpirydate', 'webenroll', 'recruitdate'], 'safe'],
            [['workingsalary', 'entrygpa', 'entrancepoint'], 'number'],
            [['entrytype'], 'string', 'max' => 2],
            [['entrydegree', 'homeaddress1', 'homeaddress2', 'officename', 'officeaddress1', 'officeaddress2', 'workingposition', 'studentfathername', 'studentmothername', 'entrydegreeeng', 'parentname', 'parentaddress1', 'parentaddress2', 'contactaddress1', 'contactaddress2', 'currentaddress1', 'currentaddress2', 'currentrelation', 'jobless', 'officedepartment'], 'string', 'max' => 100],
            [['bloodgroup'], 'string', 'max' => 4],
            [['homedistrict', 'homephoneno', 'officedistrict', 'officephoneno', 'officefaxno', 'parentrelation', 'parentdistrict', 'parentphoneno', 'contactdistrict', 'contactphoneno', 'currentdistrict', 'currentphoneno'], 'string', 'max' => 32],
            [['homezipcode', 'officezipcode', 'parentzipcode', 'contactzipcode', 'currentzipcode'], 'string', 'max' => 8],
            [['workingstatus', 'studentsex', 'cardprinted'], 'string', 'max' => 1],
            [['studentcode', 'bankaccount'], 'string', 'max' => 16],
            [['citizenid'], 'string', 'max' => 13],
            [['contactperson'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studentid' => 'Studentid',
            'nationid' => 'Nationid',
            'religionid' => 'Religionid',
            'schoolid' => 'Schoolid',
            'entrytype' => 'Entrytype',
            'entrydegree' => 'Entrydegree',
            'bloodgroup' => 'Bloodgroup',
            'birthdate' => 'Birthdate',
            'birthprovinceid' => 'Birthprovinceid',
            'homeaddress1' => 'Homeaddress1',
            'homeaddress2' => 'Homeaddress2',
            'homedistrict' => 'Homedistrict',
            'homezipcode' => 'Homezipcode',
            'homephoneno' => 'Homephoneno',
            'homeprovinceid' => 'Homeprovinceid',
            'officename' => 'Officename',
            'officeaddress1' => 'Officeaddress1',
            'officeaddress2' => 'Officeaddress2',
            'officedistrict' => 'Officedistrict',
            'officezipcode' => 'Officezipcode',
            'officephoneno' => 'Officephoneno',
            'officefaxno' => 'Officefaxno',
            'officeprovinceid' => 'Officeprovinceid',
            'workingstatus' => 'Workingstatus',
            'workingposition' => 'Workingposition',
            'workingsalary' => 'Workingsalary',
            'studentfathername' => 'Studentfathername',
            'studentmothername' => 'Studentmothername',
            'studentsex' => 'Studentsex',
            'studentcode' => 'Studentcode',
            'admitacadyear' => 'Admitacadyear',
            'admitsemester' => 'Admitsemester',
            'bankaccount' => 'Bankaccount',
            'entrygpa' => 'Entrygpa',
            'entrydegreeeng' => 'Entrydegreeeng',
            'citizenid' => 'Citizenid',
            'parentname' => 'Parentname',
            'parentrelation' => 'Parentrelation',
            'parentaddress1' => 'Parentaddress1',
            'parentaddress2' => 'Parentaddress2',
            'parentdistrict' => 'Parentdistrict',
            'parentzipcode' => 'Parentzipcode',
            'parentphoneno' => 'Parentphoneno',
            'parentprovinceid' => 'Parentprovinceid',
            'contactaddress1' => 'Contactaddress1',
            'contactaddress2' => 'Contactaddress2',
            'contactdistrict' => 'Contactdistrict',
            'contactzipcode' => 'Contactzipcode',
            'contactphoneno' => 'Contactphoneno',
            'contactprovinceid' => 'Contactprovinceid',
            'contactperson' => 'Contactperson',
            'entrancepoint' => 'Entrancepoint',
            'cardexpirydate' => 'Cardexpirydate',
            'currentaddress1' => 'Currentaddress1',
            'currentaddress2' => 'Currentaddress2',
            'currentdistrict' => 'Currentdistrict',
            'currentprovinceid' => 'Currentprovinceid',
            'currentzipcode' => 'Currentzipcode',
            'currentphoneno' => 'Currentphoneno',
            'currentrelation' => 'Currentrelation',
            'jobless' => 'Jobless',
            'webenroll' => 'Webenroll',
            'recruitdate' => 'Recruitdate',
            'officedepartment' => 'Officedepartment',
            'cardprinted' => 'Cardprinted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(StdStudentmaster::className(), ['studentid' => 'studentid']);
    }
    
    
}
