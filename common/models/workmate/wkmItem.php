<?php

namespace common\models\workmate;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "wkm_item".
 *
 * @property string $item_id
 * @property string $form_id
 * @property string $title_th
 * @property string $title_en
 * @property string $sorting
 * @property string $parent_id
 *
 * @property WkmForm $form
 */
class wkmItem extends \yii\db\ActiveRecord {
//    public $dept, $year;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wkm_item';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['form_id', 'title_th', 'parent_id'], 'required'],
            [['form_id', 'sorting', 'parent_id'], 'integer'],
            [['title_th', 'title_en'], 'string', 'max' => 255],
            [['form_id'], 'exist', 'skipOnError' => true, 'targetClass' => wkmForm::className(), 'targetAttribute' => ['form_id' => 'form_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'item_id' => 'Item ID',
            'form_id' => 'Form ID',
            'title_th' => 'หัวข้อประเมิน',
            'title_en' => 'Title En',
            'sorting' => 'จัดลำดับ',
            'parent_id' => 'หัวข้อหลัก',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm() {
        return $this->hasOne(wkmForm::className(), ['form_id' => 'form_id']);
    }

    /**
     * 
     * @param string $search
     * @return ActiveDataProvider
     */
    public static function lists($form_id) { //$dept = NULL, $year = NULL
        $dept = ($dept == 0) ? NULL : $dept;
        $query = parent::find()
                ->joinWith('form')
//                ->filterWhere(['like', 'title', $search])
                ->Where([
//            'dept_id' => $dept,
//            'form_year' => $year,
            'wkm_form.form_id' => $form_id,
            'parent_id' => 0,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => 5,
//            ],
        ]);

        return $dataProvider;
    }

    public static function subLists($dept = NULL, $year = NULL, $parent_id = NULL) {

        $dept = ($dept == 0) ? NULL : $dept;
        $query = parent::find()
                ->joinWith('form')
//                ->filterWhere(['like', 'title', $search])
                ->filterWhere([
            'division_id' => $dept,
            'form_year' => $year,
            'parent_id' => $parent_id,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => 5,
//            ],
        ]);

        return $dataProvider;
    }

    public static function makeDD($child = true, $form_id = NULL) {
        global $data;
        $data = array();
        $data[0] = '-- Top Level --';

        $parents = self::find()
                ->where(['parent_id' => 0])
//                ->andWhere([
//                    'published' => 1
//                ])
                ->andfilterWhere(['form_id' => $form_id])
                ->all();

        foreach ($parents as $parent) {
            $data[$parent->item_id] = $parent->title_th;
            if ($child) {
                self::subDD($parent->item_id, $form_id);
            }
        }

        return $data;
    }

    public static function subDD($parent, $form_id = NULL, $space = '|---') {
        global $data;

        $children = self::find()->where(['parent_id' => $parent])->andfilterWhere(['form_id' => $form_id])->all();
        foreach ($children as $child) {
            $data[$child->item_id] = $space . ' ' . $child->title_th;
            self::subDD($child->item_id, $space . '-----');
        }
    }

}
