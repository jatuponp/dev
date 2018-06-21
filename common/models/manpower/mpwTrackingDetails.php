<?php

namespace common\models\manpower;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "mpw_tracking_details".
 *
 * @property integer $id
 * @property integer $track_id
 * @property integer $mpw_id
 * @property string $status
 * @property string $modifiedby
 * @property string $modifieddate
 *
 * @property MpwTracking $track
 * @property MpwNumber $mpw
 */
class mpwTrackingDetails extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mpw_tracking_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['track_id', 'status'], 'required'],
            [['track_id'], 'integer'],
            [['modifieddate'], 'safe'],
            [['status'], 'string', 'max' => 100],
            [['modifiedby'], 'string', 'max' => 13],
            [['track_id'], 'exist', 'skipOnError' => true, 'targetClass' => mpwTracking::className(), 'targetAttribute' => ['track_id' => 'track_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'track_id' => 'Track ID',
            'status' => 'Status',
            'modifiedby' => 'Modifiedby',
            'modifieddate' => 'Modifieddate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrack() {
        return $this->hasOne(MpwTracking::className(), ['track_id' => 'track_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMpw() {
        return $this->hasOne(MpwNumber::className(), ['mpw_id' => 'mpw_id']);
    }

    public static function lists($track_id = NULL) {

        $query = mpwTrackingDetails::find()
                ->where(["track_id" => $track_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

}
