<?php

namespace common\models;

use Yii;
use common\models\Staff;
use common\models\stdPrefix;

/**
 * This is the model class for table "tbl_memberof".
 *
 * @property int $id
 * @property string $citizen_id
 * @property string $staff_id
 * @property int $divis_id
 */
class tblMemberof extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_memberof';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['citizen_id', 'staff_id', 'divis_id'], 'required'],
            [['divis_id'], 'integer'],
            [['citizen_id'], 'string', 'max' => 13],
            [['staff_id'], 'string', 'max' => 8],
//            [['citizen_id', 'staff_id'], 'unique', 'targetAttribute' => ['citizen_id', 'staff_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'citizen_id' => 'Citizen ID',
            'staff_id' => 'รหัสบุคลากร',
            'divis_id' => 'Divis ID',
            'staffname' => 'ชื่อ-นามสกุลบุคลากร',
        ];
    }

    public function getStaff() {
        return $this->hasOne(Staff::className(), ['staff_id' => 'staff_id']);
    }

    public function getPrefix() {
        return $this->hasOne(stdPrefix::className(), ['prefixid' => 'prefixid'])
                        ->via('staff');
    }

    public function getStaffname() {
        return "{$this->staff->first_thname} {$this->staff->last_thname}";
    }
//    public function getPrefixtitle() {
//        return "{$this->prefix->prefixname}";
//    }
    
    
//    public static function getWorkmate($top, $root, $mystaff_id = NULL) {
    public static function getWorkmate($division_id, $mystaff_id = NULL) {
        
        $division = NEW tblDivision();
        
        $mydivs  = $division->getMydivision($division_id);
  
        foreach ($mydivs as $div) {
            $divs[] = $div['id'];
        }
        
        return self::find()
                ->joinWith('staff')
                ->joinWith('prefix')
                ->where([
                   'divis_id' => $divs
                ])
                ->andFilterWhere(['<>', 'tbl_staff.staff_id', $mystaff_id])
                ->all();
        
        
        //return $divs;
        
                
//        $kob = tblDivision::findOne(['id' => $top, 'root' => $root]);
//        
//        
//        
//        
////        foreach ($divs as $div) {
//             $wkms = self::find()
//                    ->joinWith('staff')
//                    ->joinWith('prefix')
//                    ->where("id BETWEEN {$kob->lft} AND {$kob->rgt}")
////                    ->andFilterWhere(['<>', 'tbl_staff.staff_id', $mystaff_id])
//                    ->all();
////        }
//        
//        //global $wkm;
//        //$parent_id = tblDivision::findOne($dept_id)->parent_id;
//
////        if ($parent_id > 0) {
////
////            $wkm[] = TblBelongto::find()
////                    ->joinWith('staff')
////                    ->joinWith('prefix')
////                    ->where(['depart_id' => $dept_id])
////                    ->andFilterWhere(['<>', 'tbl_staff.staff_id', $mystaff_id])
////                    ->all();
////            self::getWorkmate($parent_id, $mystaff_id);
////        }
//        return $wkms;
    }
    

}
