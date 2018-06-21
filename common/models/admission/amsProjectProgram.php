<?php

namespace common\models\admission;

use Yii;
use \yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ams_project_program".
 *
 * @property integer $proj_id
 * @property integer $open_id
 * @property string $programcode
 *
 * @property AmsProjectOpen $open
 * @property AmsProject $proj
 * @property AmsProgram $programcode0
 */
class amsProjectProgram extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_project_program';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['proj_id', 'open_id', 'programcode'], 'required'],
            [['proj_id', 'open_id'], 'integer'],
            [['programcode'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'proj_id' => 'Proj ID',
            'open_id' => 'Open ID',
            'programcode' => 'Programcode',
                //'programname' => 'สาขาวิชา',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpen() {
        return $this->hasOne(AmsProjectOpen::className(), ['open_id' => 'open_id']);
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

    public function lists($proj_id, $open_id = NULL) {

        $query = NEW Query();
        $query->select(["*", "ams_program.programcode AS programcode"])
                ->from(["ams_project_program"])
                ->innerJoin("ams_program", "ams_program.programcode = ams_project_program.programcode")
                ->where([
                    "proj_id" => $proj_id,
                    "open_id" => $open_id
                ])
                ->orderBy("programname ASC");

//        if ($open_id) {
//            $query->andWhere([
//                "open_id" => $open_id
//            ]);
//        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

//    public function makeDD() {
//        $query = amsProjectProgram::find()
//                ->where ('register_name IS NOT NULL')
//                ->all();
//
//        $data = array();
//        //$data['all'] = "ทั้งหมด";
//        foreach ($query as $row) {
//
//            $data[$row->programcode] = $row->register_name;
//        }
//
//        return $data;
//        
//    }
}
