<?php

namespace common\models\phone;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Staff;

/**
 * This is the model class for table "tbl_phone".
 *
 * @property integer $phone_id
 * @property string $phone_number_in
 * @property string $phone_number_ex
 * @property string $mobile
 * @property string $fax
 * @property integer $dept_id
 * @property string $status
 *
 * @property Department $dept
 * @property PhoneOwner[] $phoneOwners
 */
class tblPhone extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public $citizen_id, $search, $datas, $dept;

    //public 

    public static function tableName() {
        return 'tbl_phone';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['phone_number_in', 'dept_id'], 'required'],
            ['dept_id', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'กรุณาเลือกคณะ / หน่วยงาน'],
            [['dept_id'], 'integer'],
            [['phone_number_in'], 'string', 'max' => 6],
            [['phone_number_ex', 'mobile', 'fax'], 'string', 'max' => 20],
            [['comments'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10],
//            [['phone_number_in', 'phone_number_ex', 'fax', 'status', 'deptName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'phone_id' => 'Phone ID',
            'phone_number_in' => 'หมายเลขภายใน',
            'phone_number_ex' => 'หมายเลขภายนอก',
            'mobile' => 'มือถือ',
            'fax' => 'Fax',
            'dept_id' => 'คณะ / หน่วยงาน',
            'deptName' => 'คณะ / หน่วยงาน',
            'staffName' => 'ชื่อ - นามสกุล',
            'status' => 'สถานะ',
            'staff_id' => 'ชื่อ - นามสกุล',
            'citizen_id' => 'ชื่อ - นามสกุล',
            'comments' => 'หมายเหตุ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepart() {
        return $this->hasOne(\common\models\tblDepartment::className(), ['id' => 'dept_id']);
    }

    public function getDeptName() {
        //print_r($this->dept);
        //exit();
        return $this->depart->title;
////      OR  $customer->getDept()->andWhere('status=1')->all() 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneOwners() {
        return $this->hasMany(tblPhoneOwner::className(), ['phone_id' => 'phone_id']);
    }

    public function getStaff() {
        return $this->hasMany(Staff::className(), ['citizen_id' => 'citizen_id']) //เดิม staff_id
                        ->via('phoneOwners');
    }

    public function getStaffName() {

        $query = $this->staff;
        foreach ($query as $r) {
            $temp .= "- " . Staff::getStaffNameById($r->citizen_id) . "<br>";
        }

        return $temp;
    }

    public function lists($search = null, $dept = NULL, $unlimited = false) {

        $query = tblPhone::find()
                ->joinWith('depart')
                ->joinWith('staff')
                ->filterWhere(['like', 'title', $search])
                ->orFilterWhere(['like', 'phone_number_in', $search])
                ->orFilterWhere(['like', 'phone_number_ex', $search])
                ->orFilterWhere(['like', 'tbl_phone.mobile', $search])
                ->orFilterWhere(['like', 'tbl_phone.fax', $search])
                ->orFilterWhere(['like', 'tbl_phone.comments', $search])
                ->orFilterWhere(['like', 'first_thname', $search])
                ->orFilterWhere(['like', 'last_thname', $search])
                ->orderBy([
            "tbl_department.id" => SORT_ASC,
            "phone_number_in" => SORT_ASC,
        ]);

        if ($dept > 0) {
            $query->andFilterWhere([
                'or',
                ["tbl_department.id" => $dept],
                ["tbl_department.parent_id" => $dept]
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => ($unlimited) ? 2000 : 50,
            ],]);

        $dataProvider->setSort([
            'attributes' => [
                'deptName' => [
                    'asc' => ['tbl_department.title' => SORT_ASC],
                    'desc' => ['tbl_department.title' => SORT_DESC],
                ]
                , 'staffName' => [
                    'asc' => ['first_thname' => SORT_ASC, 'last_thname' => SORT_ASC],
                    'desc' => ['first_thname' => SORT_DESC, 'last_thname' => SORT_DESC],
                ]
            ]
        ]);
        return $dataProvider;
    }

    public function getStaffList() {
        $data = array();

        $query = NEW \yii\db\Query();
        $query->select([
                    "CONCAT(prefixname,first_thname, ' ', last_thname) AS fullname",
                    "tbl_staff.citizen_id"
//            , "tbl_staff_position.staff_id"
                ])
                ->from("tbl_staff")
                ->innerJoin("std_prefix", "tbl_staff.prefixid = std_prefix.prefixid");
//                ->leftJoin("tbl_staff_position", "tbl_staff.citizen_id = tbl_staff_position.citizen_id")
//                ->leftJoin("vw_lastdatestatus_staff", "tbl_staff_position.citizen_id = vw_lastdatestatus_staff.citizen_id and tbl_staff_position.staff_id = vw_lastdatestatus_staff.staff_id")
//                ->leftJoin("tbl_staff_history", "vw_lastdatestatus_staff.citizen_id = tbl_staff_history.citizen_id and vw_lastdatestatus_staff.staff_id = tbl_staff_history.staff_id and lastupdate = status_date")
//                ->andWhere(["tbl_staff_history.status_id" => 1]);
        $results = $query->createCommand()->queryAll();

        foreach ($results as $value) {
            //$data[$value["staff_id"]] = $value["fullname"];
            $data[$value["citizen_id"]] = $value["fullname"];
        }

        return $data;
    }

    public function statNumberOfPhone() {
        $query = tblPhone::find()
                ->select(['title', 'datas' => 'COUNT(*)'])
                ->joinWith('depart')
                ->groupBy('title')
                ->createCommand()
                ->queryAll();
        //;

        $arr = array();
        foreach ($query as $row) {

            $arr[] = array((string) $row['title'], (int) $row['datas']);
            //$tname = mlbMailboxType::getMailTypeName($row->type_id);
            //$arr[] = array($row['tile'], (int) $row['datas']);
        };

        return $arr;
        //return $query[0]['title'];
    }

}
