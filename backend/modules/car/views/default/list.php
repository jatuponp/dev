<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use common\components\ndate;
use common\models\Staff;
use yii\helpers\Url;

$this->title = 'รายการจัดรถยนต์';
?>
<div class="site-content">
    <h2><?= Html::encode($this->title) ?></h2>
    <hr/>
    <?php
    echo GridView::widget([
        'dataProvider' => $model->search(),
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '40%'],
                'header' => 'รายการจองรถยนต์',
                'attribute' => 'title'
            ],
            [
                'headerOptions' => ['width' => '20%'],
                'header' => 'สถานที่',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->place . ' ' . common\models\District::getName($model->amphoe) . ' ' . \common\models\Province::getName($model->province);
                }
            ],
            [
                'headerOptions' => ['width' => '15%'],
                'header' => 'วันที่เดินทาง',
                'format' => 'raw',
                'value' => function($model) {
                    $ndate = new ndate();
                    $tra = $ndate->getThaiShortDate($model->travel_date) . ' ' . substr($model->travel_time, 0, -3);
                    $return = $ndate->getThaiShortDate($model->return_date) . ' ' . substr($model->return_time, 0, -3);
                    return $tra . ' <br/> ' . $return;
                }
            ],
//            [
//                'headerOptions' => ['width' => '10%'],
//                'header' => 'รับที่',
//                'format' => 'raw',
//                'value' => function($model) {
//                    return $model->pickup_at . ' ' . substr($model->pickup_time, 0, -3);
//                }
//            ],
            [
                'headerOptions' => ['width' => '20%', 'style' => 'text-align: right;'],
                'contentOptions' => ['align' => 'center'],
                'header' => '',
                'format' => 'raw',
                'value' => function($model) {
                $html = '&nbsp;<a href="#theModal" data-remote="' . yii\helpers\Url::to(['many', 'id' => $model->id]) . '" style="padding-top: 5px; padding-bottom: 5px;" class="btn btn-primary" data-toggle="modal" data-target="#theModal"><i class="fa fa-bars" title="รายการใช้รถยนต์ร่วม"></i></a>';
                $html .= '&nbsp;<a href="#theModal" data-type="1" data-remote="' . yii\helpers\Url::to(['schedule', 'id' => $model->id]) . '" style="padding-top: 5px; padding-bottom: 5px;" class="btn btn-success" data-toggle="modal" data-target="#theModal"><i class="fa fa-calendar" title="รายละเอียดการจัดรถยนต์"></i></a>';
                $html .= '&nbsp;<a href="' . yii\helpers\Url::to(['stack', 'id' => $model->id]) . '" style="padding-top: 5px; padding-bottom: 5px;" class="btn btn-warning"><i class="fa fa-address-book" title="ใบมอบงาน"></i></a>';
                $html .= '&nbsp;<a href="' . yii\helpers\Url::to(['cancel', 'id' => $model->id]) . '" style="padding-top: 5px; padding-bottom: 5px;" class="btn btn-danger" data-method="post" data-confirm="คุณต้องการยกเลิกการจัดรถยนต์ไช่ไหม๋?"><i class="fa fa-trash" title="ยกเลิกการจัดรถยนต์"></i></a>';
                    return $html;
                },
            ],
        ]
    ]);
    ?>
</div>
<div class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-car"></i> รายการใช้รถยนต์ร่วม</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-close "></i></span>
                </button>
            </div>
            <div class="modal-body">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<EOF
$('#theModal').on('show.bs.modal', function (e) {    
    var button = $(e.relatedTarget);
    var remote = button.data('remote');
    var modal = $(this);
    if(button.data('type') == 1){
        $('.modal-title').html('<i class="fa fa-calendar"></i> ตารางการใช้รถยนต์');
    }
    // or, load content from value of data-remote url
    modal.find('.modal-body').load(remote);
});
EOF;
$this->registerJs($js);
?>