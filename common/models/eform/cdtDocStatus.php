<?php

namespace common\models\eform;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\eform\cdtCredentials;
use common\components\ndate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;
Html::csrfMetaTags();
/**
 * This is the model class for table "cdt_doc_status".
 *
 * @property integer $doc_status_id
 * @property integer $id_form
 * @property string $status
 * @property string $doc_num
 * @property string $doc_copy
 */
class cdtDocStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdt_doc_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_form','citizen_id', 'doc_num', 'doc_copy','date_issue'], 'required'],
            [['date_issue'], 'string', 'max' => 50],
            [['id_form'], 'integer'],
            [['citizen_id'], 'string', 'max' => 13],
            [['doc_num'], 'string', 'max' => 50],
            [['doc_copy'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'doc_status_id' => 'Doc Status ID',
            'id_form' => 'Id Form',
            'citizen_id'=>'citizen Id',
            'doc_num'=>'เลขที่เอกสาร',
            'doc_copy' => 'Doc Copy',
            'date_issue'=>'date_issue',
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
    public function getDateIssue($cdt_id=NULL) {
        $query=cdtDocStatus::findOne(['id_form' => $cdt_id]);
            
        if ($query==""){
            return "-";
        }
        return $query->date_issue;
      
    }
    public function getDocNum($cdt_id=NULL) {
        $query=cdtDocStatus::findOne(['id_form' => $cdt_id]);
            
        if ($query==""){
            return "-";
        }
        return $query->doc_num;
      
    }
    public function search($cdt_id=NULL) {
        $query = cdtDocStatus::find()
                ->where(['id_form' => $cdt_id])
                ->orderBy('id_form ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    public function lists($citizen_id) {
        $query = cdtDocStatus::find()
                ->where(['citizen_id' => $citizen_id])
                ->orderBy('id_form ASC');
    //    print_r($query->createCommand()->rawSql);
    $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
}
