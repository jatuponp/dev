<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\FileInput;
?>
<div class="material-default-index">
    <div class="page-header"><?= Html::encode('ข้อมูลรถยนต์') ?> [<?php echo ($model->id) ? "แก้ไข" : "สร้างใหม่"; ?>]</div>    
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
    <div class="form-group">
        <label class="col-md-2 control-label" for="hpdrepairing-request_build">ทะเบียนรถ :</label>
        <div class="col-md-6">
            <div class="controls form-inline">
                <?= $form->field($model, 'register_id', ['labelOptions' => ['class' => 'sr-only control-label']])->input('text', ['style' => 'width: 200px;']) ?>
                &nbsp;<label for="hpdrepairing-request_room">&nbsp;&nbsp;&nbsp;ประเภทรถ :</label>&nbsp;
                <?= $form->field($model, 'brand_model', ['labelOptions' => ['class' => 'sr-only control-label']])->input('text', ['style' => 'width: 200px;']) ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2 control-label" for="hpdrepairing-request_build">จำนวนที่นั่ง :</label>
        <div class="col-md-6">
            <div class="controls form-inline">
                <?= $form->field($model, 'capacity', ['labelOptions' => ['class' => 'sr-only control-label']])->input('text', ['style' => 'width: 145px;']) ?>                
                &nbsp;<label for="hpdrepairing-request_room">&nbsp;&nbsp;&nbsp;สถานะ :</label>&nbsp;
                <?= $form->field($model, 'status', ['labelOptions' => ['class' => 'sr-only control-label']])->dropDownList(['0' => 'ปกติ', '1' => 'ซ่อมบำรุง'], ['style' => 'width: 200px;']) ?>
            </div>
        </div>
    </div>
    <?php
    echo $form->field($model, 'mPicture')->widget(FileInput::classname(), [
        'options' => [
            'multiple' => false,
        ],
        'pluginOptions' => [
            'initialPreview' => [
                (($model->mPicture)? Html::img(Yii::getAlias('@web') . '/images/material/'.$model->mPicture, ['class' => 'file-preview-image']):"")
            ],
            'initialCaption' => $model->mPicture,
            'overwriteInitial' => false,
            'showUpload' => false
        ]
    ]);
    ?>
    <?= $form->field($model, 'description')->textarea(['rows' => '5', 'style' => 'width: 448px;']) ?>
    <?= $form->field($model, 'staff')->input('text', ['style' => 'width: 250px;']) ?>
    <?= $form->field($model, 'id', ['labelOptions' => ['class' => 'sr-only']])->hiddenInput() ?>    

    <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> บันทึก', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('<i class="glyphicon glyphicon-remove"></i> ยกเลิก', ['class' => 'btn', 'onclick' => 'history.back();']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>