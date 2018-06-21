<?php

use yii\bootstrap4\Nav;
use kartik\icons\Icon;

Icon::map($this, Icon::FA);
Nav::begin();
echo Nav::widget([
    'options' => ['class' => 'nav flex-column'],
    'encodeLabels' => false,
    'items' => [
        ['label' => 'เมนูหลัก', 'linkOptions' => ['class' => 'disabled'], 'visible' => !Yii::$app->user->isGuest],
        ['label' => '<i class="btn btn-primary fa fa-calendar" style="padding: 5px;"></i> ปฏิทินการใช้รถยนต์', 'url' => ['default/index'], 'active' => (Yii::$app->controller->id == 'default') && (Yii::$app->controller->action->id == 'index'), 'visible' => Yii::$app->user->can('carAdmin')],
        ['label' => '<i class="btn btn-success fa fa-list" style="padding: 5px;"></i> รายการจัดรถยนต์', 'url' => ['default/list'], 'visible' => Yii::$app->user->can('carAdmin')],
        ['label' => '<i class="btn btn-danger fa fa-list" style="padding: 5px;"></i> รายการจองรถยนต์', 'url' => ['default/booking'], 'visible' => Yii::$app->user->can('carAdmin')],
        ['label' => 'ข้อมูลพื้นฐาน', 'linkOptions' => ['class' => 'disabled'], 'visible' => !Yii::$app->user->isGuest],
        ['label' => '<i class="btn btn-primary fa fa-car" style="padding: 5px;"></i> ข้อมูลรถยนต์', 'url' => ['vehicle/index'], 'active' => (Yii::$app->controller->id == 'vehicle'), 'visible' => Yii::$app->user->can('carAdmin')],
        ['label' => '<i class="btn btn-primary fa fa-vcard" style="padding: 5px;"></i> ข้อมูลพนักงานขับรถยนต์', 'url' => ['driver/index'], 'active' => (Yii::$app->controller->id == 'driver'), 'visible' => Yii::$app->user->can('carAdmin')],
    ],
]);
Nav::end();