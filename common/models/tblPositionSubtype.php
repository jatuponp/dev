<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_position_subtype".
 *
 * @property integer $position_subtype_id
 * @property string $position_subtype
 */
class tblPositionSubtype extends \yii\db\ActiveRecord
{
    public $search;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_position_subtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position_subtype'], 'required'],
            [['position_subtype'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'position_subtype_id' => 'Position Subtype ID',
            'position_subtype' => 'แหล่งเงินจ้าง',
        ];
    }
    
    /**
     * 
     * @param string $search
     * @return ActiveDataProvider
     */
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
    
    /**
     * 
     * @param boolean $all
     * @return array
     */
    public static function makeDD($all = false) {

        $results = parent::find()->all();

        $data = array();
        ($all) ? $data[0] = "สายปฏิบัติงานทั้งหมด" : "";
        foreach ($results as $value) {
            $data[$value->position_subtype_id] = $value->position_subtype;
        }

        return $data;
    }
    
}
