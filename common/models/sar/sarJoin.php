<?php

namespace common\models\sar;

use Yii;

/**
 * This is the model class for table "sar_join".
 *
 * @property integer $act_id
 * @property string $staff_id
 * @property integer $attend
 * @property integer $verified
 *
 * @property SarActivities $actIt
 * @property StaffPosition $staff
 */
class sarJoin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sar_join';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['act_id', 'staff_id'], 'required'],
            [['act_id', 'attend', 'verified'], 'integer'],
            [['staff_id'], 'string', 'max' => 8],
            [['act_id'], 'exist', 'skipOnError' => true, 'targetClass' => sarActivities::className(), 'targetAttribute' => ['act_id' => 'act_id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\tblStaffPosition::className(), 'targetAttribute' => ['staff_id' => 'staff_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'act_id' => 'Act It',
            'staff_id' => 'Staff ID',
            'attend' => 'Attend',
            'verified' => 'Verified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActIt()
    {
        return $this->hasOne(sarActivities::className(), ['act_id' => 'act_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(\common\models\tblStaffPosition::className(), ['staff_id' => 'staff_id']);
    }
    
    public function lists($act_id) {
        $query = sarJoin::find()
                ->andWhere(["act_id" => $act_id]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }
    
}
