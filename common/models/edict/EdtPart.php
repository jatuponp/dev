<?php

namespace common\models\edict;

use Yii;

/**
 * This is the model class for table "edt_part".
 *
 * @property integer $id
 * @property integer $edt_id
 * @property string $title
 * @property string $actions
 * @property integer $ordering
 */
class EdtPart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edt_part';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['edt_id', 'actions'], 'required'],
            [['edt_id', 'ordering'], 'integer'],
            [['actions'], 'string'],
            [['title'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'edt_id' => 'Edt ID',
            'title' => 'ชื่อคณะกรรมการฝ่าย',
            'actions' => 'มีหน้าที่',
            'ordering' => 'Ordering',
        ];
    }
}
