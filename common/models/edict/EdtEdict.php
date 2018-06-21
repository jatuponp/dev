<?php

namespace common\models\edict;

use Yii;

/**
 * This is the model class for table "edt_edict".
 *
 * @property integer $id
 * @property integer $dept
 * @property integer $num
 * @property integer $years
 * @property string $topic
 * @property string $content
 * @property integer $since
 * @property string $approveDate
 * @property string $signBy
 * @property string $instead
 * @property string $files
 * @property integer $published
 * @property string $createDate
 * @property string $applyDate
 */
class EdtEdict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edt_edict';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['years', 'topic', 'content', 'signBy'], 'required'],
            [['dept', 'num', 'years', 'published'], 'integer'],
            [['content'], 'string'],
            [['since', 'approveDate', 'createDate', 'applyDate'], 'safe'],
            [['topic'], 'string', 'max' => 500],
            [['signBy'], 'string', 'max' => 13],
            [['instead', 'files'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dept' => 'Dept',
            'num' => 'ที่',
            'years' => 'ปี',
            'topic' => 'ชื่อเรื่อง',
            'content' => 'เนื้อหา',
            'since' => 'ทั้งนี้ ตั้งแต่',
            'approveDate' => 'สั่ง ณ วันที่',
            'signBy' => 'ลงนามโดย',
            'instead' => 'Instead',
            'files' => 'Files',
            'published' => 'Published',
            'createDate' => 'Create Date',
            'applyDate' => 'Apply Date',
        ];
    }
}
