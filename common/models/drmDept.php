<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "drm_dept".
 *
 * @property integer $dorm_id
 * @property string $dept
 * @property string $dept_amout
 * @property string $terms
 * @property string $years
 */
class drmDept extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drm_dept';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dorm_id', 'dept', 'dept_amout', 'terms', 'years'], 'required'],
            [['dorm_id'], 'integer'],
            [['dept'], 'string', 'max' => 250],
            [['dept_amout'], 'string', 'max' => 100],
            [['terms', 'years'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dorm_id' => 'Dorm ID',
            'dept' => 'Dept',
            'dept_amout' => 'Dept Amout',
            'terms' => 'Terms',
            'years' => 'Years',
        ];
    }
}
