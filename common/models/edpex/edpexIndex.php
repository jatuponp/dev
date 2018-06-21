<?php

namespace common\models\edpex;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "edpex_index".
 *
 * @property integer $index_id
 * @property integer $item_id
 * @property string $index_year
 * @property double $score
 * @property double $pair
 *
 * @property EdpexItem $item
 */
class edpexIndex extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'edpex_index';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['item_id', 'index_year', 'score'], 'required'],
            [['item_id'], 'integer'],
            [['score', 'pair'], 'number'],
            [['index_year'], 'string', 'max' => 4],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => edpexItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'index_id' => 'Index ID',
            'item_id' => 'Item ID',
            'index_year' => 'ปีการศึกษา',
            'score' => 'ค่าคะแนนตัวชี้วัด',
            'pair' => 'ค่าคะแนนคู่เปรียบ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem() {
        return $this->hasOne(edpexItem::className(), ['item_id' => 'item_id']);
    }

    public function lists($item_id = NULL) {
        $query = parent::find()
                ->andFilterWhere([
                    'item_id' => $item_id
                ])
                ->orderBy(['index_year' => SORT_ASC]);
        //->where(['like', 'title', $search]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

    public function getScore($item_id, $year) {
        $query = edpexIndex::find()
                ->andWhere([
                    'item_id' => $item_id,
                    'index_year' => $year,
                ])
//                ->andWhere([
//                    'between',
//                    'index_year',
//                    $year-2, $year,
//                ])
                ->one();

        return $query;
    }

//    public function getSeriesColumn($item_id, $year_back) {
//        $this_y = date("Y");
//        if ($this_y < 2560) {
//            $this_y += 543;
//        }
//        $query = edpexIndex::find()
//                ->andWhere([
//                    'item_id' => $item_id,
//                ])
//                ->andWhere([
//                    'between',
//                    'index_year',
//                    $this_y - $year_back, $this_y,
//                ])
//                ->orderBy(['index_year' => SORT_ASC])
//                ->all();
//
//        //$series = array();
//        foreach ($query as $r) {
//
//            $series[] = array(
//                "name" => $r->index_year,
//                "data" => array($r->score)
//            );
//        }
//
//
//        //return $query->createCommand()->rawSql;
//        return $series;
//    }

    public function getSeries($item_id, $year_back) {
        $this_y = date("Y");
        $this_y += ($this_y < 2560) ? 543 : 0;
        
        $query = edpexIndex::find()
                ->andWhere([
                    'item_id' => $item_id,
                ])
                ->andWhere([
                    'between',
                    'index_year',
                    $this_y - $year_back, $this_y,
                ])
                ->orderBy(['index_year' => SORT_ASC])
                ->all();

        foreach ($query as $r) {
            //array_push($data, $r->score);
            $dataEdpex[] = $r->score;
            $dataPair[] = $r->pair;
        }
        $seriesEdpex[] = array(
            "name" => "ตัวชี้วัด",
            "data" => $dataEdpex
        );
        
        $seriesPair[] = array(
            "name" => "คู่เทียบ",
            "data" => $dataPair
        );
        return array_merge($seriesEdpex, $seriesPair);
    }

}
