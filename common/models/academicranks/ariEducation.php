<?php
namespace common\models\academicranks;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\tblStaffStatus;
//use common\models\tblStaffHistory;
use common\models\tblLevel;
use common\models\Department;
use common\components\ndate;
use yii\db\Query;
//use yii\db\Query;
//use yii\data\SqlDataProvider;
/**
 * This is the model class for table "tbl_education".
 *
 * @property integer $id
 * @property string $citizen_id
 * @property integer $amount_time
 * @property string $start_date
 * @property string $end_date
 * @property integer $level_id
 * @property string $academy
 * @property string $degree
 * @property integer $country_id
 * @property string $status
 * @property string $returnjob_date
 * @property string $graduation_date
 * @property string $createby
 * @property string $createdate
 * @property string $modifiedby
 * @property string $modifieddate
 */
class ariEducation extends \yii\db\ActiveRecord
{
    public $status_id,$status_date,$dept,$search;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id','start_date', 'end_date', 'level_id','leave_status_id', 'academy', 'degree', 'country_id','graduation_date'], 'required'],
            [['level_id','leave_status_id', 'country_id'], 'integer'],
            [['start_date', 'end_date','graduation_date', 'createdate', 'modifieddate'], 'safe'],
            [['citizen_id'], 'string', 'max' => 13],
            [['academy', 'degree'], 'string', 'max' => 255],
            [['createby', 'modifiedby'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'citizen_id' => 'ชื่อ-สกุล',
            'start_date' => 'วันที่เริ่มลาศึกษา',
            'end_date' => 'วันที่คาดว่าจะสำเร็จการศึกษา',
            'level_id' => 'ระดับการศึกษา',
            'leave_status_id'=>'ประเภทการลา ศึกษา/ฝึกอบรม/ปฏิบัติการวิจัย',
            'academy' => 'สถานศึกษา/สถานที่ฝึกอบรม/สถานที่ปฏิบัติการวิจัย',
            'degree' => 'สาขาวิชา/ชื่อ,หัวข้อการอบรม/ปฏิบัติการวิจัย',
            'country_id' => 'ประเทศ',
            'graduation_date' => 'วันที่สำเร็จการศึกษา',
            'createby' => 'Createby',
            'createdate' => 'Createdate',
            'modifiedby' => 'Modifiedby',
            'modifieddate' => 'Modifieddate',
            'status_id'=>'สถานะ',
            'status_date'=>'วันที่เข้าปฏิบัติงาน',
        ];
    }
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->modifiedby = \Yii::$app->user->identity->citizen_id;
//            $this->modifiedby = "8430188000293";

            return true;
        }
        return false;
    }

