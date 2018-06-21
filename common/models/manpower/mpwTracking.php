<?php

namespace common\models\manpower;

use Yii;

/**
 * This is the model class for table "mpw_tracking".
 *
 * @property integer $track_id
 * @property integer $mpw_id
 * @property integer $finished
 * @property string $modifiedby
 * @property string $modifeddate
 *
 * @property MpwTrackingDetails[] $mpwTrackingDetails
 */
class mpwTracking extends \yii\db\ActiveRecord {

    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mpw_tracking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpw_id'], 'required'],
            [['mpw_id', 'finished'], 'integer'],
            [['modifeddate'], 'safe'],
            [['modifiedby'], 'string', 'max' => 13],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'track_id' => 'Track ID',
            'mpw_id' => 'Mpw ID',
            'finished' => 'Finished',
            'modifiedby' => 'Modifiedby',
            'modifeddate' => 'Modifeddate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMpwTrackingDetails() {
        return $this->hasMany(MpwTrackingDetails::className(), ['track_id' => 'track_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMpw() {
        return $this->hasOne(mpwNumber::className(), ['mpw_id' => 'mpw_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition() {
        return $this->hasOne(\common\models\tblPosition::className(), ['position_id' => 'position_id'])
            ->via("mpw");
                        //->via("mpw");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDept() {
        return $this->hasOne(\common\models\tblDepartment::className(), ['id' => 'dept_id'])
                        ->via("mpw");
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->modifiedby = \Yii::$app->user->identity->citizen_id;
            return true;
        }
        return false;
    }

    public function lists($search = null) {
        $query = mpwTracking::find()
//                ->select("*")
                ->joinWith("position")
                ->joinWith("dept")
                ->andFilterWhere(["position_no" => $search])
                ->orFilterWhere(["like", "position", $search]);

//        die($query->createCommand()->rawSql);
        return $query;
    }

    public function listsEmpty($search = null) {
        $query = mpwTracking::find()
                ->select(["*", "mpw_number.*"])
                ->joinWith("position", TRUE, "RIGHT JOIN")
                ->where(["mpw_number.status" => 0])
                //->andWhere(["mpw_tracking.finished" => 0])
                ->andFilterWhere(["position_no" => $search])
                ->orFilterWhere(["like", "tbl_position.position", $search]);
                //->orFilterWhere(["like", "tbl_department.title", $search]);
        return $query;
    }

}
