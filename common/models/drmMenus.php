<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_menus".
 *
 * @property integer $id
 * @property string $names
 * @property integer $parent_id
 * @property string $urls
 * @property string $langs
 * @property integer $published
 * @property integer $ordering
 */
class drmMenus extends ActiveRecord {

    public $content;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'drm_menus';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['names'], 'required'],
            [['parent_id','article_id', 'published', 'ordering'], 'integer'],
            [['names', 'icons', 'urls'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'names' => 'ชื่อเมนู',
            'parent_id' => 'ภายใต้เมนู',
            'urls' => 'ที่อยู่ URL',
            'content' => 'เชื่อมโยงเนื้อหา',
            'article_id' => 'เชื่อมโยงเนื้อหา',
            'icons' => 'Icon ของเมนู',
            'published' => 'Published',
            'ordering' => 'Ordering',
        ];
    }

//    public function beforeSave($insert) {
//        if (parent::beforeSave($insert)) {
//            if ($this->isNewRecord) {
//                $this->langs = 'thai';
//            }
//            return true;
//        }
//        return false;
//    }

    public function search() {
        $query = drmMenus::find()->orderBy('ordering');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    private function orderMax($parent_id = null) {
        $query = drmMenus::find()
                ->where(['parent_id' => $parent_id])
                ->max('ordering');
        return $query;
    }

    private function orderMin($parent_id = null) {
        $query = drmMenus::find()
                ->where(['parent_id' => $parent_id])
                ->min('ordering');

        return $query;
    }

    public function makeDropDown($langs = null) {
        global $data;
        $data = array();
        $data['0'] = '-- Top Level --';
        $parents = drmMenus::find()
                ->where(['parent_id' => 0])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->names;
            drmMenus::subDropDown($parent->id);
        }

        return $data;
    }

    public function subDropDown($parent, $space = '|---') {
        global $data;

        $children = drmMenus::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data[$child->id] = $space . ' ' . $child->names;
            drmMenus::subDropDown($child->id, $space . '---');
        }
    }

    public function listCategory() {
        global $arr;
        $arr = array();
        $parents = drmMenus::find()
                ->where(['parent_id' => 0])
                ->orderBy('ordering')
                ->all()
        ;
        foreach ($parents as $parent) {
            $data = array();
            $data['id'] = $parent->id;
            $data['names'] = $parent->names;
            $data['ordering'] = $parent->ordering;
            $data['published'] = $parent->published;
            $data['min'] = $this->orderMin($parent->parent_id);
            $data['max'] = $this->orderMax($parent->parent_id);
            $arr[] = $data;
            
            drmMenus::listCategorySub($parent->id);
        }

        return new ArrayDataProvider([
            'allModels' => $arr,
            'key' => 'id',
            'pagination' => [
                'pageSize' => 20,
        ]]);
    }

    public function listCategorySub($parent, $space = '|---') {
        global $arr;

        $children = drmMenus::find()
                ->where(['parent_id' => $parent])
                ->orderBy('ordering')
                ->all();
        foreach ($children as $child) {
            $data = array();
            $data['id'] = $child->id;
            $data['names'] = $space . ' ' . $child->names;
            $data['ordering'] = $child->ordering;
            $data['published'] = $child->published;
            $data['min'] = $this->orderMin($child->parent_id);
            $data['max'] = $this->orderMax($child->parent_id);
            $arr[] = $data;
            drmMenus::listCategorySub($child->id, $space . '---');
        }
    }

    public function listMenus($parent, $level, $sub = null) {
        $connection = \Yii::$app->db;
        $sql = "SELECT a.id, a.names, a.icons, a.article_id, a.urls, Deriv1.Count FROM `drm_menus` a  "
                . "LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS Count FROM `drm_menus` GROUP BY parent_id) "
                . "Deriv1 ON a.id = Deriv1.parent_id WHERE a.parent_id=" . $parent . " AND a.published=1 "
                . "ORDER BY a.ordering";
        ;
        $command = $connection->createCommand($sql);
        $reader = $command->query();
        $data = array();
        foreach ($reader as $r) {
            if ($r['Count'] > 0) {
                $data['label'] = '<i class="glyphicon glyphicon-' . $r['icons'] . '"></i> ' . $r['names'];
                $data['url'] = (($r['article_id']) ? ['default/view', 'id' => $r['article_id']] : \yii\helpers\Url::to([$r['urls']]));
                $data['items'] = drmMenus::listMenus($r['id'], $level + 1);
            } else {
                $data['label'] = '<i class="glyphicon glyphicon-' . $r['icons'] . '"></i> ' . $r['names'];
                $data['url'] = ((!$r['urls']) ? \yii\helpers\Url::to(['default/view', 'id' => $r['article_id']]) : \yii\helpers\Url::to([$r['urls']]));
                unset($data['items']);
            }

            $items[] = $data;
        }

        return $items;
    }

}
