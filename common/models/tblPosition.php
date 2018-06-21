<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_position".
 *
 * @property integer $position_id
 * @property string $position
 */
class tblPosition extends \yii\db\ActiveRecord {

    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_position';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['position'], 'required'],
            [['position'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'position_id' => 'Position ID',
            'position' => 'ชื่อตำแหน่ง',
        ];
    }

    /**
     * 
     * @param string $search
     * @return ActiveDataProvider
     */
    public static function lists($search = NULL) {

        $query = parent::find()
                ->filterWhere(['like', 'position', $search]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * 
     * @param boolean $all
     * @return array
     */
    public static function makeDD($all = false) {

        $results = parent::find()
                ->all();

        $data = array();
        ($all) ? $data[""] = "ทั้งหมด" : "";
        foreach ($results as $value) {
            $data[$value->position_id] = $value->position;
        }

        return $data;
    }

    /**
     * 
     * @param integer $position_id
     * @return string
     */
    public static function getName($position_id) {
        return parent::findOne(["position_id" => $position_id])->position;
    }

}
