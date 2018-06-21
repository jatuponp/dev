<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ams_program".
 *
 * @property string $programcode
 * @property integer $facultyid
 * @property string $programname
 * @property string $programabbeng
 * @property string $register_name
 *
 * @property AmsAdmission[] $amsAdmissions
 * @property StdFaculty $faculty
 * @property AmsRegister[] $amsRegisters
 */
class amsProgram extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_program';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['programcode', 'facultyid', 'programname', 'programabbeng'], 'required'],
            [['facultyid'], 'integer'],
            [['programcode'], 'string', 'max' => 6],
            [['programname', 'register_name'], 'string', 'max' => 255],
            [['programabbeng'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'programcode' => 'รหัส',
            'facultyid' => 'Facultyid',
            'programname' => 'สาขาวิชา',
            'programabbeng' => 'ชื่อย่อ',
            'register_name' => 'ชื่อใช้ในตอนสมัครเรียน',
            'facultyname' => 'คณะ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsAdmissions() {
        return $this->hasMany(amsAdmission::className(), ['programcode' => 'programcode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaculty() {
        return $this->hasOne(stdFaculty::className(), ['facultyid' => 'facultyid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsRegisters() {
        return $this->hasMany(amsRegister::className(), ['programcode' => 'programcode']);
    }

    public static function _getName($programcode = NULL) {

        return amsProgram::findOne(["programcode" => $programcode]);
    }
    
    public function getFacultyName() {
        return $this->faculty->facultyname;
////      OR  $customer->getDept()->andWhere('status=1')->all() 
    }

    public function lists($provider = FALSE) {

        $query = amsProgram::find()
                ->orderBy("facultyid ASC");

        if ($provider) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            
            return $dataProvider;
        } else {
            
            return $query;
        }
    }

    public static function listsProgramcode($faculty_id = NULL) {

        if ($faculty_id) {
            //return amsProgram::find(["facultyid" => $faculty_id])->asArray()->all();
            return amsProgram::findAll(["facultyid" => $faculty_id]);
        } else {
            $query = amsProgram::find()->all();
            return $query;
        }
    }

    public static function makeDDProgramName($all = TRUE) {

        //'id' => [4, 8, 15, 16, 23, 42],

        $results = amsProgram::find()
                        ->select(["programcode", "programname"])
                        ->where([
                            'facultyid' => Yii::$app->getModule("admission")->facultyid
                        ])->all();

        $data = array();

        ($all)? $data[""] = "ทั้งหมด":"";

        foreach ($results as $value) {
            $data[$value->programcode] = $value->programname;
        }

        //$data = Yii::$app->getModule("admission")->facultyid;
        //$data = $results;
        return $data;
    }

    public function makeDD($all = FALSE) {

        $results = amsProgram::find()
                ->select(["programcode", "register_name"])
                ->where("register_name IS NOT NULL or register_name <> ''")
                ->all();

        $data = array();

        if ($all)
            $data["all"] = "ทั้งหมด";

        foreach ($results as $value) {
            $data[$value->programcode] = $value->register_name;
        }

        //$data = Yii::$app->getModule("admission")->facultyid;
        //$data = $results;
        return $data;
    }

}
