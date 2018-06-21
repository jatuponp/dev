<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\components\ndate;
use common\models\Staff;
$this->title = 'รายการรถยนต์ที่เดินทางวันเดียวกัน';
?>
<h5><?= $this->title ?></h5>
<div class="site-content">
    <?php
    Pjax::begin([
        'id' => 'grid-share-pjax',
    ]);
    echo GridView::widget([
        'dataProvider' => $model->searchBook(),
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '80%'],
                'header' => 'รายการ',
                'format' => 'raw',
                'value' => function($model) {
                    $ndate = new ndate();
                    $detail = $model->title . "<br/>" . $model->place . ' ' . $model->amphoe . ' ' . $model->province;
                    $tra = $ndate->getThaiShortDate($model->travel_date) . ' ' . substr($model->travel_time, 0, -3);
                    $return = $ndate->getThaiShortDate($model->return_date) . ' ' . substr($model->return_time, 0, -3);
                    return $detail . '<br/>' . $tra . ' <br/> ' . $return;
                }
            ],            
            [
                'headerOptions' => ['width' => '15%', 'style' => 'text-align: right;'],
                'contentOptions' => ['align' => 'center'],
                'header' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $html = '<a class="btn btn-primary" href="' . Url::to(['bookshare', 'id' => $model->id, 'reqId' => Yii::$app->request->get('reqId')], true) . '" title="จัดเข้าร่วมรายการนี้" style="padding: 4px 8px;" data-method="post" data-confirm="คุณต้องการยืนยันการจัดรถยนต์ร่วมใช่ไหม๋?"><i class="fa fa-car"></i></a>&nbsp;';
                    return $html;
                },
            ],
        ]
    ]);
    Pjax::end();
    ?>
</div>