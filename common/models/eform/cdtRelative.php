<?php

namespace common\models\eform;

use Yii;

/**
 * This is the model class for table "cdt_relative".
 *
 * @property integer $relative_id
 * @property string $relative_name
 */
class cdtRelative extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdt_relative';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relative_name'], 'required'],
            [['relative_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relative_id' => 'Relative ID',
            'relative_name' => 'Relative Name',
        ];
    }
}
