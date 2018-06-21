<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_slider".
 *
 * @property integer $id
 * @property string $slider_Url
 * @property string $link_Url
 * @property integer $target
 * @property integer $published
 * @property integer $ordering
 * @property string $submitdate
 */
class drmSlider extends \yii\db\ActiveRecord {

    public $upload_files;
    public $cid;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'drm_slider';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['slider_Url'], 'required'],
            [['target', 'published', 'ordering'], 'integer'],
            [['submitdate'], 'safe'],
            [['slider_Url', 'link_Url'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'slider_Url' => 'Slider  Url',
            'link_Url' => 'Link  Url',
            'target' => 'Target',
            'published' => 'Published',
            'ordering' => 'Ordering',
            'submitdate' => 'Submitdate',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 0;
                $this->submitdate = $now;
                if (!$this->langs) {
                    $this->langs = 'thai';
                }
            }
            return true;
        }
        return false;
    }

    public function search() {
        $langs = $this->langs;
        $query = Slider::find();
        $query->where(['langs' => $langs]);
        $query->orderBy('ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $dataProvider;
    }

    public function slider() {

        $query = Slider::find();
        $query->where(['langs' => 'thai', 'published' => 0]);
        $query->orderBy('ordering ASC');
        $result = $query->all();

        return $result;
    }

    public function orderMax($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Slider::find()->where(['langs' => $langs])->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Slider::find()->where(['langs' => $langs])->min('ordering');
        return $query;
    }

}
