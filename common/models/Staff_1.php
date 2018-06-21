<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_staff".
 *
 * @property string $staff_id
 * @property string $citizen_id
 * @property integer $prefixid
 * @property string $first_thname
 * @property string $last_thname
 * @property string $first_enname
 * @property string $last_enname
 * @property string $nickname
 * @property string $sex
 * @property string $blood
 * @property string $date_ofbirth
 * @property string $address
 * @property integer $province_id
 * @property string $mobile
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $email_anothor
 * @property string $homepage
 * @property string $religion
 * @property string $nation
 * @property integer $country_id
 * @property string $position_board
 * @property string $position_acade
 * @property string $picture
 * @property integer $update_by
 * @property string $update_date
 *
 * @property AriSubmit[] $ariSubmits
 * @property AssQa[] $assQas
 * @property CmsOccupy[] $cmsOccupies
 * @property Belongto[] $belongtos
 * @property StaffHistory[] $staffHistories
 * @property StaffPosition[] $staffPositions
 */
class Staff extends \yii\db\ActiveRecord {

    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['citizen_id', 'prefixid', 'country_id'], 'required'],
            [['prefixid', 'province_id', 'country_id', 'update_by'], 'integer'],
            [['sex', 'blood', 'address'], 'string'],
            [['date_ofbirth', 'update_date'], 'safe'],
            [['staff_id'], 'string', 'max' => 6],
            [['citizen_id'], 'string', 'max' => 13],
            [['first_thname', 'last_thname', 'first_enname', 'last_enname', 'nickname', 'nation'], 'string', 'max' => 100],
            [['mobile', 'phone', 'fax', 'religion'], 'string', 'max' => 50],
            [['email', 'email_anothor', 'homepage', 'position_board', 'position_acade'], 'string', 'max' => 200],
            [['picture'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_id' => 'รหัสพนักงาน',
            'citizen_id' => 'เลขประจำตัวประชาชน',
            'prefixid' => 'คำนำหน้า',
            'first_thname' => 'ชื่อ (ไทย)',
            'last_thname' => 'นามสกุล (ไทย)',
            'first_enname' => 'ชื่อ (อังกฤษ)',
            'last_enname' => 'นามสกุล (อังกฤษ)',
            'nickname' => 'ชื่อเล่น',
            'sex' => 'เพศ',
            'blood' => 'กลุ่มเลือด',
            'date_ofbirth' => 'วันเกิด',
            'address' => 'ที่อยู่',
            'province_id' => 'จังหวัด',
            'mobile' => 'มือถือ',
            'phone' => 'โทรศัพท์',
            'fax' => 'Fax',
            'email' => 'Email',
            'email_anothor' => 'Email Anothor',
            'homepage' => 'Homepage',
            'religion' => 'ศาสนา',
            'nation' => 'สัญชาติ',
            'country_id' => 'ประเทศ',
            'position_board' => 'Position Board',
            'position_acade' => 'Position Acade',
            'picture' => 'Picture',
            'update_by' => 'Update By',
            'update_date' => 'Update Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBelongtos() {
        return $this->hasMany(Belongto::className(), ['citizen_id' => 'citizen_id']);
    }

    public function search() {
        $query = Staff::find();

//        $query->where('request_status != "close"');
//        $query->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

    public function getPrefix() {
        return $this->hasOne(stdPrefix::className(), ['prefixid' => 'prefixid']);
    }

    public function getPrefixname() {
        return $this->prefix->prefixname;
    }

    public function getFullname() {
        return $this->prefix->prefixname . $this->first_thname . ' ' . $this->last_thname;
    }

    public function getStaffacademic() {
        return $this->hasOne(tblStaffAcademic::className(), ['citizen_id' => 'citizen_id']);
    }

    public function getAcade() {
        return $this->hasOne(tblAcademic::className(), ['academic_id' => 'academic_id']);
    }

    /**
     * แสดงชื่อพนักงาน จากผู้ใช้ที่เข้าระบบแล้ว 
     *
     * @return string ชื่อเต็ม
     */
    public static function getStaffName() {
        $id = \Yii::$app->user->id;
        $us = User::findOne($id);
        $query = Staff::findOne(['citizen_id' => $us->citizen_id]);
        $prefix = stdPrefix::findOne(['prefixid' => $query->prefixid]);
        return $prefix->prefixname . $query->first_thname . '   ' . $query->last_thname;
    }

    /**
     * 
     * @param type $staff_id
     * @return string ชื่อเต็ม
     */
    
    public static function getNameByStaffID($staff_id) {
        $result = self::find()->joinWith('prefix')
                ->where(['staff_id' => $staff_id])
                ->one();
                
        return $result->prefix->prefixname. $result->first_thname . ' ' . $result->last_thname ;
    }
    /*
     * Written by เอ๋
     * 
     * @param string $citizen_id หมายเลขบัตรประชาชน
     * @param boolean $acade true คำนำชื่อเป็นตำแหน่งวิชาการ
     * @return string ชื่อเต็ม
     */
    
    public static function getStaffNameById($citizen_id = null, $acade = false) {
        $query = Staff::findOne(['citizen_id' => $citizen_id]);
        $prefix = stdPrefix::findOne(['prefixid' => $query->prefixid]);
        if ($acade) {
            if ($query->prefixid2) {
                $prefix = stdPrefix::findOne(['prefixid' => $query->prefixid2]);
            }
            if ($query->staffacademic->academic_id) {
                $aca_query = tblAcademic::findOne(['academic_id' => $query->staffacademic->academic_id]);
                $aca = $aca_query->title;
            }
        }
        return (($aca) ? $aca . " " : "") . $prefix->prefixname . $query->first_thname . ' ' . $query->last_thname;
    }
    
    /**
     * Written by KOB
     * 
     * 
     * @param string $citizen_id 
     * @return string รหัสพนักงาน
     */
    public static function getStaffID($citizen_id = NULL) {
        $citizen_id = ($citizen_id) ? $citizen_id: \Yii::$app->user->identity->citizen_id;
        return self::find()->where(['citizen_id' => $citizen_id])->one()->staff_id;
    }
    

    /*
     * Written by KOB
     * 
     * @param string $staff_id 
     * @return string ชื่อเต็ม
     */

//    public static function getStaffNameByStaffId($staff_id) {
//        $query = NEW Query();
//        $query->select(["CONCAT(prefixname,first_thname, ' ', last_thname) AS fullname"])
//                ->from("tbl_staff_position")
//                ->innerJoin("tbl_staff", "tbl_staff.citizen_id = tbl_staff_position.citizen_id")
//                ->innerJoin("std_prefix", "std_prefix.prefixid = tbl_staff.prefixid")
//                ->filterWhere(["tbl_staff_position.staff_id" => $staff_id]);
//
//        $result = $query->createCommand()->queryOne();
//        return $result["fullname"];
//    }

    public static function getStaffProfile($citizen_id = null) {
        //$query = Staff::findOne(['citizen_id' => $citizen_id]);
        //return $query->prefix_thname. $query->first_thname . ' ' . $query->last_thname;

        $query = NEW Query();
        $query->select([
                    "CONCAT('อาจารย์', first_thname, '   ', last_thname) AS fullname",
                    "title AS faculty"
                ])
                ->from("tbl_staff")
                ->innerJoin("tbl_belongto", "tbl_belongto.citizen_id = tbl_staff.citizen_id")
                ->innerJoin("tbl_department", "tbl_department.id = tbl_belongto.depart_id")
                ->where([
                    "tbl_staff.citizen_id" => $citizen_id
        ]);

        $result = $query->createCommand()->queryOne();

        return $result;
    }
    
    
//    public static function profile($citizen_id) {
//        $staff_id = self::getStaffID($citizen_id);
//        
//        $profile = self::find()
//                ->joinWith("prefix")
//                ->where([
//                    'tbl_staff.citizen_id' => $citizen_id
//                ])->all();
//        return $profile;
//    }
//
//    public function getProfile() {
//        return self::profile(\Yii::$app->user->identity->citizen_id);
//    }
    

    public function getStaffCitizen() {
        $id = \Yii::$app->user->id;
        $us = User::findOne($id);
        return $us->citizen_id;
    }

    /**
     * Written by KOB
     * 
     * @param string $name Button name as it's written in template
     * @return yii\data\ActiveDataProvider
     */
    public function listStaff($search, $dept = NULL, $sstatus = NULL, $positiongroup = NULL) {

        $query = NEW Query();
        $query->select([
                    "CONCAT(prefixname,first_thname, ' ', last_thname) AS fullname",
                    "tbl_staff.citizen_id", "tbl_staff_position.staff_id", "position", "date_work",
                    "position_type", "mpw_number.position_no", "tbl_staff_status.title",
                    "tbl_department.title as dept"
                ])
                ->from("tbl_staff")
                ->innerJoin("std_prefix", "std_prefix.prefixid = tbl_staff.prefixid")
                ->leftJoin("tbl_staff_position", "tbl_staff.citizen_id = tbl_staff_position.citizen_id")
                ->leftJoin("tbl_position", "tbl_staff_position.position_id = tbl_position.position_id")
                ->leftJoin("tbl_position_type", "tbl_staff_position.position_type_id = tbl_position_type.position_type_id")
                ->leftJoin("mpw_number", "tbl_staff_position.mpw_id = mpw_number.mpw_id")
                ->leftJoin("vw_lastdatestatus_staff", "tbl_staff_position.citizen_id = vw_lastdatestatus_staff.citizen_id and tbl_staff_position.staff_id = vw_lastdatestatus_staff.staff_id")
                ->leftJoin("tbl_staff_history", "vw_lastdatestatus_staff.citizen_id = tbl_staff_history.citizen_id and vw_lastdatestatus_staff.staff_id = tbl_staff_history.staff_id and lastupdate = status_date")
                ->leftJoin("tbl_belongto", "tbl_staff.citizen_id = tbl_belongto.citizen_id")
                ->leftJoin("tbl_department", "tbl_belongto.depart_id = tbl_department.id")
                ->leftJoin("tbl_staff_status", "tbl_staff_history.status_id = tbl_staff_status.status_id")
                ->andFilterWhere(["tbl_staff.citizen_id" => $search])
                ->orFilterWhere(["like", "first_thname", $search])
                ->orFilterWhere(["like", "last_thname", $search])
                ->orderBy([
                    "first_thname" => SORT_ASC,
                    "last_thname" => SORT_ASC,
        ]);

        if ($dept > 0) {
            $query->andFilterWhere(["tbl_department.id" => $dept])
                    ->orFilterWhere(["tbl_department.parent_id" => $dept]);
        }
        if ($sstatus > 0) {
            $query->andFilterWhere(["tbl_staff_history.status_id" => $sstatus]);
        }
        if ($positiongroup > 0) {
            $query->andFilterWhere(["tbl_staff_position.position_group_id" => $positiongroup]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Written by KOB
     * 
     * @author kob
     * @param $citizen_id หมายเลขบัตรประชาชน
     * @return \yii\db\Query
     */
    public static function getAcademic($citizen_id) {

//SELECT authorise_date, title  FROM `tbl_staff_academic` 
//inner join tbl_academic on `tbl_staff_academic`.academic_id = tbl_academic.academic_id
//WHERE citizen_id = '8430188000293' 
//ORDER BY authorise_date DESC LIMIT 1
        $query = NEW Query();
        $query->select([
                    "title",
                    "authorise_date"
                ])
                ->from("tbl_staff_academic")
                ->innerJoin("tbl_academic", "tbl_academic.academic_id = tbl_staff_academic.academic_id")
                ->where([
                    "tbl_staff_academic.citizen_id" => $citizen_id
                ])
                ->orderBy([
                    "authorise_date" => SORT_DESC
        ]);

        $result = $query->createCommand()->queryOne();

        return $result;
    }

    public static function getPosition($citizen_id) {

//SELECT tbl_staff.citizen_id, first_thname, tbl_position.position, position_group,position_type,position_subtype  FROM tbl_staff
//inner join tbl_staff_position on tbl_staff.citizen_id = tbl_staff_position.citizen_id
//inner join tbl_position on tbl_staff_position.position_id = tbl_position.position_id
//inner join tbl_position_group on tbl_staff_position.position_group_id = tbl_position_group.position_group_id
//inner join tbl_position_type on tbl_staff_position.position_type_id = tbl_position_type.position_type_id
//inner join tbl_position_subtype on tbl_staff_position.position_subtype_id = tbl_position_subtype.position_subtype_id
//where tbl_staff.citizen_id = '8430188000293' and tbl_staff_position.default = 1
//        
        $query = NEW \yii\db\Query();
        $query->select([
                    "position",
                    "position_group",
                    "position_type",
                    "position_subtype",
                ])
                ->from("tbl_staff_position")
                ->innerJoin("tbl_position", "tbl_position.position_id = tbl_staff_position.position_id")
                ->innerJoin("tbl_position_group", "tbl_position_group.position_group_id = tbl_staff_position.position_group_id")
                ->innerJoin("tbl_position_type", "tbl_position_type.position_type_id = tbl_staff_position.position_type_id")
                ->innerJoin("tbl_position_subtype", "tbl_position_subtype.position_subtype_id = tbl_staff_position.position_subtype_id")
                ->where([
                    "tbl_staff_position.citizen_id" => $citizen_id,
//                    "tbl_staff_position.default" => 1
                ])
                ->orderBy([
                    "date_receive" => SORT_DESC
        ]);

        $result = $query->createCommand()->queryOne();

        return $result;
    }

//    public function makeDropDown() {
//        //global $data;
//        //$data = array();
//        //$data['0'] = '-- Top Level --';        
//        $parents = Staff::find();
//        
//        
//        foreach ($parents as $parent) {
//            $data[] = $r->first_thname . ' ' . $r->last_thname;
//            //Department::subDropDown($parent->id);
//        }
//
//        return $data;
//    }
}
