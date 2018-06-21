<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "drm_building".
 *
 * @property integer $id
 * @property string $dorm_name
 * @property string $dorm_type
 * @property string $dorm_descript
 */
class drmBuilding extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drm_building';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dorm_name', 'dorm_type', 'dorm_descript'], 'required'],
            [['dorm_type', 'dorm_descript'], 'string'],
            [['dorm_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dorm_name' => 'Dorm Name',
            'dorm_type' => 'Dorm Type',
            'dorm_descript' => 'Dorm Descript',
        ];
    }
}
