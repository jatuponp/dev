<?php

namespace common\models\training;

use Yii;

/**
 * This is the model class for table "trng_course".
 *
 * @property integer $course_id
 * @property string $course_name
 * @property string $budget_year
 * @property string $start_date
 * @property string $finish_date
 * @property string $start_time
 * @property string $finish_time
 * @property string $place
 * @property integer $fee_student
 * @property integer $fee_kku
 * @property integer $fee_people
 * @property integer $regis_number
 * @property integer $seat_number
 * @property string $lecturer
 * @property string $outline
 * @property string $modified
 *
 * @property TrngPaticipant[] $trngPaticipants
 */
class trngCourse extends \yii\db\ActiveRecord
{
    public $search;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trng_course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_name', 'budget_year', 'start_date', 'finish_date', 'start_time', 'finish_time', 'place', 'fee_student', 'fee_kku', 'fee_people', 'lecturer', 'outline'], 'required'],
            [['start_date', 'finish_date', 'start_time', 'finish_time', 'modified'], 'safe'],
            [['fee_student', 'fee_kku', 'fee_people', 'regis_number', 'seat_number'], 'integer'],
            [['outline'], 'string'],
            [['course_name'], 'string', 'max' => 255],
            [['budget_year'], 'string', 'max' => 4],
            [['place'], 'string', 'max' => 200],
            [['lecturer'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => 'Course ID',
            'course_name' => 'ชื่อหลักสูตร',
            'budget_year' => 'ปีงบประมาณ',
            'start_date' => 'วันที่เริ่ม',
            'finish_date' => 'วันที่สิ้นสุด',
            'start_time' => 'เวลาเริ่มอบรม',
            'finish_time' => 'เวลาสิ้นสุดอบรม',
            'place' => 'สถานที่',
            'fee_student' => 'ค่าลงทะเบียน นร./นศ.',
            'fee_kku' => 'ค่าลงทะเบียน บุคลากร มข.',
            'fee_people' => 'ค่าลงทะเบียน บุคคลทั่วไป',
            'regis_number' => 'จำนวนสมัคร',
            'seat_number' => 'จำนวนเปิดรับ',
            'lecturer' => 'วิทยากร',
            'outline' => 'Course Outline',
            'modified' => 'วันที่ลงประกาศ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrngPaticipants()
    {
        return $this->hasMany(TrngPaticipant::className(), ['course_id' => 'course_id']);
    }
    
    public static function lists($search = NULL, $lastest = false) {

        $query = trngCourse::find()
//                ->andWhere(["course_name" => $search]);
                ->andFilterWhere([
            'or',
//            ["course_name" => $search],
            ["like", "course_name", $search],
//            ["like", "tbl_staff.last_thname", $search]
        ]);

        if ($lastest) {
            $today = date('Y-m-d');
                    
            $query->andWhere(['>=', 'start_date', $today]);
            
            
        }
        
        $query->orderBy('start_date asc');
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => ($lastest)? 5:25,
            ],
        ]);

        return $dataProvider;
    }
}
