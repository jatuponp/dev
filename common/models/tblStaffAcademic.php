<?php

namespace common\models;

use Yii;

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
class tblStaffAcademic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
            'citizen_id' => 'ชื่อ-สกุล',
            'academic_id' => 'ตำแหน่งวิชาการ',
            'authorise_date' => 'วันที่แต่งตั้ง',
            'pathfile' => 'Pathfile',
            'modifiedby' => 'Modifiedby',
            'modifeddate' => 'Modifeddate',
        ];
    }
}