//    public function search($citizen_id=NULL) {
//        $query = ariEducation::find()
//                ->where(['citizen_id' => $citizen_id])
//                ->orderBy('id ASC');
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => false,
//        ]);
//
//        return $dataProvider;
//    }
    public function search() {
        $query = ariEducation::find()
                ->where(['citizen_id' => $_REQUEST['citizen_id']])
                ->orderBy('id ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    public function Level($citizen_id=NULL) {
        $subQuery = ariEducation::findOne(['citizen_id' => $citizen_id]);
        $level_id=$subQuery->level_id;
        $query=tblLevel::findOne(['level_id'=>$level_id]);
        if ($query){
        return $query->title;
        }
        return "การฝึกอบรม";
    }
    public function Startdate($citizen_id) {
//        $query=ariEducation::findOne(['citizen_id'=>$citizen_id]);
//        $start=$query->start_date;
//        $dmy= Yii::$app->Formatter->asDate($start, 'php:Y-m-d'); 
//        $d = new ndate();
//        return $d->getThaiShortDate($dmy);
        $query = ariEducation::find()
         ->where(['citizen_id' =>$citizen_id ])
         ->orderBy(start_date)
         ->one();
        $start=$query->start_date;
      //  $dmy= Yii::$app->Formatter->asDate($start, 'php:Y-m-d'); 
        $d = new ndate();
        return $d->getThaiShortDate($start);
     
    }
    public function Enddate($citizen_id) {
//        $query=ariEducation::findOne(['citizen_id'=>$citizen_id]);
//        $end=$query->end_date;
//        $dmy= Yii::$app->Formatter->asDate($end, 'php:Y-m-d'); 
//        $d = new ndate();
//        return $d->getThaiShortDate($dmy);
        $query = ariEducation::find()
         ->where(['citizen_id' =>$citizen_id ])
         ->orderBy(end_date)
         ->one();
        $end_date=$query->end_date;
      //  $dmy= Yii::$app->Formatter->asDate($end_date, 'php:Y-m-d'); 
        $d = new ndate();
        return $d->getThaiShortDate($end_date);
    }
//    public function Returndate($citizen_id=NULL) {
//        $query=ariEducation::findOne(['citizen_id'=>$citizen_id]);
//        $returnjob_date=$query->returnjob_date;
//       if ($returnjob_date="0000-00-00"){
//           return "ยังไม่ได้รายงานตัวเข้าทำงาน";
//       }
//        Yii::$app->thaiFormatter->locale = 'th-TH';
//        $dmy= Yii::$app->Formatter->asDate($returnjob_date, 'php:Y-m-d'); 
//        $d = new ndate();
//        return $d->getThaiShortDate($dmy);
//       // return $query->end_date;
//    }
    public function Graduation($citizen_id) {
        $query=ariEducation::findOne(['citizen_id'=>$citizen_id]);
        $grad_date=$query->graduation_date;
       if ($grad_date=="0000-00-00"){
           return "ยังไม่สำเร็จการศึกษา";
       }
       // Yii::$app->thaiFormatter->locale = 'th-TH';
    //    $dmy= Yii::$app->Formatter->asDate($grad_date, 'php:Y-m-d'); 
        $d = new ndate();
        return $d->getThaiShortDate($grad_date);
       // return $query->end_date;
    }
    public function Income($citizen_id) {
//        $sub= NEW Query();
//            $sub->select([
//                    "citizen_id"
//                ])
//                ->from("tbl_education")
//                ->where(["citizen_id" => $citizen_id])
//                ->andwhere(['not', ['start_date' => null]])
//                ->orderBy([
//                    "status_date" => SORT_DESC
//                ]);
//
//                $result = $sub->createCommand()->queryOne();
//                $status_date=$result[status_date];
                
        $subquery = NEW Query();
            $subquery->select([
                    "status_date"
                ])
                ->from("tbl_staff_history")
                ->where(["citizen_id" => $citizen_id])
                ->andwhere(['not',["status_id"=>4]])
                ->orderBy([
                    "status_date" => SORT_DESC
                ]);

                $result = $subquery->createCommand()->queryOne();
                $status_date=$result[status_date];
//        $query=ariStaffHistory::findOne(['citizen_id'=>$citizen_id]);
//        $status_date=$query->status_date;
       if ($status_date=="0000-00-00"){
           return "-";
       }
       // Yii::$app->thaiFormatter->locale = 'th-TH';
    //    $dmy= Yii::$app->Formatter->asDate($status_date, 'php:Y-m-d'); 
        $d = new ndate();
        return $d->getThaiShortDate($status_date);
       // return $query->end_date;
    }
     public function searchAll() {
         
        $query = ariEducation::find();
      //  $query->where(['status' => $status]);
        if($query->citizen_id){
            $query->andWhere(['citizen_id' => $this->citizen_id]);
            $query->orderBy('id DESC');
        }
       
        

        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
//    public function getLaststatus() {
//        return $this->hasOne(vwLastdatestatusStaff::className(), ['citizen_id' => 'citizen_id']);
//    }
//    public function getHistory() {
//        return $this->hasOne(tblStaffHistory::className(), ['citizen_id' => 'citizen_id', 'status_date' => 'lastupdate'])
//            ->via("laststatus");
//    }
    public function listedu($search,$dept = NULL) {
             
      $query = ariEducation::find()
//              ->joinWith("history")
//              ->joinWith("laststatus")
              ->leftjoin("tbl_staff_history","tbl_education.citizen_id=tbl_staff_history.citizen_id")
              ->leftJoin("tbl_staff", "tbl_education.citizen_id = tbl_staff.citizen_id") 
              ->leftJoin("tbl_belongto", "tbl_staff.citizen_id = tbl_belongto.citizen_id")
              ->leftJoin("tbl_department", "tbl_belongto.depart_id = tbl_department.id")
           //   ->leftJoin("tbl_staff_history","tbl_education.citizen_id=tbl_staff_history.citizen_id")
             // ->leftJoin("tbl_staff_status","tbl_staff_history.status_id=tbl_staff_status.status_id")
              ->andFilterWhere(["tbl_staff.citizen_id" => $search])
              ->andWhere("tbl_education.leave_status_id <>1")
              ->andWhere("tbl_education.graduation_date = 0000-00-00")
              ->orFilterWhere(["like", "first_thname", $search])
              ->orFilterWhere(["like", "last_thname", $search]);
          //    ->orderBy(["tbl_staff_history.status_date" => SORT_DESC]);
              
        if ($dept > 0) {
            $query->andFilterWhere(["tbl_department.id" => $dept])
              ->orFilterWhere(["tbl_department.parent_id" => $dept]);
        }
        
       
        

        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

}
