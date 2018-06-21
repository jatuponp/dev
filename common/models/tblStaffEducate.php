<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_educate".
 *
 * @property integer $id
 * @property string $citizen_id
 * @property integer $level_id
 * @property string $academy
 * @property string $degree
 * @property string $acadyear
 * @property integer $coutry_id
 * @property string $modifiedby
 * @property string $modifieddate
 */
class tblStaffEducate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_educate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'level_id', 'academy', 'degree', 'acadyear', 'coutry_id'], 'required'],
            [['level_id', 'coutry_id'], 'integer'],
            [['modifieddate'], 'safe'],
            [['citizen_id'], 'string', 'max' => 13],
            [['academy', 'degree'], 'string', 'max' => 255],
            [['acadyear'], 'string', 'max' => 4],
            [['modifiedby'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'citizen_id' => 'Citizen ID',
            'level_id' => 'Level ID',
            'academy' => 'Academy',
            'degree' => 'Degree',
            'acadyear' => 'Acadyear',
            'coutry_id' => 'Coutry ID',
            'modifiedby' => 'Modifiedby',
            'modifieddate' => 'Modifieddate',
        ];
    }
}
