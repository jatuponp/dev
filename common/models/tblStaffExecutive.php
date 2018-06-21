<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "tbl_staff_executive".
 *
 * @property integer $id
 * @property string $citizen_id
 * @property integer $admin_id
 * @property string $date_start
 * @property string $date_stop
 * @property string $modifieddate
 * @property string $modifiedby
 *
 * @property Administrator $admin
 */
class tblStaffExecutive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_executive';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['citizen_id', 'admin_id', 'date_start', 'date_stop'], 'required'],
            [['admin_id'], 'integer'],
            [['date_start', 'date_stop', 'modifieddate'], 'safe'],
            [['citizen_id', 'modifiedby'], 'string', 'max' => 13]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'citizen_id' => 'ชื่อ-สกุล',
            'admin_id' => 'ตำแหน่งบริหาร',
            'date_start' => 'วันที่แต่งตั้ง',
            'date_stop' => 'วันที่ครบวาระ',
            'modifieddate' => 'Modifieddate',
            'modifiedby' => 'Modifiedby',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(TblAdministrator::className(), ['admin_id' => 'admin_id']);
    }
    public function search() {
        $query = $this->find();
        $query->where(['citizen_id' => $_REQUEST['id']]);
        

        $query->orderBy('citizen_id ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
}
