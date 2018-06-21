<?php

namespace common\models\admission;

use Yii;
use yii\data\ActiveDataProvider;
use common\components\ndate;
use yii\db\Query;

/**
 * This is the model class for table "ams_project_open".
 *
 * @property integer $open_id
 * @property string $date_start
 * @property string $date_stop
 * @property integer $proj_id
 * @property string $modified
 *
 * @property AmsProject $proj
 * @property AmsProjectProgram[] $amsProjectPrograms
 * @property AmsProjectProvince[] $amsProjectProvinces
 */
class amsProjectOpen extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_project_open';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['date_start', 'date_stop', 'proj_id'], 'required'],
            [['date_start', 'date_stop', 'modified'], 'safe'],
            [['proj_id'], 'integer'],
            [['date_start', 'date_stop', 'proj_id'], 'unique', 'targetAttribute' => ['date_start', 'date_stop', 'proj_id'], 'message' => 'โครงการนี้กำหนดช่วงวันนี้แล้ว'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'open_id' => 'Open ID',
            'date_start' => 'Date Start',
            'date_stop' => 'Date Stop',
            'proj_id' => 'Proj ID',
            'modified' => 'Modified',
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
    public function getAmsProjectPrograms() {
        return $this->hasMany(AmsProjectProgram::className(), ['open_id' => 'open_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsProjectProvinces() {
        return $this->hasMany(AmsProjectProvince::className(), ['open_id' => 'open_id']);
    }

    public function lists($proj_id) {

        $query = amsProjectOpen::find()
                ->select(["*", "DATE_FORMAT(date_start,'%d / %m / %Y') AS date_start", "DATE_FORMAT(date_stop,'%d / %m / %Y') AS date_stop" ])
                ->where(["proj_id" => $proj_id])
                ->orderBy("open_id ASC");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    public function makeDD($all = FALSE, $proj_id = NULL) {
        
        $ndate = NEW ndate();

        $results = amsProjectOpen::find()
                ->select(["open_id", "DATE_FORMAT(date_start,'%d  %M  %Y') AS date_start", "DATE_FORMAT(date_stop,'%d  %M  %Y') AS date_stop"])
                ->where([
                    "proj_id" => $proj_id
                ])
                ->orderBy("open_id ASC")
               ->all();
                
//                if ($proj_id) {
//                    $results->andwh
//                }
                

        $data = array();
        
        $data[""] = "";

        if ($all)
            $data["all"] = "ทั้งหมด";

        $i=1;
        foreach ($results as $value) {
            $data[$value->open_id] = "รอบที่ $i ==> ".$value->date_start ." ถึง ". $value->date_stop;
            $i++;
        }
        return $data;
    }
    
    public function getAllow($open_id) {
        $query = NEW Query();
        $query->from("ams_register")
                ->where([
                    "open_id" => $open_id,
                ])->count();
        $count = $query->createCommand()->queryScalar();
        
        if ($count > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
        
    }
}
