<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "por_portal".
 *
 * @property integer $id
 * @property string $title
 * @property string $properties
 * @property string $icons
 * @property string $area
 * @property string $belong
 * @property integer $hits
 * @property string $tag
 * @property integer $ordering
 * @property integer $published
 * @property string $lastUpdate
 * @property string $submitDate
 */
class porPortal extends \yii\db\ActiveRecord {
    public $search;
    public $langs;
    public $cid;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'por_portal';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'properties', 'icons', 'area', 'belong'], 'required'],
            [['urls','properties', 'area', 'belong'], 'string'],
            [['hits', 'ordering', 'published'], 'integer'],
            [['lastUpdate', 'submitDate'], 'safe'],
            [['title', 'tag'], 'string', 'max' => 250],
            [['icons'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'ชื่อระบบ',
            'urls' => 'เชื่อมโยง',
            'properties' => 'คุณสมบัติ',
            'icons' => 'Icons',
            'area' => 'ประเภทระบบ',
            'belong' => 'หน่วยงาน',
            'hits' => 'Hits',
            'tag' => 'Tag',
            'ordering' => 'Ordering',
            'published' => 'Published',
            'lastUpdate' => 'Last Update',
            'submitDate' => 'Submit Date',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->hits = 0;
                $this->published = 0;
                $now = date('Y-m-d H:i:s');
                $this->submitDate = $now;
                $this->lastUpdate = $now;
            }
            return true;
        }
        return false;
    }

    public function search() {
        $query = porPortal::find();

        if ($this->area)
            $query->andWhere(['area' => $this->area]);
        if ($this->belong)
            $query->andWhere(['belong' => $this->belong]);
        
        $query->orderBy('area, ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
    
    public function listPortal($area='manage'){
        $result = porPortal::find()
                ->where(['area'=>$area])
                ->orderBy('ordering')
                ->all();
        return $result;
    }
    
    public function searchPortal($search=''){
        $search = $_POST['porPortal']['search'];
        $result = porPortal::find()
                ->where('title LIKE :s', [':s' => "%$search%"])
                ->orderBy('ordering')
                ->all();
        return $result;
    }

    public function orderMax() {
        $max = $this->find()->where(['area' => $this->area])->max('ordering');
        return $max;
    }

    public function orderMin() {
        $min = $this->find()->where(['area' => $this->area])->min('ordering');
        return $min;
    }
    
    public function getPortalType($area=null){
        $arr = array('manage'=>'ด้านการบริหารจัดการ','learning'=>'ด้านการจัดการเรียนการสอน','finance'=>'ด้านการเงิน','research'=>'ด้านการวิจัย');
        return $arr[$area];
    }

}
