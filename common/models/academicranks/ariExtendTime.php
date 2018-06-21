<?php

namespace common\models\academicranks;

use Yii;
use yii\data\ActiveDataProvider;
use common\components\ndate;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * This is the model class for table "ari_extend_time".
 *
 * @property integer $extend_id
 * @property integer $id_stud
 * @property integer $extend_amount
 * @property string $extend_start
 * @property string $extend_end
 *
 * @property AriStudyLeave $idStud
 */
class ariExtendTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ari_extend_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'extend_start', 'extend_end'], 'required'],
            [['extend_amount'], 'integer'],
            [['citizen_id'], 'string', 'max' => 13],
            [['extend_start', 'extend_end'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'extend_id' => 'Extend ID',
            'citizen_id'=>'ชื่อ-สกุล',
            'extend_amount' => '',
            'extend_start' => 'วันที่เริ่มขยายเวลาศึกษา',
            'extend_end' => 'วันที่สิ้นสุดขยายเวลาศึกษา',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdStud()
    {
        return $this->hasOne(ariEducation::className(), ['id_stud' => 'id']);
    }
    public function Extenddate($citizen_id) {
        $query = ariExtendTime::find()
         ->where(['citizen_id' =>$citizen_id ])
         ->orderBy(extend_start)
         ->one();
       $start=$query->extend_start;
        if ($start==""){
           return "ไม่ได้ขยายเวลาศึกษาต่อ";
       }
    //   Yii::$app->thaiFormatter->locale = 'th-TH';
     //   $dmy= Yii::$app->Formatter->asDate($start, 'php:Y-m-d'); 
        $d = new ndate();
        return $d->getThaiShortDate($start);
        //return $start;
    }
    public function Extdate($citizen_id) {
        $query = ariExtendTime::find()
         ->where(['citizen_id' =>$citizen_id ])
         ->orderBy(extend_end)
         ->one();
       $end=$query->extend_end;
        if ($end==""){
           return "ไม่ได้ขยายเวลาศึกษาต่อ";
       }
    //  Yii::$app->thaiFormatter->locale = 'th-TH';
      //  $dmy= Yii::$app->Formatter->asDate($end, 'php:Y-m-d'); 
        $d = new ndate();
        return $d->getThaiShortDate($end);
      // return $end;
    }
   
    public function search() {
        $query = ariExtendTime::find()
                ->where(['citizen_id' => $_REQUEST['citizen_id']])
                ->orderBy('extend_id ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
//    public function searchAll($id,$citizen_id) {
//        $query = ariExtendTime::find()
//                ->where(['citizen_id' => $citizen_id])
//                ->orderBy('extend_id ASC');
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => false,
//        ]);
//
//        return $dataProvider;
//    }
}
