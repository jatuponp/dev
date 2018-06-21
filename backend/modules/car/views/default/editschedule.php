<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use kartik\widgets\DepDrop;
use common\components\ndate;
use yii\widgets\Pjax;

$ndate = new ndate();
$url = Url::to(['editschedule']);
$js = <<<EOF
//this will work with mulitpart data or regular form
  jQuery('#carBook-form').submit(function(e) {
      e.preventDefault();
      jQuery.ajax({
          url: jQuery(this).attr('action'),
          type: jQuery(this).attr('method'),
          data: new FormData(jQuery('form')[0]),
          mimeType: 'multipart/form-data',
          contentType: false,
          cache: false,
          processData: false,
          dataType: 'json',
          success: function(data) {
              //if there are serverside errors then ajax show them on the page
              if (data.errors) {
                  jQuery.each(data.errors, function(key, val) {
                      var el = jQuery('#' + key);
                      el.parent('.form-group').addClass('has-error');
                      el.next('.help-block').html(val);
                  });
              } else {
                  //reload pjax and close boostrap modal
                  jQuery.pjax.reload({
                      container: '#carBook-form'
                  });
                  jQuery('#modal').modal('hide');
                  jQuery('#schedule').html(data);
              }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
          }
      });
      return false;
});
EOF;
$this->registerJs($js);
$this->title = 'ตารงการจัดรถยนต์';
$i = 1;
?>
<div id="schedule">
    <?php
    $form = ActiveForm::begin([
                'id' => 'carBook-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-2 text-left',
                        'offset' => 'offset-sm-2',
                        'wrapper' => 'col-sm-10',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
    ]);
    ?>
    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'title')->input('text', ['placeholder' => $model->getAttributeLabel('title')]) ?>
            <?= $form->field($model, 'place')->input('text', ['placeholder' => $model->getAttributeLabel('place')]) ?>
            <div class="row">
                <label class="col-md-2 control-label  text-left" for="finstore-full_name"><?= $model->getAttributeLabel('amphoe') ?></label>
                <div class="col-md-5">
                    <?php
                    echo $form->field($model, 'province', ['template' => "<div class=\"col-12\">{input}</div>"])->widget(Select2::classname(), [
                        'data' => \common\models\Province::makeDropDown(),
                        'hideSearch' => false,
                        'options' => [
                            'placeholder' => 'เลือกจังหวัด'
                            , 'class' => 'form-control '
                            , 'multiple' => false
                            , 'style' => 'width: 98%;'
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-5">
                    <?=
                    $form->field($model, 'amphoe', ['labelOptions' => ['class' => 'col-md-3 control-label'], 'template' => "{label}<div class=\"col-md-9\">{input}</div>"])->widget(DepDrop::classname(), [
                        'type' => DepDrop::TYPE_SELECT2,
                        'data' => [$model->amphoe => ''],
                        'options' => ['style' => 'width: 98%;'],
                        'select2Options' => ['hideSearch' => true,],
                        'pluginOptions' => [
                            'depends' => ['carbooking-province'], // the id for cat attribute
                            'placeholder' => 'เลือกอำเภอ',
                            'url' => Url::to(["getdistrict"]),
                            'initialize' => true
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div id="cop" class="row no-gutters cop">
                <div id="title" class="col-md-2 title">
                    เดินทางวันที่                
                </div>
                <div class="col-md-3 pl-md-1 travelDate">
                    <?php
                    echo $form->field($model, 'travel_date', ['template' => "<div class=\"col-md-12\">{input}</div>"])->widget(DatePicker::classname(), [
                        'id' => 'beginDate',
                        'options' => ['placeholder' => 'วันที่เดินทาง'],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ],
                        'pluginEvents' => [
                            "changeDate" => "function(e) {
                            var minDate = new Date(e.date.valueOf());
                            $('#carbooking-return_date').kvDatepicker('setStartDate', minDate);
                        }",
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-2 d-flex flex-column flex-md-row align-items-center travelTime">
                    <?=
                    $form->field($model, 'travel_time', ['template' => "<div class=\"col-md-12\">{input}</div>"])->widget(TimePicker::className(), [
                        'pluginOptions' => [
                            'defaultTime' => '00:00',
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1
                        ]
                    ]);
                    ?>
                    <span style="margin-left: 3px; margin-top: -15px;">-</span>
                </div>
                <div class="col-md-3 pl-md-1 returnDate">
                    <?php
                    echo $form->field($model, 'return_date', ['template' => "<div class=\"col-md-12\">{input}</div>"])->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'เดินทางกลับวันที่'],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ],
                        'pluginEvents' => [
                            "changeDate" => "function(e) {
                            var maxDate = new Date(e.date.valueOf());
                            $('#carbooking-travel_date').kvDatepicker('setEndDate', maxDate);
                        }",
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-2 returnTime">
                    <?=
                    $form->field($model, 'return_time', ['template' => "<div class=\"col-md-12\">{input}</div>"])->widget(TimePicker::className(), [
                        'pluginOptions' => [
                            'defaultTime' => '00:00',
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <label class="col-md-2 control-label text-left" for="finstore-full_name">รับที่</label>
                <div class="col-md-6">
                    <?= $form->field($model, 'pickup_at', ['template' => "<div class=\"col-md-12\">{input}</div>"])->input('text', ['placeholder' => $model->getAttributeLabel('pickup_at')]) ?>
                </div>
                <div class="col-md-4">
                    <?=
                    $form->field($model, 'pickup_time', ['labelOptions' => ['class' => 'col-md-3 control-label'], 'template' => "{label}<div class=\"col-md-9\">{input}</div>"])->widget(TimePicker::className(), [
                        'pluginOptions' => [
                            'defaultTime' => '00:00',
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <?= $form->field($model, 'car_id')->dropDownList(common\models\car\CarVehicles::makeDropDown()) ?>
            <?php
            $values = explode(',', $model->driver_id);
            $model->driver = $values;
            echo $form->field($model, 'driver')->widget(Select2::classname(), [
                'data' => common\models\car\CarDriver::makeDropDown(),
                'options' => [
                    'id' => 'comm_' . $p->id,
                    'placeholder' => 'พิมพ์ชื่อ-นามสกุล',
                    'style' => 'width: 300px;',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);
            ?>
            <?= $form->field($model, 'kms', ['template' => "{label}<div class=\"col-md-6\">{input}</div><label class=\"form-control-label col-sm-2\" style=\"margin-left:-25px; margin-top: 5px;\">กิโลเมตร</label>"])->input('text', ['placeholder' => 'ระยะทางโดยประมาณ']) ?>
            <?= $form->field($model, 'note')->textarea([]) ?>

            <div class="form-group row">
                <div class="col-12">
                    <a href="<?= Url::to(['default/schedule', 'id' => $model->id]) ?>" class="btn btn-secondary  float-right ml-2" ><i class="fa fa-chevron-left"></i> ย้อนกลับ</a>
                    <?= Html::submitButton('<i class="fa fa-save"></i> บันทึก', ['class' => 'btn btn-primary float-right']) ?>&nbsp;
                </div>
            </div>
            <?= $form->field($model, 'id', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
            <?= $form->field($model, 'reqId', ['options' => ['class' => 'sr-only']])->hiddenInput() ?>
        </div>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>