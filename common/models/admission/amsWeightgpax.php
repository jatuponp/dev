<?php

namespace common\models\admission;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "ams_weightgpax".
 *
 * @property integer $id
 * @property integer $proj_id
 * @property string $programcode
 * @property string $subject
 * @property double $weight
 *
 * @property AmsProject $proj
 * @property AmsProgram $programcode0
 */
class amsWeightgpax extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_weightgpax';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['proj_id', 'programcode', 'subject', 'weight'], 'required'],
            [['proj_id'], 'integer'],
            [['weight'], 'number'],
            [['programcode', 'subject'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'proj_id' => 'โครงการ',
            'programcode' => 'สาขาวิชา',
            'subject' => 'Subject',
            'weight' => 'Weight',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProj() {
        return $this->hasOne(AmsProject::className(), ['proj_id' => 'proj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramcode0() {
        return $this->hasOne(AmsProgram::className(), ['programcode' => 'programcode']);
    }

    public function listsStudent($proj_id = NULL, $programcode = NULL, $data_provi = FALSE) {
        $query = NEW Query();

        $query->select(["ams_register.*"
                    , "CONCAT(prefixname,ams_register.nameth, '   ', ams_register.surnameth) AS fullname"
                    , "programname",
                    "thai",
                    "math",
                    "sci",
                    "social",
                    "phy",
                    "art",
                    "job",
                    "lang",
            "(thai + math + sci + social + phy + lang+ GPAX) AS gpax_total ",
//                    , "ams_school.school_name AS school_name"
//                    , "province_name_th"
                ])
                ->from("ams_register")
                ->innerJoin("ams_project", "ams_register.proj_id = ams_project.proj_id")
                ->innerJoin("std_prefix", "ams_register.prefixid = std_prefix.prefixid")
                ->innerJoin("ams_program", "ams_register.programcode = ams_program.programcode")
                ->innerJoin("ams_gpa", "ams_register.citizen_id = ams_gpa.citizen_id and ams_register.proj_id = ams_gpa.proj_id and ams_register.open_id = ams_gpa.open_id")
//                ->innerJoin("ams_gpa", "ams_register.open_id = ams_gpa.open_id")
//                ->innerJoin("ams_school", "ams_register.school_id = ams_school.school_id")
//                ->innerJoin("std_ref_province", "ams_register.shool_province_id = std_ref_province.province_id")
                ->where([
                    "ams_project.acadyear" => Yii::$app->getModule("admission")->acadyears,
                    "ams_register.confirm" => "Y",
                    "ams_register.payment" => "Y",
                    //"ams_register.verify" => "Y",
        ]);

        if ($proj_id && $proj_id != "all") {
            $query->andWhere(["ams_register.proj_id" => $proj_id]);
        }
        if ($programcode && $programcode != "all") {
            $query->andWhere(["ams_register.programcode" => $programcode]);
        }
        
        $query->andWhere("ams_register.verify = 'Y' OR ams_register.verify is null");
        
        $query->orderBy("gpax_total DESC");

        if ($data_provi) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
//            'sort' => [
////                'defaultOrder' => [
////                    'ams_register.modified' => SORT_DESC,
////                    'GPAX' => SORT_DESC
////                ],
//                'attributes' => [
//                    //'age',
////                    'fullname' => [
////                        'asc' => ['fullname' => SORT_ASC],
////                        'desc' => ['fullname' => SORT_DESC],
////                        //'label' => 'NAME',
////                    ],
//                    'verify' => [
//                        'asc' => ['ams_register.verify' => SORT_ASC],
//                        'desc' => ['ams_register.verify' => SORT_DESC],
//                        //'label' => 'NAME',
//                    ],
//                    'payment' => [
//                        'asc' => ['payment' => SORT_ASC],
//                        'desc' => ['payment' => SORT_DESC],
//                        //'label' => 'NAME',
//                    ],
//                ],
//            ],
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $dataProvider;
        } else {
            return $query->createCommand()->queryAll();
        }
    }

    public function listsSubject($proj_id = NULL, $programcode = NULL) {
        $query = NEW Query();
        $query->select("*")
                ->from("ams_weightgpax")
                ->where([
                    "proj_id" => $proj_id,
                    "programcode" => $programcode,
        ]);

        return $query->createCommand()->queryAll();
    }

    public function listsGpaxdetail($proj_id = NULL, $programcode = NULL) {
        
    }

}
