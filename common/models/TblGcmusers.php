<?php

namespace common\models;

use yii\db\ActiveRecord;

class TblGcmusers extends ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_gcmusers';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'userId', 'created_at'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['userId'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'gcm_regid' => 'GCM Register ID',
            'name' => 'Name',
            'userId' => 'userId',
            'created_at' => 'Create Time',
        ];
    }
}

