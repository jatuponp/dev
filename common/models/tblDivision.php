<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_division".
 *
 * @property string $description
 */
class tblDivision extends \kartik\tree\models\Tree {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_division';
    }

    public function rules() {

        $rules = parent::rules();

        //$rules[] = [['staff_id'], 'required'];
        $rules[] = ['description', 'safe'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'รหัส',
            'title' => 'ชื่อคณะ / หน่วยงาน',
        ];
    }

    /**
     * Override isDisabled method if you need as shown in the  
     * example below. You can override similarly other methods
     * like isActive, isMovable etc.
     */
//    public function isDisabled()
//    {
//        if (Yii::$app->user->username !== 'admin') {
//            return true;
//        }
//        return parent::isDisabled();
//    }

    public static function makeDD($all = true) {
        if ($all)
            $data[] = 'ทั้งหมด';

        $parents = self::find()
                ->where(['lvl' => 0])
                ->all();
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->title;
//            if ($child) {
//                self::subDropDown($parent->id);
//            }
        }

        return $data;
    }

    /**
     * หาหน่วยงานบนสุดแต่ไม่ใช่ root
     * 
     */

    public function getTopDivision($division_id) {

        $row = self::findOne(['id' => $division_id]);

        return self::find()
                        ->where("lvl = 1 AND lft <= {$row->lft} AND rgt >= {$row->rgt} AND root = {$row->root}")
                        ->one()->id;
    }

    /**
     * หา root
     */

    public function getRoot($division_id) {

        return self::findOne(['id' => $division_id])->root;
    }

    /**
     * 
     * หาหน่วยงานอื่นๆ ในงานเดียวกัน
     * 
     * @return array division_id
     */
    public function getMydivision($division_id) {

        $top = $this->getTopDivision($division_id);

        $div = self::findOne(['id' => $top]);

        return self::find()
                        ->select(['id'])
                        ->where([
                            'BETWEEN', 'lft', $div->lft, $div->rgt
                        ])
                        ->andWhere(['root' => $div->root])
                        ->asArray()
                        ->all();
    }
    
    /**
     * 
     * @return integer division_id
     */
    
    public function getDivisionID() {
        $staff_id = Staff::getStaffID();
        
        return tblMemberof::find()->where(['staff_id' => $staff_id])->one()->divis_id;
    }

}
