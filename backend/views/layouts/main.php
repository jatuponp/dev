<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use kartik\icons\Icon;

Icon::map($this, Icon::FA);
Yii::$app->name = 'Khon Kaen University, Nong Khai Campus';
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="wrap">
            <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-info box-shadow text-white">
                <h5 class="my-0 mr-md-auto font-weight-normal"><i class="d-inline-block fa fa-eercast"></i> ระบบสารสนเทศเพื่อการบริหารและการตัดสินใจ</h5>
                <!--                <nav class="my-2 my-md-0 mr-md-3 navbar-dark">
                                    <a class="p-2 text-dark" href="#">Features</a>
                                    <a class="p-2 text-dark" href="#">Enterprise</a>
                                    <a class="p-2 text-dark" href="#">Support</a>
                                    <a class="p-2 text-dark" href="#">Pricing</a>
                                </nav>-->
                <a class="btn btn-outline-light" href="javascript:;" data-method="post"><?= common\models\Staff::getStaffNameById(Yii::$app->user->identity->citizen_id) ?></a>&nbsp;
                <a class="btn btn-outline-light" href="<?= Url::to(['/site/logout']) ?>" data-method="post">ออกจากระบบ</a>
            </div>

            <div class="container-fluid">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <!--        <footer class="footer">
                    <div class="container-fluid">
                        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        
                        <p class="pull-right"><?= Yii::powered() ?></p>
                    </div>
                </footer>-->

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
