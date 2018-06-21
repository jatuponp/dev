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
 * This is the model class for table "cdt_doc_authenticate".
 *
 * @property integer $id_docs
 * @property string $citizen_id
 * @property string $mobile
 * @property integer $th_amn
 * @property integer $en_amn
 * @property string $purposes
 * @property string $text_adjuncts
 * @property string $dateprefer
 */
class cdtDocAuthenticate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdt_doc_authenticate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'mobile', 'th_amn', 'en_amn', 'purposes', 'text_adjuncts'], 'required'],
            [['dateprefer'], 'safe'],
            [['th_amn', 'en_amn'], 'integer'],
            [['citizen_id'], 'string', 'max' => 13],
           // [['mobile'], 'string', 'max' => 10],
            [['purposes', 'text_adjuncts'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_docs' => 'Id Docs',
            'citizen_id' => 'Citizen ID',
          //  'mobile' => 'เบอร์โทร.ติดต่อ :',
            'th_amn' => 'ฉบับบภาษาไทย (จำนวน) :',
            'en_amn' => 'ฉบับภาษาอังกฤษ (จำนวน) :',
            'purposes' => 'วัตถุประสงค์เพื่อ :',
            'text_adjuncts' => 'ข้อมูลเพิ่มเติม :',
            'dateprefer' => 'Dateprefer',
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
            $query = cdtDocAuthenticate::find()
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
                    "cdt_doc_authenticate.*"
                ])
                ->from("cdt_doc_authenticate")
            //    ->leftJoin("cdt_credentials", "cdt_doc_status.id_form = cdt_credentials.cdt_id")
                ->where(['cdt_doc_authenticate.citizen_id' => $citizen_id]);
             //   ->orderBy('date_issue ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    public function listprefer() {
            $query = cdtDocAuthenticate::find()
              ->leftJoin("cdt_status_prefer", "cdt_doc_authenticate.id_docs = cdt_status_prefer.preferID")
              ->andWhere(["cdt_status_prefer.status_name"=>"wait"])
              ->andWhere(["cdt_status_prefer.type_form"=>"workinghistory"]);
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
            $query= cdtDocAuthenticate::find()
               // ->from("cdt_doc_status")
                ->leftJoin("cdt_status_prefer", "cdt_doc_authenticate.id_docs = cdt_status_prefer.preferID")
                ->andwhere(['cdt_doc_authenticate.citizen_id' => $citizen_id])
                ->andWhere(["cdt_status_prefer.type_form"=>"workinghistory"]);
            $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 20,
            ],
        ]);
            
        return $dataProvider;
    }
    public function listpres() {
    $query= cdtDocAuthenticate::find()
               // ->from("cdt_doc_status")
                ->leftJoin("cdt_status_prefer", "cdt_doc_authenticate.id_docs = cdt_status_prefer.preferID")
                ->andWhere(["cdt_status_prefer.type_form"=>"workinghistory"])
                ->andWhere(["cdt_status_prefer.status_name"=>"success"]);
             //   ->andwhere(['cdt_doc_authenticate.citizen_id' => $citizen_id]);
            $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 20,
            ],
        ]);
            
        return $dataProvider;
    }
}
