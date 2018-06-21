<?php

namespace common\models\academicranks;

use Yii;
use common\models\tblStaffPosition;
use common\models\tblPosition;
use common\models\academicranks\ariStaffAcademic;
use common\models\academicranks\ariAcademic;
use common\models\tblStaffStatus;
use common\models\academicranks\ariEducation;
use common\models\tblStaffEducate;
use common\models\academicranks\ariExtendTime;
use common\models\Staff;
use common\models\TblBelongto;
use app\models\Department;
use common\components\ndate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "tbl_staff_academic".
 *
 * @property string $citizen_id
 * @property integer $academic_id
 * @property string $authorise_date
 * @property string $pathfile
 * @property string $modifiedby
 * @property string $modifeddate
 */
class ariStaffAcademic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $acad,$dept,$search;
    public static function tableName()
    {
        return 'tbl_staff_academic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'academic_id', 'authorise_date', 'pathfile'], 'required'],
            [['academic_id'], 'integer'],
            [['authorise_date', 'modifeddate'], 'safe'],
            [['citizen_id'], 'string', 'max' => 13],
            [['pathfile'], 'string', 'max' => 255],
            [['modifiedby'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'citizen_id' => 'Citizen ID',
            'academic_id' => 'Academic ID',
            'authorise_date' => 'Authorise Date',
            'pathfile' => 'Pathfile',
            'modifiedby' => 'Modifiedby',
            'modifeddate' => 'Modifeddate',
        ];
    }
    public function search() {
        $position_id=4;
        $subQuery = tblStaffPosition::find()->select('citizen_id')->where(['position_id'=>$position_id]);
        $query = ariStaffAcademic::find()->where(['citizen_id'=>$subQuery]);
        $query->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
       //  }
        return $dataProvider;
        
    }
    public function liststa($search,$acad=NULL,$dept = NULL) {
            $position_id=4;
            $query = ariStaffAcademic::find()
              ->leftJoin("tbl_staff", "tbl_staff_academic.citizen_id = tbl_staff.citizen_id")
              ->leftJoin("tbl_academic", "tbl_staff_academic.academic_id = tbl_academic.academic_id")
              ->leftJoin("tbl_belongto", "tbl_staff.citizen_id = tbl_belongto.citizen_id")
              ->leftJoin("tbl_department", "tbl_belongto.depart_id = tbl_department.id")
            //  ->andWhere(["tbl_staff_position.position_id"=>$position_id])
            //  ->andFilterWhere(["tbl_academic.academic_id" => $acad]);
//              ->orFilterWhere(["like", "first_thname", $search])
//              ->orFilterWhere(["like", "last_thname", $search]);
              ->andFilterWhere(["tbl_staff.citizen_id" => $search])
              ->orFilterWhere(["like", "first_thname", $search])
              ->orFilterWhere(["like", "last_thname", $search]);
        if ($acad > 0) {
            $query->andFilterWhere(["tbl_academic.academic_id" => $acad]);
        }
        if ($dept>0) {
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
    public function staffid($citizen_id=NULL) {
        $query = ariStaff::findOne(['citizen_id' => $citizen_id]);
               
        return $query->staff_id;
    }
    public function Authorize($citizen_id=NULL)
    {
        $query = ariStaffAcademic::findOne(['citizen_id' => $citizen_id]);
        $authorise_date=$query->authorise_date;
       // Yii::$app->thaiFormatter->locale = 'th-TH';
     //   $dmy= Yii::$app->Formatter->asDate($authorise_date, 'php:Y-m-d'); 
        $d = new ndate();
        return $d->getThaiShortDate($authorise_date);
    }
}
