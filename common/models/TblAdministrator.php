<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_administrator".
 *
 * @property integer $admin_id
 * @property string $admin_title
 */
class TblAdministrator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_administrator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_title'], 'required'],
            [['admin_title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'admin_title' => 'Admin Title',
        ];
    }
    public static function makeDopedown($all = false) {

        $results = TblAdministrator::find()->all();

        $data = array();

        ($all)? $data[0] = "ตำแหน่งบริหาร":"";

        foreach ($results as $value) {

            $data[$value->admin_id] = $value->admin_title;
        }

        return $data;
    }
}
