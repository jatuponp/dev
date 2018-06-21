<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_position_type".
 *
 * @property integer $position_type_id
 * @property string $position_type
 */
class tblPositionType extends \yii\db\ActiveRecord
{
    public $search;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_position_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position_type'], 'required'],
            [['position_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'position_type_id' => 'Position Type ID',
            'position_type' => 'ประเภทตำแหน่ง',
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
        ($all) ? $data[""] = "ทั้งหมด" : "";
        foreach ($results as $value) {
            $data[$value->position_type_id] = $value->position_type;
        }

        return $data;
    }
}
