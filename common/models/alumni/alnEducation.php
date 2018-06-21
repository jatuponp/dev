<?php

namespace common\models\alumni;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "aln_education".
 *
 * @property integer $edu_id
 * @property string $std_code
 * @property integer $studyingstatus
 * @property integer $educationlevel
 * @property string $course_branch
 * @property string $academy
 */
class alnEducation extends \yii\db\ActiveRecord
{
    public $search;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'aln_education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['std_code', 'studyingstatus', 'educationlevel', 'course_branch', 'academy'], 'required'],
            [['studyingstatus', 'educationlevel'], 'integer'],
            [['std_code'], 'string', 'max' => 20],
            [['course_branch', 'academy'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'edu_id' => 'Edu ID',
            'std_code' => 'Std Code',
            'studyingstatus' => 'Studyingstatus',
            'educationlevel' => 'Educationlevel',
            'course_branch' => 'Course Branch',
            'academy' => 'Academy',
        ];
    }
    
    public static function lists($search = NULL) {

        $query = parent::find();
            //->where(['like', 'title', $search]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }
}
