<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;

$js = <<<EOF
$(document).ready(function()
{
    $("#plusRange").click(function() {
        var countDiv = $(".cop").length;
        $("#cop").clone().insertAfter("div.cop:last");
        $("div.title:last").html('<a id="delNode" href="#" class="btn btn-danger float-right delNode" style="padding: 0 7px; margin: 5px;"><i class="fa fa-minus"></i></a>');
        var tD = "travel_date-" + countDiv;
        var tT = "travel_time-" + countDiv;
        var rD = "return_date-" + countDiv;
        var rT = "return_time-" + countDiv;
        var travelDateHtml = '<input id="' + tD + '" class="form-control krajee-datepicker" name="CarBooking[tra_date][]" value="" placeholder="วันที่เดินทาง" aria-required="true" data-datepicker-source="' + tD + '" data-datepicker-type="1" type="text">';
        $("div.travelDate:last").html(travelDateHtml);
        var travelTimeHtml = '<div class="form-group row field-carbooking-travel_time required has-success">';
            travelTimeHtml += '<div class="col-md-12"><div class="bootstrap-timepicker input-group">';
            travelTimeHtml += '<input id="' + tT + '" class="form-control" name="CarBooking[tra_time][]" value="" aria-invalid="false" type="text"></div></div></div>';
            travelTimeHtml += '<span style="margin-left: 3px; margin-top: -15px;">-</span>';
        $("div.travelTime:last").html(travelTimeHtml);
        var returnDateHtml = '<input id="' + rD + '" class="form-control krajee-datepicker" name="CarBooking[ret_date][]" value="" placeholder="เดินทางกลับ" aria-required="true" data-datepicker-source="' + rD + '" data-datepicker-type="1" type="text">';
        $("div.returnDate:last").html(returnDateHtml);
        var returnTimeHtml = '<div class="form-group row field-carbooking-travel_time required has-success">';
            returnTimeHtml += '<div class="col-md-12"><div class="bootstrap-timepicker input-group">';
            returnTimeHtml += '<input id="' + rT + '" class="form-control" name="CarBooking[ret_time][]" value="" aria-invalid="false" type="text"></div></div></div>';        
        $("div.returnTime:last").html(returnTimeHtml);
        
        if ($('#' + tD).data('kvDatepicker')) { $('#' + tD).kvDatepicker('destroy'); }
        $('#' + tD).kvDatepicker({"autoclose":true,"format":"yyyy-mm-dd"});
        $('#' + tD).on('changeDate', function(e) {
            var minDate = new Date(e.date.valueOf());
            $('#' + rD).kvDatepicker('setStartDate', minDate);
        });
        
        if ($('#' + rD).data('kvDatepicker')) { $('#' + rD).kvDatepicker('destroy'); }
        $('#' + rD).kvDatepicker({"autoclose":true,"format":"yyyy-mm-dd"});
        $('#' + rD).on('changeDate', function(e) {
            var maxDate = new Date(e.date.valueOf());
            $('#' + tD).kvDatepicker('setEndDate', maxDate);
        });
        
        if ($('#' + tT).data('timepicker')) { $('#' + tT).timepicker('destroy'); }
        $('#' + tT).timepicker({"defaultTime":"00:00","showSeconds":false,"showMeridian":false,"minuteStep":5});

        if ($('#' + rT).data('timepicker')) { $('#' + rT).timepicker('destroy'); }
        $('#' + rT).timepicker({"defaultTime":"00:00","showSeconds":false,"showMeridian":false,"minuteStep":5});
    });
    
    //$("#delNode").on("click",function(){
    //$('div.title').delegate('a','click',function() {
    $(document).delegate("#delNode", "click", function(e) {
        console.log('test11');
        $(this).parent().closest('div.cop').remove();
    });
});
EOF;

$this->registerJs($js);

?>

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
                วันที่
                <a id="plusRange" href="#" class="btn btn-success float-right" style="padding: 0 7px; margin: 5px;"><i class="fa fa-plus"></i></a>
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
                'placeholder' => 'กรุณาเลือกพนักงานขับรถยนต์',
                'style' => 'width: 300px;',
                'multiple' => true
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
        ?>
        <?= $form->field($model, 'kms', ['template' => "{label}<div class=\"col-md-6\">{input}{error}</div><label class=\"form-control-label col-sm-2\" style=\"margin-left:-25px; margin-top: 5px;\">กิโลเมตร</label>"])->input('text', ['placeholder' => 'ระยะทางโดยประมาณ']) ?>
        <?= $form->field($model, 'note')->textarea([]) ?>

        <div class="form-group row">
            <div class="col-12">
                <button type="button" class="btn btn-secondary  float-right ml-2" data-dismiss="modal"><i class="fa fa-close"></i> ปิด</button>
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
