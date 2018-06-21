<?php

use yii\helpers\Url;
use backend\modules\car\assets\CarAsset;

CarAsset::register($this);
Yii::$app->name = "ระบบบริหารจัดการรถยนต์";
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div class="row">
    <div class="col-12 justify-content-between bg-dark">
        <div class="p-3 float-left text-white"><i class="fa fa-car"></i> &nbsp;<span><?= Yii::$app->name ?></span></div>
        <div class="p-3 float-right text-white">สำนักงานวิทยาเขตหนองคาย</div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-4 col-lg-3 col-xl-2 pt-3 bg-light border-right box-shadow">
        <nav class="navbar navbar-expand-md" style="margin-left: -25px; margin-right: -25px; padding-right: 0px">
            <div class="collapse navbar-collapse rounded" id="navbarCollapse">
                <?php require_once 'menus.php'; ?>
            </div>
        </nav>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 col-xl-10 pt-3">
        <?= $content ?>
    </div>
</div>
<?php $this->endContent(); ?>

