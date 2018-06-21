<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'ข้อมูลรถยนต์';
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
        'showHeader' => true,
        'layout' => "{items}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['width' => '5%'],
            ],
            [
                'contentOptions' => ['width' => '85%'],
                'header' => 'รายการรถยนต์',
                'format' => 'raw',
                'value' => function($model) {
                    $html = '<img src="https://it.nkc.kku.ac.th/common/media/car/'  . $model->picture . '" width="120px" style="float: left; margin-right: 15px;"/><div>';
                    $html .= '<b>ทะเบียนรถยนต์:</b> ' . $model->register_id . '<br/>';
                    $html .= '<b>ยี่ห้อ/รุ่น:</b> ' . $model->brand_model . '<br/>';
                    $html .= '<b>จำนวนที่นั่ง:</b> ' . $model->capacity . '<br/>';
                    $html .= '<b>สถานะ:</b> ' . $model->status . '<br/>';
                    $html .= '<b>รายละเอียด:</b> ' . $model->description . '<br/>';
                    $html .= '</div>';
                    return $html;
                }
            ],
            [
                'headerOptions' => ['width' => '10%', 'style' => 'text-align:center;'],
                'contentOptions' => ['width' => '10%','align' => 'center'],
                'class' => 'yii\grid\CustomColumn',
                'template' => '{update} {delete}',
                'header' => 'แก้ไข',
            ],
        ]
    ]);
    ?> 
</div>
