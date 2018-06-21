<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Staff;

$this->title = 'ข้อมูลพนักงานขับรถยนต์';
?>
<div class="site-content">
    <h2><?= Html::encode($this->title) ?></h2>
    <hr/>
    <div class="row">
        <div class="col-4">
            <?= Html::a('<i class="fa fa-plus"></i> เพิ่มข้อมูล', ['update'], ['class' => 'btn btn-info']) ?>
        </div>
        <div class="col-8">

        </div>
    </div><br/>
    <?php
    echo GridView::widget([
        'dataProvider' => $model->search(),
        'layout' => "{items}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '5%'],
            ],
            [
                'headerOptions' => ['width' => '15%'],
                'header' => '',
                'format' => 'raw',
                'value' => function($model) {
                    $html = '<img src="https://it.nkc.kku.ac.th/common/media/car/' . $model->picture . '" width="120px" style="float: left; margin-right: 15px;"/>';
                    return $html;
                }
            ],
            [
                'headerOptions' => ['width' => '20%'],
                'header' => 'ชื่อ-นามสกุล',
                'format' => 'raw',
                'value' => function($model) {
                    $html = Staff::getStaffNameById($model->citizen_id) . ' (' . $model->nickname . ')';
                    return $html;
                }
            ],
            [
                'headerOptions' => ['width' => '50%'],
                'header' => 'เบอร์โทร',
                'attribute' => 'mobile'
            ],
            [
                'headerOptions' => ['width' => '10%', 'style' => 'text-align:center;'],
                'contentOptions' => ['align' => 'center'],
                'class' => 'yii\grid\CustomColumn',
                'template' => '{update} {delete}',
                'header' => 'แก้ไข',
            ],
        ]
    ]);
    ?> 
</div>
