<?php

namespace common\models\education;

use Yii;

/**
 * This is the model class for table "tbl_eduusers".
 *
 * @property integer $id
 * @property string $gcm_regid
 * @property string $userId
 * @property string $name
 * @property string $created_at
 */
class TblEduusers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_eduusers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gcm_regid'], 'string'],
            [['name'], 'required'],
            [['created_at'], 'safe'],
            [['userId'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gcm_regid' => 'Gcm Regid',
            'userId' => 'User ID',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }
    
    public function getStdMaster() {
        return $this->hasOne(\common\models\stdStudentMaster::className(), ['studentid' => 'userId']);
    }
}
