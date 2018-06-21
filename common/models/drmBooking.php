<?php

namespace common\models;


use Yii;
/**
 * This is the model class for table "drm_booking".
 *
 * @property integer $id
 * @property string $student_id
 * @property string $room_id
 * @property string $years
 * @property string $terms
 * @property string $booking_begin
 * @property string $booking_confirm
 * @property string $status
 */
class drmBooking extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'drm_booking';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['student_id', 'room_id', 'years', 'terms', 'booking_begin', 'booking_confirm', 'status'], 'required'],
            [['booking_begin', 'booking_confirm'], 'safe'],
            [['status'], 'string'],
            [['room_id'], 'string', 'max' => 20],
            [['years', 'terms'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'student_id' => 'รหัสนักศึกษา',
            'room_id' => 'Room ID',
            'years' => 'Years',
            'terms' => 'Terms',
            'booking_begin' => 'Booking Begin',
            'booking_confirm' => 'Booking Confirm',
            'status' => 'Status',
        ];
    }

    public function getStdStudentMaster() {
        return $this->hasOne(stdStudentMaster::className(), ['studentid' => 'student_id']);
    }

    public function getStudentName() {
        return $this->stdStudentMaster->studentprefix . $this->stdStudentMaster->studentname . ' ' . $this->stdStudentMaster->studentsurname;
    }
    
    public function getStudentCode(){
        return $this->stdStudentMaster->studentcode;
    }

}
