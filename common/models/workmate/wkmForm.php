<?php

namespace common\models\workmate;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "wkm_form".
 *
 * @property string $form_id
 * @property integer $division_id
 * @property string $form_year
 * @property integer $form_times
 * @property string $start
 * @property string $stop
 * @property integer $isactive
 *
 * @property tblDivision $division
 * @property WkmItem[] $wkmItems
 */
class wkmForm extends \yii\db\ActiveRecord {

    public $search, $div;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wkm_form';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['division_id', 'form_year'], 'required'],
            [['division_id', 'form_times', 'isactive'], 'integer'],
            [['start', 'stop'], 'safe'],
            [['form_year'], 'string', 'max' => 4],
            [['form_times'], 'unique', 'targetAttribute' => ['division_id', 'form_year', 'form_times'], 'message' => 'มีแบบประเมินของปีนี้แล้ว (This form has already been taken.)'],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\tblDivision::className(), 'targetAttribute' => ['division_id' => 'id']],
            [['start'], 'compare', 'compareAttribute' => 'stop', 'operator' => '<=', 'skipOnEmpty' => true],
            [['stop'], 'compare', 'compareAttribute' => 'start', 'operator' => '>='],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'form_id' => 'Form ID',
            'division_id' => 'คณะ / หน่วยงาน',
            'form_year' => 'ปีประเมิน',
            'form_times' => 'การประเมินครั้งที่',
            'start' => 'เริ่มประเมิน',
            'stop' => 'สิ้นสุดประเมิน',
            'isactive' => 'Isactive',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision() {
        return $this->hasOne(\common\models\tblDivision::className(), ['id' => 'division_id']);
    }

    public function getDivisionTitle() {
        return $this->division->title;
    }

    /**
     * 
     * @param string $search
     * @return ActiveDataProvider
     */
    public static function lists($search = NULL, $div_id = NULL) {

        $div_id = ($div_id == 0) ? NULL : $div_id;
        $query = self::find()
                ->joinWith('division')
                ->filterWhere(['like', 'title', $search])
                ->filterWhere(['division_id' => $div_id])
                ->orderBy([
            'form_year' => SORT_DESC,
            'title' => SORT_ASC,
            'form_id' => SORT_ASC,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

//        $dataProvider = $query;
        return $dataProvider;
    }

}
