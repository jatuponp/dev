<?php

namespace common\models\workmate;

use Yii;

/**
 * This is the model class for table "wkm_vote".
 *
 * @property integer $form_id
 * @property integer $item_id
 * @property string $who
 * @property string $whom
 * @property integer $vote
 */
class wkmVote extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wkm_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['item_id', 'who', 'whom', 'vote'], 'required'],
            [['form_id', 'item_id'], 'integer'],
            [['vote'], 'double'],
            [['who', 'whom'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'form_id' => 'Form ID',
            'item_id' => 'Item ID',
            'who' => 'Who',
            'whom' => 'Whom',
            'vote' => 'คะแนนความพึงพอใจ',
        ];
    }

    public static function itemsAlias($key) {
        $items = [
            'rating' => [
                5 => '5',
                4 => '4',
                3 => '3',
                2 => '2',
                1 => '1'
            ],
        ];

        return \yii\helpers\ArrayHelper::getValue($items, $key, []);
    }

    public function getItemRating() {
        return self::itemsAlias('rating');
    }

    public static function chkVoted($who, $whom, $form_id) {
        $voted = self::find()
                ->where([
            'who' => $who,
            'whom' => $whom,
            'form_id' => $form_id,
        ])->count();
        if ($voted) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
