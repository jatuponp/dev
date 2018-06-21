<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_department".
 *
 * @property integer $id
 * @property string $dname
 * @property integer $parent
 */
class Department extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_department';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title'], 'required'],
            [['parent_id'], 'integer'],
            [['title'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'ชื่อหน่วยงาน',
            'parent_id' => 'ภายใต้หน่วยงาน',
        ];
    }

    public function makeDropDown() {
        global $data;
        $data = array();
        $data['0'] = '-- Top Level --';        
        $parents = Department::find()
                ->where(['parent_id' => 0])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->title;
            Department::subDropDown($parent->id);
        }

        return $data;
    }

    public function subDropDown($parent, $space = '|---') {
        global $data;

        $children = Department::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data[$child->id] = $space . ' ' . $child->title;
            Department::subDropDown($child->id, $space . '-----');
        }
    }

    public function listCategory($langs = null) {
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

    public function listCategorySub($parent, $space = '|---') {
        global $arr;

        $children = Department::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data = array();
            $data['id'] = $child->id;
            $data['title'] = $space . ' ' . $child->title;
            $arr[] = $data;
            Department::listCategorySub($child->id, $space . '---');
        }
    }

}
