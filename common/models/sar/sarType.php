<?php

namespace common\models\sar;

use Yii;

/**
 * This is the model class for table "sar_type".
 *
 * @property integer $type_id
 * @property string $type_name
 *
 * @property SarActivities[] $sarActivities
 */
class sarType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sar_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name'], 'required'],
            [['type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'type_name' => 'Type Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSarActivities()
    {
        return $this->hasMany(SarActivities::className(), ['type_id' => 'type_id']);
    }
    
    public static function makeDD($all = false) {
         $results = sarType::find()
//                ->select(["type_id", "type_name"])
                //->where("register_name IS NOT NULL or register_name <> ''")
                ->all();

        $data = array();

        if ($all)
            $data["all"] = "ทั้งหมด";

        foreach ($results as $value) {
            $data[$value->type_id] = $value->type_name;
        }
        
        return $data;
    }
}
