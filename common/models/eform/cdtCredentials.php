<?php

namespace common\models\eform;

use Yii;
use common\models\Staff;
use common\models\eform\cdtStatusPrefer;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * This is the model class for table "cdt_credentials".
 *
 * @property integer $cdt_id
 * @property string $citizen_id
 * @property integer $type_credentials_id
 * @property string $tel_mobil
 * @property integer $language
 * @property integer $amount
 * @property string $purpose
 * @property string $adjunct
 * @property string $date_prefer
 */
class cdtCredentials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdt_credentials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['cdt_id', 'citizen_id', 'type_credentials_id','salary', 'tel_mobil', 'th_amount','en_amount','purpose','text_adjunct'], 'required'],
            [['cdt_id','salary','th_amount','en_amount','payroll_cer','career_history','assure_conduct'], 'integer'],
            [['date_prefer'], 'safe'],
           // [['th_amount'],'validateIdCard'],
            [['citizen_id'], 'string', 'max' => 13],
            [['purpose', 'text_adjunct','annotation'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cdt_id' => 'Cdt ID',
            'citizen_id' => 'Citizen ID',
          //  'type_credentials_id' => 'Type Credentials ID',
            'salary'=>'เงินเดือน :',
         //   'tel_mobil' => 'เบอร์โทร.ติดต่อ :',
            'th_amount' => 'ฉบับบภาษาไทย (จำนวน) :',
            'en_amount' => 'ฉบับภาษาอังกฤษ (จำนวน) :',
            'payroll_cer' => 'เงินเดือน :',
            'career_history'=>'ประวัติการทำงาน :',
            'assure_conduct'=>'ความประพฤติ :',
            'purpose' => 'วัตถุประสงค์เพื่อ :',
            'text_adjunct' => 'ข้อมูลที่ต้องเพิ่มเติมในเอกสาร :',
            'annotation'=>'หมายเหตุ :',
            'date_prefer' => 'Date Prefer',
        ];
    }
    public $dept, $search, $sstatus, $positiongroup;
//    public function beforeSave($insert) {
//        if (parent::beforeSave($insert)) {
//
//            $this->citizen_id = \Yii::$app->user->identity->citizen_id;
////            $this->modifiedby = "8430188000293";
//
//            return true;
//        }
//        return false;
//    }
    public function staffList() {
        $citizen_id = \Yii::$app->user->identity->citizen_id;
        $query = NEW Query();
        $query->select([
                    "CONCAT(prefixname,first_thname, ' ', last_thname) AS fullname",
                    "tbl_staff.citizen_id", "tbl_staff_position.staff_id", "position", "date_work",
                    "position_type", "mpw_number.position_no","tbl_department.title"
                ])
                ->from("tbl_staff")
                ->innerJoin("std_prefix", "std_prefix.prefixid = tbl_staff.prefixid")
                ->leftJoin("tbl_staff_position", "tbl_staff.citizen_id = tbl_staff_position.citizen_id")
                ->leftJoin("tbl_position", "tbl_staff_position.position_id = tbl_position.position_id")
                ->leftJoin("tbl_position_type", "tbl_staff_position.position_type_id = tbl_position_type.position_type_id")
                ->leftJoin("mpw_number", "tbl_staff_position.mpw_id = mpw_number.mpw_id")
                ->leftJoin("vw_lastdatestatus_staff", "tbl_staff_position.citizen_id = vw_lastdatestatus_staff.citizen_id and tbl_staff_position.staff_id = vw_lastdatestatus_staff.staff_id")
                ->leftJoin("tbl_staff_history", "vw_lastdatestatus_staff.citizen_id = tbl_staff_history.citizen_id and vw_lastdatestatus_staff.staff_id = tbl_staff_history.staff_id and lastupdate = status_date")
                ->leftJoin("tbl_belongto", "tbl_staff_position.citizen_id = tbl_belongto.citizen_id AND tbl_staff_position.staff_id = tbl_belongto.staff_id")
                ->leftJoin("tbl_department", "tbl_belongto.depart_id = tbl_department.id")
                ->leftJoin("tbl_staff_status", "tbl_staff_history.status_id = tbl_staff_status.status_id")
                ->andFilterWhere(["tbl_staff.citizen_id" => $citizen_id])
                ->orderBy([
                    "first_thname" => SORT_ASC,
                    "last_thname" => SORT_ASC,
        ]);



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
        //return $query->createCommand()->rawSql;
        //return $query->all();
    }
//    public function validateIdCard() { 
//        $total = $this->th_amount; 
//        if($total = ""){ //ตัวที่ 13 มีค่าไม่เท่ากับผลรวมจากการคำนวณ ให้ add error 
//            $this->addError('th_amount', 'เฉพาะตัวเลขเท่านั้น');
//            }
//            } 
    public function lists($citizen_id=null) {
       if ($citizen_id) {
            $query = cdtCredentials::find()
                    ->where(['like','citizen_id',$citizen_id]);
                    
        } 

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        
        return $dataProvider;
    }

    public function search($citizen_id=NULL) {
        $query = NEW Query();
        $query->select([
                    "cdt_credentials.*"
                ])
                ->from("cdt_credentials")
            //    ->leftJoin("cdt_credentials", "cdt_doc_status.id_form = cdt_credentials.cdt_id")
                ->where(['cdt_credentials.citizen_id' => $citizen_id]);
             //   ->orderBy('date_issue ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    public function listprefer() {
            $query = cdtCredentials::find()
              ->leftJoin("cdt_status_prefer", "cdt_credentials.cdt_id = cdt_status_prefer.preferID")
              ->andWhere(["cdt_status_prefer.status_name"=>"wait"])
              ->andWhere(["cdt_status_prefer.type_form"=>"credential"]);
            $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
    public function listprefers() {
            $query = cdtCredentials::find()
                ->leftJoin("cdt_status_prefer", "cdt_credentials.cdt_id = cdt_status_prefer.preferID")
              //  ->andwhere(['cdt_credentials.citizen_id' => $citizen_id])
                ->andWhere(["cdt_status_prefer.type_form"=>"credential"])
                ->andWhere(["cdt_status_prefer.status_name"=>"success"]);
            $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
    public function listpre($citizen_id) {
        //    $query= NEW Query();
//            $query->select([
//                    "cdt_credentials.*","cdt_doc_status.*"
//                ])
            $query=  cdtCredentials::find()
              //  ->select('cdt_credentials.*')
                ->leftJoin("cdt_status_prefer", "cdt_credentials.cdt_id = cdt_status_prefer.preferID")
                ->andwhere(['cdt_credentials.citizen_id' => $citizen_id])
                ->andWhere(["cdt_status_prefer.type_form"=>"credential"]);
               // ->with('cdt_status_prefer')
               // ->all();
            $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 20,
            ],
        ]);
            
        return $dataProvider;
    }
}

