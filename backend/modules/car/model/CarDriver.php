<?php

namespace common\models\car;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Staff;

/**
 * This is the model class for table "car_driver".
 *
 * @property integer $id
 * @property string $citizen_id
 * @property string $nickname
 * @property string $picture
 * @property string $mobile
 */
class CarDriver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    var $mPicture;
    
    public static function tableName()
    {
        return 'car_driver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'mobile'], 'required'],
            [['citizen_id'], 'string', 'max' => 13],
            [['picture'], 'string', 'max' => 100],
            [['mobile', 'nickname'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'citizen_id' => 'ชื่อพนักงาน',
            'nickname' => 'ชื่อเล่น',
            'picture' => 'ภาพถ่าย',
            'mobile' => 'เบอร์โทร',
            'mPicture' => 'รูปภาพ'
        ];
    }
    
    public function search() {
        $query = $this->find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        //var_dump($query->createCommand()->rawSql);

        return $dataProvider;
    }
    
    public function makeDropDown() {
        global $data;
        $data = array();
        //$data['0'] = '-- Top Level --';        
        $parents = CarDriver::find()
                //->where(['parent_id' => 0])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->citizen_id] = Staff::getStaffNameById($parent->citizen_id);
        }

        return $data;
    }
}
