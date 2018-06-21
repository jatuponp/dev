<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_article".
 *
 * @property integer $id
 * @property integer $cid
 * @property string $title
 * @property string $fulltexts
 * @property integer $ordering
 * @property integer $published
 * @property string $startdate
 * @property string $finishdate
 * @property string $submitdate
 * @property string $applydate
 * @property string $langs
 * @property string $pins
 * @property integer $frontpage
 */
class drmArticle extends \yii\db\ActiveRecord {

    public $search;
    public $upload_files;
    public $langs;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'drm_article';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cid', 'title'], 'required'],
            [['cid', 'ordering', 'published'], 'integer'],
            [['fulltexts', 'search'], 'string'],
            [['startdate', 'finishdate', 'submitdate', 'applydate'], 'safe'],
            [['title'], 'string', 'max' => 300],
            [['pins'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cid' => 'หมวดหมู่บทความ',
            'title' => 'ชื่อเรื่อง',
            'fulltexts' => 'Fulltexts',
            'ordering' => 'Ordering',
            'published' => 'การเผยแพร่',
            'startdate' => 'เริ่มวันที่',
            'finishdate' => 'สิ้นสุดวันที่',
            'submitdate' => 'Submitdate',
            'applydate' => 'Applydate',
            'pins' => 'Pins',
            'upload_files' => 'ภาพประกอบ'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
                if ($auth['Editor']->roleName == 'Editor') {
                    $this->published = 1;
                }
                if (!$this->startdate) {
                    $this->startdate = $now;
                }
                if(!$this->finishdate){
                    $this->finishdate = '0000-00-00';
                }
                $this->submitdate = $now;
                $this->applydate = $now;
                $this->createBy = \Yii::$app->user->id;
            } else {
                $this->applydate = $now;
            }
            return true;
        }
        return false;
    }
    
    public function listContent() {
        $data = array();
        $parents = drmArticle::find()
                ->where(['cid' => 0])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->title;
        }

        return $data;
    }

    public function getCatName($cid) {
        if($cid == 0){
            $title = 'เนื้อหาเว็บไซต์';
        }else{
            $title = 'ข่าวประชาสัมพันธ์';
        }
        return $title;
    }

    public function search() {
        $search = $this->search; //($_POST['Article']['search'])? $_POST['Article']['search']:($_REQUEST['search'])? $_REQUEST['search']:'';
        //$langs = $this->langs;
        $cid = $this->cid;
        $query = drmArticle::find()->where('title LIKE :s', [':s' => "%$search%"]);
        if ($cid)
            $query->andWhere(['cid' => $cid]);

        $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
        if ($auth['Editor']->roleName == 'Editor' || $auth['Publisher']->roleName == 'Publisher') {
            $q = User::findAll(['usergroup' => \Yii::$app->user->id]);
            foreach ($q as $r) {
                $gid[] = $r->id;
            }
            $query->andWhere('createBy IN (' . implode(',', $gid) . ')');
        }

        $query->orderBy('cid, ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    public function news($cid = null) {
        $now = date('Y-m-d');
        $query = drmArticle::find()->where(['cid' => $cid, 'published' => 1]);
        $query->andWhere(['OR', 'startdate = ' . "'0000-00-00'", "startdate <='" . $now . "'"]);
        $query->andWhere(['OR', 'finishdate = ' . "'0000-00-00'", "finishdate >='" . $now . "'"]);

        $query->orderBy('ordering ASC');
        $result = $query->all();

        return $result;
    }

    public function checkOwner($id) {
        $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
        //print_r($auth);
        $access = false;
        if ($auth['Editor']->roleName == 'Editor') {
            $model = drmArticle::findOne($id);
            if ($model->createBy == \Yii::$app->user->id) {
                $access = true;
            }
        } else {
            $access = true;
        }
        return $access;
    }

    public function orderMax($langs = null, $cid = null) {
        $query = drmArticle::find()->where(['cid' => $cid])->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        $query = drmArticle::find()->where(['cid' => $cid])->min('ordering');
        return $query;
    }
    
    public function makeDropDown($langs = null) {
        $data = array();
        $data['0'] = 'เนื้อหาเว้บไซต์';
        $data['1'] = 'ข่าวประชาสัมพันธ์';

        return $data;
    }

}
