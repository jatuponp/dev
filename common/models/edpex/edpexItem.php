<?php

namespace common\models\edpex;

use Yii;
use \yii\data\ActiveDataProvider;

/**
 * This is the model class for table "edpex_item".
 *
 * @property integer $item_id
 * @property string $title
 * @property string $seq
 * @property string $draft
 * @property integer $ordering
 * @property integer $parent_id
 */
class edpexItem extends \yii\db\ActiveRecord {

    public $year_back;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'edpex_item';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'parent_id'], 'required'],
            [['ordering', 'parent_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['seq'], 'string', 'max' => 10],
            [['draft'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'item_id' => 'Item ID',
            'title' => 'ตัวชี้วัด',
            'seq' => 'ที่',
            'draft' => 'โครงร่าง / กระบวนการ',
            'ordering' => 'Ordering',
            'parent_id' => 'Parent ID',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            // Place your custom code here
            if ($this->isNewRecord) {
                $maxordering = parent::find()->where(['parent_id' => $this->parent_id])->orderBy(['ordering' => SORT_DESC])->one()->ordering;

                $this->ordering = $maxordering + 1;
            }
            return true;
        } else {

            return false;
        }
    }

    public static function getParentname($item_id) {
        $parent_id = parent::find()->where(['item_id' => $item_id])->one()->parent_id;
        return edpexItem::find()->where(['item_id' => $parent_id])->one()->title;
    }

    public static function getTitlename($item_id) {
        return parent::find()->where(['item_id' => $item_id])->one()->title;
    }

    public function lists($parent_id = NULL) {
        $query = parent::find()
                ->andFilterWhere([
                    'parent_id' => $parent_id,
                    'published' => 1
                ])
                ->orderBy([
            'ordering' => SORT_ASC,
                //'seq' => SORT_ASC,
        ]);
        //->where(['like', 'title', $search]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

    public function listsItem($parent_id) {
        $query = edpexItem::find()
                ->andWhere([
                    'parent_id' => $parent_id,
                    'published' => 1
                ])
                ->orderBy([
                    'ordering' => SORT_ASC,
                        //'seq' => SORT_ASC
                ])
                ->all();

        return $query;
    }

    public static function makeDD($child = true) {
        global $data;
        $data = array();
        $data[1] = '-- Top Level --';

        $parents = edpexItem::find()
                ->where(['parent_id' => 1])
                ->andWhere([
                    'published' => 1
                ])
                ->all();

        foreach ($parents as $parent) {
            $data[$parent->item_id] = $parent->title;
            if ($child) {
                edpexItem::subDD($parent->item_id);
            }
        }

        return $data;
    }

    public static function subDD($parent, $space = '|---') {
        global $data;

        $children = edpexItem::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data[$child->item_id] = $space . ' ' . $child->title;
            edpexItem::subDD($child->item_id, $space . '-----');
        }
    }

    public function listsItem1() {
        global $arr;
        $arr = array();

        $parent = ($this->parent_id) ? $this->parent_id : '0';
        $parents = Department::find()
                ->where(['parent_id' => $parent])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data = array();
            $data['id'] = $parent->id;
            $data['title'] = $parent->title;
            $arr[] = $data;
            Department::listCategorySub($parent->id);
        }

        return new ArrayDataProvider([
            'allModels' => $arr,
            'key' => 'id',
            'pagination' => [
                'pageSize' => 7,
        ]]);
    }

    public function orderMin($parent_id) {
        return parent::find()->where(['parent_id' => $parent_id])->orderBy(['ordering' => SORT_ASC])->one()->ordering;
    }

    public function orderMax($parent_id) {
        return parent::find()->where(['parent_id' => $parent_id])->orderBy(['ordering' => SORT_DESC])->one()->ordering;
    }

}
