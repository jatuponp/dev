<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\db\Exception;

/**
 * This is the model class for table "ams_project".
 *
 * @property integer $proj_id
 * @property string $nameth
 * @property string $nameeng
 * @property string $acadyear
 * @property string $proj_code
 * @property string $modified
 * @property integer $createby
 *
 * @property AmsProjectOpen[] $amsProjectOpens
 * @property AmsProjectProgram[] $amsProjectPrograms
 * @property AmsProjectProvince[] $amsProjectProvinces
 * @property AmsRegister[] $amsRegisters
 * @property AmsWeightgpax[] $amsWeightgpaxes
 * @property AmsWeightsubject[] $amsWeightsubjects
 */
class amsProject extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ams_project';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nameth', 'acadyear'], 'required'],
            [['modified'], 'safe'],
            [['createby'], 'integer'],
            [['nameth', 'nameeng'], 'string', 'max' => 100],
            [['acadyear'], 'string', 'max' => 4],
            [['proj_code'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'proj_id' => 'รหัสโครงการ',
            'nameth' => 'ชื่อโครงการภาษาไทย',
            'nameeng' => 'ชื่อโครงการภาษาอังกฤษ',
            'acadyear' => 'ปีการศึกษา',
            'proj_code' => 'Project Code',
            'modified' => 'Modified',
            'createby' => 'Createby',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsProjectOpens() {
        return $this->hasMany(AmsProjectOpen::className(), ['proj_id' => 'proj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsProjectPrograms() {
        return $this->hasMany(AmsProjectProgram::className(), ['proj_id' => 'proj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsProjectProvinces() {
        return $this->hasMany(AmsProjectProvince::className(), ['proj_id' => 'proj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsRegisters() {
        return $this->hasMany(AmsRegister::className(), ['proj_id' => 'proj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmsWeightsubjects() {
        return $this->hasMany(AmsWeightsubject::className(), ['proj_id' => 'proj_id']);
    }

    public function lists($search = null) {
        if ($search) {
            $query = amsProject::find()
                    ->where(['like', 'nameth', $search])
                    ->orWhere(['like', 'nameeng', $search])
                    ->orderBy("acadyear ASC")

            ;
        } else {
            $query = amsProject::find()
                    ->orderBy("acadyear ASC, proj_id  ASC");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    public static function getProjname($proj_id = NULL, $lang = "th") {

        $result = amsProject::findOne($proj_id);

        if ($lang == "th") {
            return $result->nameth;
        } elseif ($lang == "en") {
            return $result->nameeng;
        }
    }

    public static function makeDDProjname($all = True, $acadyear = NULL, $withyear = FALSE) {

        if (!$acadyear) {
            $acadyear = Yii::$app->getModule("admission")->acadyears;
        }
        //$results = amsProject::findAll(["acadyear" => $acadyear]);
	$results = amsProject::find()
                ->where(["acadyear" => [$acadyear-1, $acadyear, $acadyear+1]])
		->all();

        $data = array();

        ($all)? $data[""] = "ทั้งหมด":"";
        
        foreach ($results as $value) {
            if ($withyear) {
                $data[$value->proj_id] = $value->nameth . " [" . $value->acadyear . "]";
            } else {
                $data[$value->proj_id] = $value->nameth;
            }
            
        }

        return $data;
    }
    
    public function getAllow($pro_id) {
        $query = NEW Query();
        $query->from("ams_register")
                ->where([
                    "proj_id" => $pro_id,
                ])->count();
        $count = $query->createCommand()->queryScalar();
        
        if ($count > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function copy($proj_id) {

        $connection = Yii::$app->db;

        $transaction = $connection->beginTransaction();
        try {
            
            $citizen_id = Yii::$app->user->identity->citizen_id;
            
            $sql = "INSERT INTO  ams_project( nameth, nameeng, acadyear, proj_code, createby ) "
                    . " SELECT CONCAT('สำเนาของ ', nameth), CONCAT('Copy of ', nameeng), acadyear, proj_code, $citizen_id "
                    . " FROM ams_project "
                    . " WHERE proj_id = '$proj_id' ";
            $command = $connection->createCommand($sql);
            $command->execute();

            $new_proj_id = Yii::$app->db->lastInsertID;

            $query = NEW Query();

            $query->select(['*'])
                    ->from('ams_project_open')
                    ->where(['proj_id' => $proj_id]);

            $result = $query->createCommand()->queryAll();

            foreach ($result as $r) {
                $sql = "INSERT INTO ams_project_open (date_start, date_stop, proj_id) "
                        . " VALUES ('" . $r["date_start"] . "', '" . $r["date_stop"] . "', '$new_proj_id') "
                        . " "
                ;
                $command = $connection->createCommand($sql);
                $command->execute();
                $new_open_id = Yii::$app->db->lastInsertID;

                $query->select(['*'])
                        ->from('ams_project_program')
                        ->where([
                            'proj_id' => $r["proj_id"],
                            'open_id' => $r["open_id"],
                        ]);

                $result1 = $query->createCommand()->queryAll();
                foreach ($result1 as $r1) {
                    $sql = "INSERT INTO  ams_project_program (proj_id, open_id, programcode) "
                            . " VALUES ('" . $new_proj_id . "', '" . $new_open_id . "', '" . $r1["programcode"] . "') "
                    ;
                    $command = $connection->createCommand($sql);
                    $command->execute();
                }
                
                $query->select(['*'])
                        ->from('ams_project_province')
                        ->where([
                            'proj_id' => $r["proj_id"],
                            'open_id' => $r["open_id"],
                        ]);

                $result2 = $query->createCommand()->queryAll();
                foreach ($result2 as $r2) {
                    $sql = "INSERT INTO  ams_project_province (proj_id, open_id, province_id) "
                            . " VALUES ('" . $new_proj_id . "', '" . $new_open_id . "', '" . $r2["province_id"] . "') "
                    ;
                    $command = $connection->createCommand($sql);
                    $command->execute();
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo "<pre>";
            print_r($e);
            echo "</pre>";
        }
    }

}
