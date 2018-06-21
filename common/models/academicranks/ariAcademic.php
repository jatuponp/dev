<?php

namespace common\models\academicranks;

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
class ariAcademic extends \yii\db\ActiveRecord
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
            'title' => 'Title',
            'title_eng' => 'Title Eng',
            'abb' => 'Abb',
            'abb_eng' => 'Abb Eng',
        ];
    }
}
