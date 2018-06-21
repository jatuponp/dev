<?php

namespace common\models\admission;

use Yii;

/**
 * This is the model class for table "ams_gpa".
 *
 * @property string $citizen_id
 * @property integer $proj_id
 * @property integer $open_id
 * @property double $thai
 * @property double $math
 * @property double $sci
 * @property double $social
 * @property double $phy
 * @property double $art
 * @property double $job
 * @property double $lang
 *
 * @property AmsRegister $citizen
 */
class comGpa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ams_gpa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'proj_id', 'open_id', 'thai', 'math', 'sci', 'social', 'phy', 'art', 'job', 'lang'], 'required'],
            [['proj_id', 'open_id'], 'integer'],
            [['thai', 'math', 'sci', 'social', 'phy', 'art', 'job', 'lang'], 'number','max' => 100.00, "min" => 0.00],
            [['citizen_id'], 'string', 'max' => 13]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'citizen_id' => 'Citizen ID',
            'proj_id' => 'Proj ID',
            'open_id' => 'Open ID',
            'thai' => 'Thai',
            'math' => 'Math',
            'sci' => 'Sci',
            'social' => 'Social',
            'phy' => 'Phy',
            'art' => 'Art',
            'job' => 'Job',
            'lang' => 'Lang',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitizen()
    {
        return $this->hasOne(AmsRegister::className(), ['citizen_id' => 'citizen_id']);
    }
    
    
}
