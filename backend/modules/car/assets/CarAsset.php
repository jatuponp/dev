<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\modules\car\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CarAsset extends AssetBundle {

    public $sourcePath = '@app/modules/car/assets';
    public $css = ['css/timetable.css', 'css/jquery.schedule.css']; // Path to admin.css file : $sourcePath/css/admin.css
    public $js = ['js/jquery.carschedule.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];

}
