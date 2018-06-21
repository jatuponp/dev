<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_position_group".
 *
 * @property integer $position_group_id
 * @property string $position_group
 */
class tblPositionGroup extends \yii\db\ActiveRecord {

    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_position_group';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['position_group'], 'required'],
            [['position_group'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'position_group_id' => 'Position Group ID',
            'position_group' => 'สายตำแหน่ง',
        ];
    }

    public static function lists($search = NULL) {

        $query = parent::find();
        //->where(['like', 'title', $search]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

    public static function makeDD($all = false) {

        $results = parent::find()->all();

        $data = array();
        ($all) ? $data[0] = "สายปฏิบัติงานทั้งหมด" : "";
        foreach ($results as $value) {

            $data[$value->position_group_id] = $value->position_group;
        }

        return $data;
    }

}
