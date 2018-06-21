<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\components\ndate;
use common\models\Staff;
$this->title = 'รายการจองรถยนต์';
?>
<div class="site-content">
    <h2><?= Html::encode($this->title) ?></h2>
    <hr/>
    <?php
    Pjax::begin([
        'id' => 'grid-booking-pjax',
        'timeout' => 5000,
    ]);
    echo GridView::widget([
        'dataProvider' => $model->search(),
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '30%'],
                'header' => 'จองใช้รถเพื่อ',
                'attribute' => 'objective'
            ],
            [
                'headerOptions' => ['width' => '20%'],
                'header' => 'สถานที่',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->objective_at . ' ' . $model->tambon . ' ' . $model->ampure . ' ' . $model->province;
                }
            ],
            [
                'headerOptions' => ['width' => '15%'],
                'header' => 'วันที่เดินทาง',
                'format' => 'raw',
                'value' => function($model) {
                    $ndate = new ndate();
                    $tra = $ndate->getThaiShortDate($model->travel_date) . ' ' . substr($model->travel_time_begin, 0, -3);
                    $return = $ndate->getThaiShortDate($model->return_date) . ' ' . substr($model->return_time_end, 0, -3);
                    return $tra . ' <br/> ' . $return;
                }
            ],
            [
                'headerOptions' => ['width' => '10%'],
                'header' => 'รับที่',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->pickup_at . ' ' . substr($model->pickup_time, 0, -3);
                }
            ],
            [
                'headerOptions' => ['width' => '10%'],
                'header' => 'ติดต่อ',
                'format' => 'raw',
                'value' => function($model) {
                    $st = Staff::find()->where(['citizen_id' => $model->citizen_id])->one();
                    return $st->first_thname . '<br/>' . $model->contact_phone;
                }
            ],
            [
                'headerOptions' => ['width' => '10%', 'style' => 'text-align: right;'],
                'contentOptions' => ['align' => 'center'],
                'header' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $html = '<a class="btn btn-primary" href="#theModal" data-remote="' . Url::to(['book', 'reqId' => $model->id], true) . '" title="จัดรถยนต์" data-toggle="modal" data-target="#theModal" style="padding: 4px 8px;"><i class="fa fa-car"></i></a>&nbsp;';
                    $html .= '<a class="btn btn-info" href="#myModal" data-remote="' . Url::to(['bookshare', 'reqId' => $model->id], true) . '" title="จัดรถยนต์ร่วม" data-toggle="modal" data-type="1" data-target="#theModal" style="padding: 4px 10px;"><i class="fa fa-code-fork"></i></a>';
                    return $html;
                },
            ],
        ]
    ]);
    Pjax::end();
    ?>
</div>

<div class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-car"></i> จัดรถยนต์</h5>
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
        $('.modal-title').html('<i class="fa fa-code-fork"></i> จัดรถยนต์ร่วม');
    }
    // or, load content from value of data-remote url
    modal.find('.modal-body').load(remote);
});
EOF;
$this->registerJs($js);
?>