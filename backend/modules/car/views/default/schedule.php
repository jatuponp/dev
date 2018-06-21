<?php

use yii\helpers\Url;
use common\components\ndate;
use yii\widgets\Pjax;

$ndate = new ndate();

$this->title = 'ตารงการจัดรถยนต์';
$i = 1;
$js = <<<EOF
//this will work with mulitpart data or regular form
  jQuery('#deleteschedul').click(function(e) {
      var remote = jQuery(this).attr('data-remote');
      var idx = $model->id;
      e.preventDefault();
      jQuery.ajax({
          url: remote,
          type: 'POST',
          data: JSON.stringify({"id" : idx}),
          contentType: "application/json; charset=utf-8", // this
          processData: false,
          dataType: 'json',
          success: function(data) {
              //if there are serverside errors then ajax show them on the page
              console.log(data);
              if (data.errors) {
                  jQuery.each(data.errors, function(key, val) {
                      var el = jQuery('#' + key);
                      el.parent('.form-group').addClass('has-error');
                      el.next('.help-block').html(val);
                  });
              } else {
                  //reload pjax and close boostrap modal
                  jQuery.pjax.reload({
                      container: '#schedule'
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
?>
<div id="schedule">
    <?php
    Pjax::begin();
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="65%">รายการ</th>
                <th width="25%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $i ?></td>
                <td><?= $model->title . '<br/>' . $ndate->getThaiLongDate($model->travel_date) . ' ' . substr($model->travel_time, 0, -3) . '<br/>' . $ndate->getThaiLongDate($model->return_date) . ' ' . substr($model->return_time, 0, -3) ?></td>
                <td class="text-right">
                    <a href="<?= Url::to(['editschedule', 'id' => $model->id]) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <!--<a href="<?= Url::to(['deleteschedule', 'id' => $model->id]) ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>-->
                </td>
            </tr>
            <?php
            $i++;
            $etc = \common\models\car\CarBooking::find()->where(['parent' => $model->id])->all();
            foreach ($etc as $r):
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $r->title . '<br/>' . $ndate->getThaiLongDate($r->travel_date) . ' ' . substr($r->travel_time, 0, -3) . '<br/>' . $ndate->getThaiLongDate($r->return_date) . ' ' . substr($r->return_time, 0, -3) ?></td>
                    <td class="text-right">
                        <a href="<?= Url::to(['editschedule', 'id' => $r->id]) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a id="deleteschedule" href="<?= Url::to(['deleteschedule', 'id' => $r->id], true) ?>" class="btn btn-danger" data-pjax="true" data-confirm="คุณต้องการลบรายการนี้ไช่ไหม๋"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php
                $i++;
            endforeach;
            ?>
        </tbody>
    </table>
    <?php Pjax::end(); ?>
</div>