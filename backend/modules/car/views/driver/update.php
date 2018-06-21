<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use common\models\Staff;
?>
<div class="material-default-index">
    <div class="page-header"><?= Html::encode('ข้อมูลพนักงานรถยนต์') ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]</div>    
    <br/>
    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-md-6\">{input}</div>",
                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                ],
    ]);
    ?>
    <?php
    $url = Url::to(['stafflist']);
    $inits = Staff::getStaffNameById($model->citizen_id);

    echo $form->field($model, 'citizen_id', ['template' => "{label}<div class=\"col-md-4\">{input}</div>"])->widget(Select2::classname(), [
        'initValueText' => $inits,
        'options' => [
            'placeholder' => 'พิมพ์ชื่อ-นามสกุล',
            'style' => 'width: 300px;',
            'multiple' => false
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => '3',
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
        ],
    ]);
    ?>    
    <?= $form->field($model, 'nickname')->input('text', ['style' => 'width: 250px;']) ?>
    <?php
    echo $form->field($model, 'mPicture')->widget(FileInput::classname(), [
        'options' => [
            'multiple' => false,
        ],
        'pluginOptions' => [
            'initialPreview' => [
                (($model->mPicture) ? Html::img(Yii::getAlias('@web') . '/images/material/' . $model->mPicture, ['class' => 'file-preview-image']) : "")
            ],
            'initialCaption' => $model->mPicture,
            'overwriteInitial' => false,
            'showUpload' => false
        ]
    ]);
    ?>
    <?= $form->field($model, 'mobile')->input('text', ['style' => 'width: 250px;']) ?>
    <?= $form->field($model, 'id', ['labelOptions' => ['class' => 'sr-only']])->hiddenInput() ?>    

    <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> บันทึก', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn', 'onclick' => 'history.back();']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>