<?php

namespace common\models\edict;

use Yii;

/**
 * This is the model class for table "edt_commit_more".
 *
 * @property integer $id
 * @property string $name
 * @property string $citizen_id
 */
class EdtCommitMore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edt_commit_more';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 300],
            [['citizen_id'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'citizen_id' => 'Citizen ID',
        ];
    }
}
