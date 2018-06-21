<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "std_prefix".
 *
 * @property integer $prefixid
 * @property string $prefixname
 * @property string $prefixnameeng
 * @property string $prefixabb
 * @property string $prefixabbeng
 * @property string $defaultsex
 */
class stdPrefix extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'std_prefix';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prefixid', 'prefixname', 'prefixnameeng', 'prefixabb', 'prefixabbeng', 'defaultsex'], 'required'],
            [['prefixid'], 'integer'],
            [['prefixname', 'prefixnameeng', 'prefixabb', 'prefixabbeng'], 'string', 'max' => 50],
            [['defaultsex'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prefixid' => 'Prefixid',
            'prefixname' => 'Prefixname',
            'prefixnameeng' => 'Prefixnameeng',
            'prefixabb' => 'Prefixabb',
            'prefixabbeng' => 'Prefixabbeng',
            'defaultsex' => 'Defaultsex',
        ];
    }
    
    public static function makeDD($all = false) {

        $results = stdPrefix::find()->all();

        $data = array();

        ($all)? $data[0] = "ทั้งหมด":"";

        foreach ($results as $value) {

            $display = (empty($value->prefixid))? "":"{$value->prefixname} ($value->prefixnameeng)";
            $data[$value->prefixid] = $display;
        }

        return $data;
    }
}
