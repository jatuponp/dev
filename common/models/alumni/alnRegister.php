<?php

namespace common\models\alumni;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "aln_register".
 *
 * @property integer $regis_id
 * @property string $std_code
 * @property string $std_citizen_id
 * @property string $graduation_date
 * @property string $tel_mobile
 * @property string $email
 * @property string $occupation
 * @property string $occupation_address
 * @property string $address
 * @property string $password
 * @property string $register_date
 */
class alnRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'aln_register';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['std_code', 'std_citizen_id', 'graduation_date', 'tel_mobile', 'email', 'address','province_id','district_id','postcode', 'password'], 'required'],
            [['register_date'], 'safe'],
            [['std_code'], 'string', 'max' => 20],
            [['std_citizen_id'], 'string', 'max' => 13],
            [['graduation_date'], 'string', 'max' => 50],
            [['tel_mobile'], 'string', 'max' => 10],
            [['email', 'address', 'password'], 'string', 'max' => 255],
            [['postcode'],'string','max'=>5],
            [['province_id','district_id'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'regis_id' => 'Regis ID',
            'std_code' => 'รหัสนักศึกษา',
            'std_citizen_id' => 'เลขประจำตัวประชาชน',
            'graduation_date' => 'ปีที่จบการศึกษา',
            'tel_mobile' => 'เบอร์มือถือ',
            'email' => 'E-mail',
            'address' => 'ที่อยู่ปัจจุบัน',
            'province_id'=>'จังหวัด',
            'district_id'=>'อำเภอ',
            'postcode'=>'รหัสไปรษณีย์',
            'password' => 'Password',
        //  'register_date' => 'Register Date',
        ];
    }
    public function lists() {
     //  if ($citizen_id) {
            $query = alnRegister::find();
                  //  ->where(['like','citizen_id',$citizen_id]);
                    
      //  } 

            $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        return $dataProvider;
    }
    public function listsd($std_citizen_id) {
     //  if ($citizen_id) {
            $query = alnRegister::find()
                   ->where(['like','std_citizen_id',$std_citizen_id]);
                    
      //  } 

            $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 20,
            ],
        ]);
        
        return $dataProvider;
    }
}
