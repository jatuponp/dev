<?php

namespace common\models\academicranks;

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
class ariStaffAcademic extends \yii\db\ActiveRecord
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
            'citizen_id' => 'Citizen ID',
            'academic_id' => 'Academic ID',
            'authorise_date' => 'Authorise Date',
            'pathfile' => 'Pathfile',
            'modifiedby' => 'Modifiedby',
            'modifeddate' => 'Modifeddate',
        ];
    }
}
