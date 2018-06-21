<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\bootstrap\Modal;
use common\models\drmRoom;
use common\models\drmDept;
use common\models\drmRange;
use common\models\stdStudentMaster;
use common\models\drmBooking;

class BuildRoom extends Component {

    function getRoom($room_id, $css = 1) {
        list($f, $s) = explode('-', $room_id);
        $model = drmRoom::findOne($room_id);
        $dorm = drmDept::findAll(['dorm_id' => $model->dorm_id, 'years'=>Yii::$app->getModule('edorm')->years, 'terms' => Yii::$app->getModule('edorm')->terms]);
        $count_booking = \common\models\drmBooking::find()
                ->where(['room_id'=>$room_id,'years' => \Yii::$app->getModule('edorm')->years, 'terms' => \Yii::$app->getModule('edorm')->terms])
                ->andWhere("status != 'cancel'")
                ->count();
        
        //$std = stdStudentMaster::findOne(['studentcode' => \Yii::$app->user->identity->username]);
        $freind = drmBooking::find()
                ->where(['room_id' => $room_id, 'years' => \Yii::$app->getModule('edorm')->years, 'terms' => \Yii::$app->getModule('edorm')->terms])
                ->andWhere("status != 'cancel'")
                ->all()
        ;
        
        if ($model->room_status == 1) {
            if ($model->room_type == 'Teacher') {
                $class = 'btn-primary';
                echo '<button class="btn btn-primary room-dorm' . $css . '">ห้อง ' . $s . '</button>';
            } else if($model->capacity <= $count_booking) {
                echo '<button class="btn btn-danger room-dorm' . $css . '">ห้อง ' . $s . '</button>';
            } else {
                $class = 'btn-success';
                Modal::begin([
                    'options' => ['id' => 'room_' . $room_id],
                    'header' => '<h4 style="margin:0; padding:0">รายละเอียดห้อง ' . $room_id . '</h4>',
                    'toggleButton' => ['label' => 'ห้อง ' . $s, 'class' => 'btn ' . $class . ' room-dorm' . $css],
                ]);
                ?>
                <b>พักได้:</b> <?= $model->capacity ?> คน<br/>
                <b>ห้องน้ำ:</b> <?= ($model->toilet == 0) ? "ห้องน้ำรวม" : "มีห้องน้ำ" ?><br/>
                <b>สถานะห้อง:</b> <?= ($model->room_status == 0) ? "ชำรุด" : "ปกติ" ?>
                <h4>เพื่อนร่วมห้อง</h4>
                <?php
                    $cf=1;
                    foreach($freind as $f){
                        echo $cf.'# '.$f->studentcode. ' ' . $f->studentname . '<br/>';
                        $cf++;
                    }
                ?>
                <h4>อัตราค่าธรรมเนียม</h4>
                <table width='95%'>
                    <?php
                    $i = 1;
                    foreach ($dorm as $d) {
                        $sum += $d->dept_amount;
                        ?>
                        <tr>
                            <td width='5%'><?= $i ?></td>
                            <td width='75%'><?= $d->dept ?></td>
                            <td width='20%' align='right'><?= number_format($d->dept_amount, '0', '.', ',') ?> บาท</td>
                        </tr>

                        <?php
                        $i++;
                    }
                    ?>
                    <tr>
                        <td colspan="2" align='right'>รวม: </td>
                        <td align='right'><b><?= number_format($sum, '0', '.', ',') ?> บาท</b></td>
                    </tr>
                </table>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href="<?= \yii\helpers\Url::to(['booking/confirm', 'room_id' => $room_id]) ?>" type="button" class="btn btn-primary">จองห้องนี้</a>
                </div>
                <?php
                Modal::end();
            }
        }
    }
    
    function chkRange($years, $terms){
        
        $now = date('Y-m-d H:i:s');
        $query = drmRange::find()->where(['years'=>$years,'terms'=>$terms]);
        $query->andWhere(['OR', 'booking_begin = ' . "'0000-00-00'", "booking_begin <='" . $now . "'"]);
        $query->andWhere(['OR', 'booking_end = ' . "'0000-00-00'", "booking_end >='" . $now . "'"]);
        
        $result = $query->count();
        if($result>0){
            return true;
        }else{
            return false;
        }

    }

}
?>