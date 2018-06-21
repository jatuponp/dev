<?php

namespace common\models\training;

use Yii;

/**
 * This is the model class for table "trng_paticipant".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $reg_id
 * @property string $reg_datetime
 * @property string $paid_datetime
 * @property string $paid_by
 *
 * @property TrngRegister $reg
 * @property TrngCourse $course
 */
class trngPaticipant extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'trng_paticipant';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['course_id', 'reg_id'], 'required'],
            [['course_id', 'reg_id'], 'integer'],
            [['reg_datetime', 'paid_datetime'], 'safe'],
            [['paid_by'], 'string', 'max' => 20],
            [['reg_id'], 'exist', 'skipOnError' => true, 'targetClass' => trngRegister::className(), 'targetAttribute' => ['reg_id' => 'reg_id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => trngCourse::className(), 'targetAttribute' => ['course_id' => 'course_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'reg_id' => 'Reg ID',
            'reg_datetime' => 'Reg Datetime',
            'paid_datetime' => 'Paid Datetime',
            'paid_by' => 'Paid By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReg() {
        return $this->hasOne(trngRegister::className(), ['reg_id' => 'reg_id']);
    }
    
    public function getFullName() {
        return $this->reg->title.$this->reg->first_name . '  ' . $this->reg->last_name;

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse() {
        return $this->hasOne(trngCourse::className(), ['course_id' => 'course_id']);
    }

    public function lists($course_id) {
        $query = trngPaticipant::find()
                ->andWhere(["course_id" => $course_id]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $dataProvider;
    }

}
