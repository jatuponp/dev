<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\tblStaffStatus;
/**
 * This is the model class for table "tbl_staff_history".
 *
 * @property string $citizen_id
 * @property integer $status_id
 * @property string $status_date
 * @property string $modifiedby
 * @property string $modifieddate
 *
 * @property StaffStatus $status
 * @property Staff $citizen
 */
class tblStaffHistory extends \yii\db\ActiveRecord
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
            [['citizen_id', 'status_id', 'status_date'], 'required'],
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
            'citizen_id' => 'Citizen ID',
            'status_id' => 'Status ID',
            'status_date' => 'Status Date',
            'modifiedby' => 'Modifiedby',
            'modifieddate' => 'Modifieddate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(tblStaffStatus::className(), ['status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitizen()
    {
        return $this->hasOne(Staff::className(), ['citizen_id' => 'citizen_id']);
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->modifiedby = \Yii::$app->user->identity->citizen_id;
//            $this->modifiedby = "8430188000293";

            return true;
        }
        return false;
    }

    
    
    public function lists($citizen_id, $staff_id) {
        
        $query = tblStaffHistory::find()
                ->joinWith("status")
                ->andWhere([
                    "citizen_id" => $citizen_id,
                    "staff_id" => $staff_id,
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
