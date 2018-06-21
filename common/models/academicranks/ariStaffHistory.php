<?php

namespace common\models\academicranks;
use yii\data\ActiveDataProvider;
use common\models\academicranks\ariExtendTime;
use app\models\Department;
use common\models\Staff;
use common\models\tblStaffStatus;
use common\models\tblLevel;
use Yii;
use common\components\ndate;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;
Html::csrfMetaTags();
/**
 * This is the model class for table "tbl_staff_history".
 *
 * @property string $citizen_id
 * @property integer $status_id
 * @property string $status_date
 * @property string $modifiedby
 * @property string $modifieddate
 *
 * @property Staff $citizen
 * @property StaffStatus $status
 */
class ariStaffHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'status_id'], 'required'],
            [['status_id'], 'integer'],
            [['status_date', 'modifieddate'], 'safe'],
            [['citizen_id'], 'string', 'max' => 13],
            [['modifiedby'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'citizen_id' => 'ชื่อ-สกุล',
            'status_id' => 'Status ID',
            'status_date' => 'วันที่รายงานตัว',
            'modifiedby' => 'Modifiedby',
            'modifieddate' => 'Modifieddate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitizen()
    {
        return $this->hasOne(Staff::className(), ['citizen_id' => 'citizen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(tblStaffStatus::className(), ['status_id' => 'status_id']);
    }
    public function getTitle()
    {
        return $this->status->title;
    }
        public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->modifiedby = \Yii::$app->user->identity->citizen_id;
//            $this->modifiedby = "8430188000293";

            return true;
        }
        return false;
    }

    public function Status($leave_status_id=NULL)
    {
      
        $query=tblStaffStatus::findOne(['status_id'=>$leave_status_id]);
        return $query->title;
    }
    
    public function lists($citizen_id) {
        
        $query = ariStaffHistory::find()
                ->joinWith("status")
                ->andWhere([
                    "citizen_id" => $citizen_id,
                   // "staff_id" => $staff_id,
                        ]);
        
//        print_r($query->createCommand()->rawSql);
//        die();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
        
    }
}
