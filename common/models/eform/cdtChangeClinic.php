<?php

namespace common\models\eform;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
/**
 * This is the model class for table "cdt_change_clinic".
 *
 * @property integer $change_clinic_id
 * @property string $citizen_id
 * @property integer $relation_id
 * @property string $fname_kin
 * @property string $lname_kin
 * @property integer $age_kin
 * @property string $citizen_id_relative
 * @property integer $patent_id
 * @property string $identity_card_copy
 * @property string $hounseregistration
 * @property string $date_prefer
 */
class cdtChangeClinic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdt_change_clinic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'relation_id','prefix', 'fname_kin', 'lname_kin', 'age_kin', 'citizen_id_relative', 'patent_id', 'identity_card_copy', 'hounseregistration','tel_mobil','status'], 'required'],
            [['relation_id', 'age_kin', 'patent_id'], 'integer'],
            [['date_prefer'], 'safe'],
            [['citizen_id', 'citizen_id_relative'], 'string', 'max' => 13],
            [['num_doc', 'date_issue'], 'string', 'max' => 50],
            [['fname_kin', 'lname_kin', 'identity_card_copy', 'hounseregistration'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'change_clinic_id' => 'Change Clinic ID',
            'citizen_id' => 'Citizen ID',
            'tel_mobil'=>'เบอร์โทร.ติดต่อ :',
            'relation_id' => 'ความสัมพันธ์ :',
            'prefix'=>'คำนำชื่อ :',
            'fname_kin' => 'ชื่อ :',
            'lname_kin' => 'สกุล :',
            'age_kin' => 'อายุ :',
            'citizen_id_relative' => 'เลขบัตรประจำตัวประชาชน :',
            'patent_id' => 'โดยใช้สิทธิ :',
            'identity_card_copy' => 'อัพโหลดไฟล์สำเนาบัตรประชาชน :',
            'hounseregistration' => 'อัพโหลดไฟล์สำเนาทะเบียนบ้าน :',
            'date_prefer' => 'Date Prefer',
            'num_doc'=>'เลขที่หนังสือออก :',
            'date_issue'=>'วันที่ออกเอกสาร :',
        ];
    }
    public function lists($citizen_id=null) {
       if ($citizen_id) {
            $query = cdtChangeClinic::find()
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
//    public function getFullname() {
//        return $this->prefix->prefixname. $this->first_thname . ' '. $this->last_thname;
//    }
    public static function getNamekin($citizen_id = null) {
    $query = cdtChangeClinic::findOne(['citizen_id' => $citizen_id]);
    $prefix = $query->prefix;
    if ($prefix == 0){
       // $prefixname="นาย";
        return 'นาย'.$query->fname_kin.' '.$query->lname_kin;
    }  elseif($prefix ==1) {
           // $prefixname="นาง";
            return 'นาง'.$query->fname_kin.' '.$query->lname_kin;
         } elseif($prefix ==2){
           // $prefixname="นางสาว";
             return 'นางสาว'.$query->fname_kin.' '.$query->lname_kin;
         }  
             // $prefixname="ด.ช.";
             elseif ($prefix==3) {
             return 'ด.ช.'.$query->fname_kin.' '.$query->lname_kin;
         }
         return 'ด.ญ.'.$query->fname_kin.' '.$query->lname_kin;
    }
   public static function getRelations($citizen_id = null) {
    $query = cdtChangeClinic::findOne(['citizen_id' => $citizen_id]);
    $relation_id = $query->relation_id;
    if ($relation_id == 0){
       // $prefixname="นาย";
        return 'บิดา';
    }  elseif($relation_id ==1) {
           // $prefixname="นาง";
            return 'มารดา';
         } elseif($relation_id ==2){
           // $prefixname="นางสาว";
             return 'คู่สมรส';
         }  
                 return 'บุตร';
    } 
}

