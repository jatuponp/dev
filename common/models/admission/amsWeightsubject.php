<?php

namespace common\models\admission;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ams_weightsubject".
 *
 * @property integer $id
 * @property integer $proj_id
 * @property integer $open_id
 * @property string $programcode
 * @property double $wthai
 * @property double $wmath
 * @property double $wsci
 * @property double $wsocial
 * @property double $wphy
 * @property double $wart
 * @property double $wjob
 * @property double $wlang
 * @property double $wgpax
 *
 * @property AmsProject $proj
 * @property AmsProgram $programcode0
 */
class amsWeightsubject extends \yii\db\ActiveRecord {

    public $sex, $confirm, $paid, $verified;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_weightsubject';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['proj_id', 'programcode'], 'required'],
            [['proj_id', 'open_id'], 'integer'],
            [['wthai', 'wmath', 'wsci', 'wsocial', 'wphy', 'wart', 'wjob', 'wlang', 'wgpax'], 'number'],
            [['programcode'], 'string', 'max' => 20],
            [['proj_id', 'programcode'], 'unique', 'targetAttribute' => ['proj_id', 'programcode'], 'message' => 'The combination of Proj ID and Programcode has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'proj_id' => 'โครงการ',
            'open_id' => 'Open ID',
            'programcode' => 'สาขาวิชา',
            'wthai' => 'ไทย',
            'wmath' => 'คณิตฯ',
            'wsci' => 'วิทย์',
            'wsocial' => 'สังคมฯ',
            'wphy' => 'พลศึกษา',
            'wart' => 'ศิลปะ',
            'wjob' => 'การงาน',
            'wlang' => 'ภาษาต่างประเทศ',
            'wgpax' => 'GPAX',
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

    public function lists($proj_id = NULL, $programcode = NULL, $data_provi = FALSE, $sortby = NULL, $sess = NULL) {
        $query = NEW Query();

        $query->select([
                    "ams_register.*",
                    "ams_gpa.*",
                    "ams_weightsubject.*",
                    "CONCAT(prefixname,ams_register.nameth, '   ', ams_register.surnameth) AS fullname",
                    "programname",
                    "province_name_th",
                    "(thai*wthai/divide) AS net_thai",
                    "(math*wmath/divide) AS net_math",
                    "(sci*wsci/divide) AS net_sci",
                    "(social*wsocial/divide) AS net_social",
                    "(phy*wphy/divide) AS net_phy",
                    "(art*wart/divide) AS net_art",
                    "(job*wjob/divide) AS net_job",
                    "(lang*wlang/divide) AS net_lang",
                    "(((GPAX*divide)/4)*wgpax/divide) AS net_gpax",
                    "(thai*wthai/divide + math*wmath/divide + sci*wsci/divide + social*wsocial/divide + phy*wphy/divide + art*wart/divide + job*wjob/divide + lang*wlang/divide) AS net_subject  ",
                    "(thai*wthai/divide + math*wmath/divide + sci*wsci/divide + social*wsocial/divide + phy*wphy/divide + art*wart/divide + job*wjob/divide + lang*wlang/divide + (((GPAX*divide)/4)*wgpax/divide)) AS net_total  ",
                ])->from("ams_register")
                ->innerJoin("ams_project", "ams_register.proj_id = ams_project.proj_id")
                ->innerJoin("std_ref_province", "ams_register.shool_province_id = std_ref_province.province_id")
                ->innerJoin("std_prefix", "ams_register.prefixid = std_prefix.prefixid")
                ->innerJoin("ams_program", "ams_register.programcode = ams_program.programcode")
                ->innerJoin("ams_weightsubject", "ams_register.proj_id = ams_weightsubject.proj_id AND ams_register.programcode = ams_weightsubject.programcode")
                ->innerJoin("ams_gpa", "ams_register.citizen_id = ams_gpa.citizen_id and ams_register.proj_id = ams_gpa.proj_id and ams_register.open_id = ams_gpa.open_id")
                ->where([
                    //"ams_project.acadyear" => Yii::$app->getModule("admission")->acadyears,
                    "ams_register.confirm" => "Y",
                        //"ams_register.sex" => "M",
                        //"ams_register.payment" => "Y",
                        //"ams_register.verify" => "Y",
                ])
                ->andWhere("ams_register.qualified = '1' or ams_register.qualified is null");
        //->andWhere(["<>", "ams_register.verify", 'N']);

        
        if (!empty($sess)) {
            ($sess["sex"]) ? $query->andWhere(["ams_register.sex" => $sess["sex"]]) : "";
            ($sess["confirm"] == 1) ? $query->andWhere(["ams_register.confirm" => "Y"]) : "";
            ($sess["paid"] == 1) ? $query->andWhere(["ams_register.payment" => 'Y']) : "";
            ($sess["verified"] == 1) ? $query->andWhere(["ams_register.verify" => "Y"]) : "";
        }
//        
//        ($qualified)? $query->andWhere(["<>", "ams_register.qualified", '0']):"";
//        ($confirm == 1)? $query->andWhere(["ams_register.confirm" => "Y"]):"";
//        


        if ($proj_id && $proj_id != "all") {
            $query->andWhere(["ams_register.proj_id" => $proj_id]);
        } else {
            $query->andWhere(["ams_register.proj_id" => 3]);
        }
        if ($programcode && $programcode != "all") {
            $query->andWhere(["ams_register.programcode" => $programcode]);
        } else {
            $query->andWhere(["ams_register.programcode" => "0206"]);
        }

        //$query->andWhere("ams_register.verify = 'Y' OR ams_register.verify is null");


        if ($data_provi) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
//                    'defaultOrder' => [
//                        'fullname' => SORT_ASC, 
//                    ],
                    'attributes' => [
//                        'fullname' => [
//                            'asc' => ['fullname' => SORT_ASC],
//                            'desc' => ['fullname' => SORT_DESC],
////                            'default' => SORT_DESC,
//                        //'label' => 'NAME',
//                        ],
                        'net_thai' => [
                            'asc' => ['net_thai' => SORT_ASC],
                            'desc' => ['net_thai' => SORT_DESC],
                            'default' => SORT_DESC,
                        //'label' => 'NAME',
                        ],
                        'net_math' => [
                            'asc' => ['net_math' => SORT_ASC],
                            'desc' => ['net_math' => SORT_DESC],
                            'default' => SORT_DESC,
                        ],
                        'net_sci' => [
                            'asc' => ['net_sci' => SORT_ASC],
                            'desc' => ['net_sci' => SORT_DESC],
                            'default' => SORT_DESC,
                        ],
                        'net_social' => [
                            'asc' => ['net_social' => SORT_ASC],
                            'desc' => ['net_social' => SORT_DESC],
                            'default' => SORT_DESC,
                        ],
                        'net_phy' => [
                            'asc' => ['net_phy' => SORT_ASC],
                            'desc' => ['net_phy' => SORT_DESC],
                            'default' => SORT_DESC,
                        ],
                        'net_art' => [
                            'asc' => ['net_art' => SORT_ASC],
                            'desc' => ['net_art' => SORT_DESC],
                            'default' => SORT_DESC,
                        ],
                        'net_job' => [
                            'asc' => ['net_job' => SORT_ASC],
                            'desc' => ['net_job' => SORT_DESC],
                            'default' => SORT_DESC,
                        ],
                        'net_lang' => [
                            'asc' => ['net_lang' => SORT_ASC],
                            'desc' => ['net_lang' => SORT_DESC],
                            'default' => SORT_DESC,
                        //'label' => 'NAME',
                        ],
                        'net_total' => [
                            'asc' => ['net_total' => SORT_ASC],
                            'desc' => ['net_total' => SORT_DESC],
                            'default' => SORT_DESC,
                        //'label' => 'NAME',
                        ],
                    ],
                ],
                'pagination' => [
                    'pageSize' => 25,
                ],
            ]);

            $dataProvider->sort->defaultOrder = ['net_total' => SORT_DESC];

            return $dataProvider;
        } else {
            switch ($sortby) {
                case "-net_total":
                    $query->orderBy("net_total DESC");
                    break;
                case "net_total":
                    $query->orderBy("net_total ASC");
                    break;
                case "-net_thai":
                    $query->orderBy("net_thai DESC");
                    break;
                case "net_thai":
                    $query->orderBy("net_thai ASC");
                    break;
                case "-net_math":
                    $query->orderBy("net_math DESC");
                    break;
                case "net_math":
                    $query->orderBy("net_math ASC");
                    break;
                case "-net_sci":
                    $query->orderBy("net_sci DESC");
                    break;
                case "net_sci":
                    $query->orderBy("net_sci ASC");
                    break;
                case "-net_social":
                    $query->orderBy("net_social DESC");
                    break;
                case "net_social":
                    $query->orderBy("net_social ASC");
                    break;
                case "-net_phy":
                    $query->orderBy("net_phy DESC");
                    break;
                case "net_phy":
                    $query->orderBy("net_phy ASC");
                    break;
                case "-net_art":
                    $query->orderBy("net_art DESC");
                    break;
                case "net_art":
                    $query->orderBy("net_art ASC");
                    break;
                case "-net_job":
                    $query->orderBy("net_job DESC");
                    break;
                case "net_job":
                    $query->orderBy("net_job ASC");
                    break;
                case "-net_lang":
                    $query->orderBy("net_lang DESC");
                    break;
                case "net_lang":
                    $query->orderBy("net_lang ASC");
                    break;

                default:
                    $query->orderBy("net_total DESC");
                    break;
            }

            return $query->createCommand()->queryAll();
        }
    }

    public function listsColumn($proj_id, $programcode) {
        $query = NEW Query();

        $query->select("*")
                ->from("ams_weightsubject")
                ->where([
                    "proj_id" => $proj_id,
                    "programcode" => $programcode
        ]);

        return $query->createCommand()->queryOne();
    }

    public function lists2($provider = FALSE, $proj_id = NULL) {
        $query = NEW Query();

        $query->select(["*"])
                ->from("ams_weightsubject")
                ->innerJoin("ams_project", "ams_project.proj_id = ams_weightsubject.proj_id")
                ->innerJoin("ams_program", "ams_program.programcode = ams_weightsubject.programcode");


        if ($proj_id && $proj_id != "all") {
            $query->andWhere(["ams_weightsubject.proj_id" => $proj_id]);
        }

        if ($provider) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $dataProvider;
        } else {

            return $query;
        }
    }

}
