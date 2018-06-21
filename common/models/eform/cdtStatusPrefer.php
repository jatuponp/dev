<?php

namespace common\models\eform;

use Yii;
use yii\data\ActiveDataProvider;
use common\components\ndate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;
/**
 * This is the model class for table "cdt_status_prefer".
 *
 * @property integer $statusID
 * @property integer $preferID
 * @property string $status_name
 */
class cdtStatusPrefer extends \yii\db\ActiveRecord
{
    public $search;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdt_status_prefer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['statusID', 'preferID', 'status_name','th_en','type_form'], 'required'],
            [['statusID', 'preferID'], 'integer'],
            [['status_name','th_en','type_form','docs_num','issue_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'statusID' => 'Status ID',
            'preferID' => 'Prefer ID',
            'status_name' => 'Status Name',
            'docs_num'=>'เลขที่เอกสาร',
            'issue_date'=>'Issue Date',
        ];
    }
    public function DateIssue($citizen_id=NULL)
    {
//        $query = cdtDocStatus::findOne(['citizen_id' => $citizen_id]);
//                ->select(['date_issue']);
//        Yii::$app->thaiFormatter->locale = 'th_TH';
//        $date_issue=$query->date_issue;
//        Yii::$app->thaiFormatter->locale = 'th-TH';
//        $dmy= Yii::$app->Formatter->asDate($date_issue, 'php:Y-m-d'); 
//        $d = new ndate();
//        return $d->getThaiShortDate($dmy);
//        return $date_issue;
        $query = $this->find();
        $query->where(['citizen_id' => $citizen_id]);
        

        $query->orderBy('date_issue ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    public function getDateIssue($id_docs=NULL) {
        
//        $query=  cdtStatusPrefer::findOne(['preferID' => $id_docs]);
//            
//        if ($query==""){
//            return "-";
//        }
//        return $query->issue_date;
        $query = cdtStatusPrefer::find()
         ->where(['preferID' =>$id_docs ])
         ->orderBy(issue_date)
         ->one();
        if ($query==""){
            return "-";
        }
        return $query->issue_date;
      
    }
    public function getNameStatus($id_docs=NULL){
        $query = cdtStatusPrefer::find()
         ->where(['preferID' =>$id_docs ])
         ->orderBy(status_name)
         ->one();
        if($query==""){
            return "-";
        }
        return $query->status_name;    
    }
    public function getDocNum($id_docs=NULL) {
    //    $query=cdtStatusPrefer::findOne(['preferID' => $id_docs]);
        $query = cdtStatusPrefer::find()
         ->where(['preferID' =>$id_docs ])
         ->orderBy(docs_num)
         ->one();            
        if ($query==""){
            return "-";
        }
        return $query->docs_num;
      
    }
    public function search() {
        $query = cdtStatusPrefer::find()
                ->where(['preferID' => $_REQUEST['id_docs']])
                ->orderBy('statusID ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    public function lists($preferID,$th_en,$type_form) {
        $query = cdtStatusPrefer::find()
                ->where(['preferID' => $preferID])
                ->andwhere(['th_en'=>$th_en])
                ->andwhere(['type_form'=>$type_form])
                ->orderBy('statusID ASC');
       // print_r($query->createCommand()->rawSql);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    public function listsf($preferID,$type_form) {
        $query = cdtStatusPrefer::find()
                ->where(['preferID' => $preferID])
                ->andWhere(['type_form'=>$type_form])
              //  ->andWhere(['status_name'=>"success"])
                ->orderBy('statusID ASC');
       // print_r($query->createCommand()->rawSql);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
        public function Docnum($cdt_id) {
        $query = cdtStatusPrefer::find()
                ->where(['preferID' => $cdt_id])
                ->andWhere(['type_form'=>"credential"])
                ->orderBy('statusID ASC')
                ->one();
        $docs_num=$query->docs_num;
       if ($docs_num==" "){
           return "-";
       }


        return $docs_num;
    }
    public function Status_name($cdt_id=null) {
        $query = cdtStatusPrefer::find()
                ->where(['preferID' => $cdt_id])
                ->andWhere(['type_form'=>"credential"])
                ->orderBy('statusID ASC')
                ->one();
        $statusname=$query->status_name;
        return $statusname;
    }
    public function Issue_date($cdt_id=null) {
        $query = cdtStatusPrefer::find()
                ->where(['preferID' => $cdt_id])
                ->andWhere(['type_form'=>"credential"])
                ->orderBy('statusID ASC')
                ->one();
        $issue_date=$query->issue_date;
        return $issue_date;
    }
}
