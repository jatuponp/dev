<?php

use yii\helpers\Url;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'รายการรถยนต์ที่เดินทางวันเดียวกัน';
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th width="10%">#</th>
            <th width="75%">รายการ</th>
            <th width="15%"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($model as $r):
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $r->objective ?></td>
                <td class="text-right">
                    <?php if (count($model) > 1): ?>
                        <a href="<?= Url::to(['cancelmany', 'id' => $r->id]) ?>" class="btn btn-danger">ยกเลิก</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php $i++;
        endforeach;
        ?>
    </tbody>
</table>
