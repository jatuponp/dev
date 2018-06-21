<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_level".
 *
 * @property integer $level_id
 * @property string $title
 * @property string $title_eng
 * @property string $abb
 * @property string $abbeng
 */
class tblLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'title_eng'], 'string', 'max' => 100],
            [['abb', 'abbeng'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'level_id' => 'Level ID',
            'title' => 'Title',
            'title_eng' => 'Title Eng',
            'abb' => 'Abb',
            'abbeng' => 'Abbeng',
        ];
    }
    public function makeDropDown() {
        global $data;
        $data = array();
        $data['0'] = 'เลือกระดับการศึกษา';        
         $parents = tblLevel::find()
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->level_id] = $parent->title;
            
        }

        return $data;
    }
}
