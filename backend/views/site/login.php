<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'เข้าสู่ระบบ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login  bg-light border rounded mt-5">
    <h2><?= Html::encode($this->title) ?></h2>

    <p>เข้าสู่ระบบด้วยบัญชีผู้ใช้ของมหาวิทยาลัยขอนแก่น <br>
        <b><u>- บุคลากร</u></b> เข้าระบบด้วย KKU-Net<br>
        <b><u>- นักศึกษาเก่า</u></b> เข้าระบบด้วย KKU-Net หรือ KKU-Webmail<br>
        <b><u>- นักศึกษาใหม่</u></b> เข้าระบบด้วย <b>Username:</b> รหัสนักศึกษาไม่มีขีด และ <b>Password:</b> รหัสบัตรประชาชน
    </p>

    <div class="row">        
        <div class="col-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('ลงชื่อเข้าใช้', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
