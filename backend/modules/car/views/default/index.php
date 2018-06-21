<?php

use yii\helpers\Url;
use common\models\car\CarVehicles;
use common\components\ndate;

$ndate = new ndate();
?>
<?php
$cars = CarVehicles::find()->all();
$dt = new DateTime;
if (isset($_GET['year']) && isset($_GET['week'])) {
    $dt->setISODate($_GET['year'], $_GET['week']);
} else {
    $dt->setISODate($dt->format('o'), $dt->format('W'));
}
$year = $dt->format('o');
$week = $dt->format('W');

$tday = [
    'Monday' => 'จันทร์',
    'Tuesday' => 'อังคาร',
    'Wednesday' => 'พุธ',
    'Thursday' => 'พฤหัสบดี',
    'Friday' => 'ศูกร์',
    'Saturday' => 'เสาร์',
    'Sunday' => 'อาทิตย์'
];

$date = $dt->format('Y-m-d'); // monday
$day_start = date("Y-m-d", strtotime('monday this week', strtotime($date)));
$day_end = date("Y-m-d", strtotime('sunday this week', strtotime($date)));

$js = <<<EOF
$("#myModal").on("show.bs.modal", function(e) {
    var link = $(e.relatedTarget);
    $(this).find(".modal-body").load(link.attr("href"));
});
$('body').on('hidden.bs.modal', '.modal', function () {
  $(this).removeData('bs.modal');
});
EOF;
$this->registerJs($js);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="pull-right">
            <?php echo 'ระหว่างวันที่ ' . $ndate->getThaiShortDate($day_start) . ' ถึง ' . $ndate->getThaiShortDate($day_end) . ' &nbsp;&nbsp;'; ?>
            <a href="<?php echo Url::to(['default/index', 'week' => ($week - 1), 'year' => $year]); ?>" class="btn btn-primary"><i class="fa fa-chevron-left"></i> สัปดาห์ก่อน</a> <!--Previous week-->
            <a href="<?php echo Url::to(['default/index']); ?>" class="btn btn-primary">สัปดาห์นี้</a> <!--Previous week-->
            <a href="<?php echo Url::to(['default/index', 'week' => ($week + 1), 'year' => $year]); ?>" class="btn btn-primary">สัปดาห์ถัดไป <i class="fa fa-chevron-right"></i></a> <!--Next week-->
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-sm-12">
        <div id="schedule" class="jqs-demo mb-3 jqs jqs-mode-edit" style="border: 1px solid #ccc;">
            <table class="jqs-table">
                <tbody>
                    <?php
                    foreach ($cars as $c):
                        $events = $model->getScheduler($c->id, $day_start, $day_end);
                        ?>
                        <tr>
                            <td>
                                <?php
                                foreach ($events as $event) {
                                    $diff_first = strtotime($event->travel_date . ' ' . $event->travel_time) - strtotime($day_start . ' 00:00:00');
                                    $ttime = ((int) substr($event->travel_time, 0, 2) < 12) ? '00:00:00' : $event->travel_time;
                                    $diff_last = strtotime($event->return_date . ' ' . $event->return_time) - strtotime($event->travel_date . ' ' . $ttime);
                                    $d1 = new DateTime($day_start . ' 00:00:00');
                                    $d2 = new DateTime($day_end . ' 00:00:00');
                                    $event_first_date = new DateTime($event->travel_date . ' ' . $event->travel_time);
                                    $event_last_date = new DateTime($event->return_date . ' ' . $event->return_time);
                                    if ($event_first_date >= $d1) {
                                        $first_position = (floor((floor($diff_first / (60 * 60))) / 12)) * 7.1428;
                                    } else {
                                        $first_position = 0;
                                    }
                                    $last_position = (ceil((floor($diff_last / (60 * 60))) / 12)) * 7.1428;
                                    if ($event_last_date >= $d2) {
                                        $last_position = 100 - $first_position;
                                    }
                                    if($last_position > 100 ){
                                        $last_position = 100;
                                    }
                                    echo "<div class='jqs-event' style='left: {$first_position}%; width: {$last_position}%;'>";
                                    if ((ceil($last_position / 7.1428)) <= 3) {
                                        echo substr($event->travel_time, 0, -3) . "-" . substr($event->return_time, 0, -3);
                                    } else {
                                        echo $ndate->getThaiShortDate($event->travel_date) . ' ' . substr($event->travel_time, 0, -3) . "-" . $ndate->getThaiShortDate($event->return_date) . ' ' . substr($event->return_time, 0, -3);
                                    }
                                    $limit_text = (ceil($last_position / 7.1428)) * 20;
                                    $txt_title = mb_substr($event->title, 0, $limit_text, 'UTF-8');
                                    echo '<br/><a data-toggle="modal" data-remote="false" data-target="#myModal" href="https://it.nkc.kku.ac.th/dev/car/default/view?id=' . $event->id . '">' . $txt_title . '...</a>';
                                    echo "</div>";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="jqs-grid">
                <div class="jqs-grid-head">
                    <?php
                    do {
                        echo "<div class=\"jqs-grid-day\"><b>{$tday[$dt->format('l')]}</b><br/>{$ndate->getThaiShortDate ($dt->format('Y-m-d'))}</div>\n";
                        $dt->modify('+1 day');
                    } while ($week == $dt->format('W'));
                    ?>                     
                </div>
                <?php
                foreach ($cars as $c):
                    ?>
                    <div class="jqs-grid-line">
                        <div class="jqs-grid-hour"><?= $c->register_id . '<br/>' . $c->brand_model ?></div>
                        <div class="jqs-grid-column-container">
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                            <div class="jqs-grid-column"></div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="modal-title">รายละเอียดการจัดรถยนต์</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-close "></i></span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
<!--            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->