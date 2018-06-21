<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_department".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent_id
 *
 * @property AriTeacher[] $ariTeachers
 * @property RscAuthorConference[] $rscAuthorConferences
 * @property RscAuthorJournal[] $rscAuthorJournals
 * @property RscResearcher[] $rscResearchers
 * @property Belongto[] $belongtos
 * @property Phone[] $phones
 */
class tblDepartment extends \yii\db\ActiveRecord {

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
            'title' => 'Title',
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBelongtos() {
        return $this->hasMany(Belongto::className(), ['depart_id' => 'id']);
    }

    public function getStaff() {
        return $this->hasOne(Staff::className(), ['citizen_id' => 'citizen_id']);
    }
    
    public function getPrefix() {
        return $this->hasOne(stdPrefix::className(), ['prefix_id' => 'prefix_id'])->via('staff');
    }

    public static function makeDD($child = true) {
        global $data;
        $data = array();
        $data['0'] = '-- Top Level --';
//        if ($langs == null) {
//            $langs = ($_POST['Categories']['langs']) ? $_POST['Categories']['langs'] : ($_REQUEST['langs']) ? $_REQUEST['langs'] : 'thai';
//        }
        $parents = tblDepartment::find()
                ->where(['parent_id' => 0])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->title;
            if ($child) {
                self::subDropDown($parent->id);
            }
        }

        return $data;
    }

    public static function subDropDown($parent, $space = '|---') {
        global $data;

        $children = self::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data[$child->id] = $space . ' ' . $child->title;
            self::subDropDown($child->id, $space . '-----');
        }
    }

    public static function getName($id) {
        return self::findOne($id);
    }

    public function getLastdeptid($deptID) {
        global $data1;
        $data1 = array();
        $parents = tblDepartment::find()
                ->where(['parent_id' => $deptID])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data1[] = $parent->id;
            tblDepartment::lastSub($parent->id);
        }

        return $data1;
    }

    public static function lastSub($parent) {
        global $data1;

        $children = tblDepartment::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data1[] = $child->id;
            tblDepartment::lastSub($child->id);
        }
    }

    public function getStaff_In_Dept($deptid) {
        $parent = array();
        $parent[] = $deptid;
        $depts = tblDepartment::getLastdeptid($deptid);
        $dept = array_merge($depts, $parent);
        $staffs = TblBelongto::find()->select('citizen_id')->where(['IN', "depart_id", $dept])->all();

        $staff = array();
        foreach ($staffs as $s) {
            $staff[] = $s->citizen_id;
        }

        return $staff;
    }

    public static function getWorkmate($dept_id, $mystaff_id = NULL) {
        global $wkm;
        $parent_id = self::findOne($dept_id)->parent_id;

        if ($parent_id > 0) {

            $wkm[] = TblBelongto::find()
                    ->joinWith('staff')
                    ->joinWith('prefix')
                    ->where(['depart_id' => $dept_id])
                    ->andFilterWhere(['<>', 'tbl_staff.staff_id', $mystaff_id])
                    ->all();
            self::getWorkmate($parent_id, $mystaff_id);
        }
        return $wkm;
    }

    public static function getRootDept($deptid) {

        $parent_id = self::findOne(['id' => $deptid])->parent_id;
        if ($parent_id > 0) {
            $deptid = self::getRootDept($parent_id);
        }

        return $deptid;
    }

}
