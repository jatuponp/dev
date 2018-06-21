<?php

namespace common\models\eform;

use Yii;

/**
 * This is the model class for table "cdt_patent".
 *
 * @property integer $patent_id
 * @property string $patent_name
 */
class cdtPatent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cdt_patent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['patent_name'], 'required'],
            [['patent_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'patent_id' => 'Patent ID',
            'patent_name' => 'Patent Name',
        ];
    }
}
