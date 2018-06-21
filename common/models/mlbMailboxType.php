<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mlb_mailbox_type".
 *
 * @property integer $id
 * @property string $type_name
 */
class mlbMailboxType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mlb_mailbox_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name'], 'required'],
            [['type_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_name' => 'Type Name',
        ];
    }
    
    public function getMailTypeName($id = null) {
        $model = mlbMailboxType::findOne($id);
        return $model->type_name;
    }
    
    public function makeRadioOption(){
        $arr = array();
        $q = mlbMailboxType::find()->all();
        foreach ($q as $v) {
            $arr[$v->id] = $v->type_name;
        }
        return $arr;
    }
}
