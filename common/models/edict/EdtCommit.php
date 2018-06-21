<?php

namespace common\models\edict;

use Yii;

/**
 * This is the model class for table "edt_commit".
 *
 * @property integer $id
 * @property integer $part_id
 * @property string $citizen_id
 * @property string $other
 * @property string $doAct
 * @property integer $ordering
 */
class EdtCommit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edt_commit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['citizen_id'], 'required'],
            [['edt_id','part_id', 'ordering'], 'integer'],
            [['citizen_id'], 'string', 'max' => 200],
            [['other'], 'string', 'max' => 200],
            [['fullname'], 'string', 'max' => 500],
            [['doAct'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'part_id' => 'Part ID',
            'citizen_id' => 'Citizen ID',
            'other' => 'Other',
            'doAct' => 'Do Act',
            'ordering' => 'Ordering',
        ];
    }
}
