<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_academic".
 *
 * @property integer $academic_id
 * @property string $title
 * @property string $title_eng
 * @property string $abb
 * @property string $abb_eng
 */
class tblAcademic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_academic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'title_eng'], 'string', 'max' => 100],
            [['abb', 'abb_eng'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'academic_id' => 'Academic ID',
            'title' => 'ตำแหน่งทางวิชาการ',
            'title_eng' => 'Title Eng',
            'abb' => 'Abb',
            'abb_eng' => 'Abb Eng',
        ];
    }
    public function makeDropDown() 
    {
        global $data;
        $data = array();
        $data['0'] = 'ตำแหน่งทางวิชาการ';        
         $parents = tblAcademic::find()
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->academic_id] = $parent->title. '( ' . $parent->abb.')';
            
        }

        return $data;
    }
    public static function makeDopedown($all = false) {

        $results = tblAcademic::find()->all();

        $data = array();

        ($all)? $data[0] = "ตำแหน่งวิชาการ":"";

        foreach ($results as $value) {

            $data[$value->academic_id] = $value->title;
        }

        return $data;
    }
}
