<?php

namespace common\models\alumni;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "aln_occupation".
 *
 * @property integer $occupation_id
 * @property string $std_code
 * @property string $occupation
 * @property string $alnposition
 * @property string $occupation_name
 * @property string $occupation_address
 * @property integer $province_id
 * @property string $occupation_tel
 */
class alnOccupation extends \yii\db\ActiveRecord
{
    public $search;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'aln_occupation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['occupation_id', 'std_code', 'occupation', 'alnposition', 'occupation_name', 'occupation_address', 'province_id', 'occupation_tel'], 'required'],
            [['occupation_id', 'province_id'], 'integer'],
            [['std_code'], 'string', 'max' => 20],
            [['occupation', 'alnposition', 'occupation_name', 'occupation_address'], 'string', 'max' => 255],
            [['occupation_tel'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'occupation_id' => 'Occupation ID',
            'std_code' => 'Std Code',
            'occupation' => 'Occupation',
            'alnposition' => 'Alnposition',
            'occupation_name' => 'Occupation Name',
            'occupation_address' => 'Occupation Address',
            'province_id' => 'Province ID',
            'occupation_tel' => 'Occupation Tel',
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
