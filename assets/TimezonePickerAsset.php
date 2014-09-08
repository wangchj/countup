<?php
/**
 * @link http://codenuggets.com/
 * @copyright Copyright (c) 2014 codenuggets.com
 */

namespace app\assets;

use yii\web\AssetBundle;

class TimezonePickerAsset extends AssetBundle
{
    public $basePath = '@webroot/com/timezonepicker';
    public $baseUrl = '@web/com/timezonepicker';
    public $css = [
    ];
    public $js = [
        'http://code.jquery.com/jquery-migrate-1.2.1.min.js',
        'lib/jquery.maphilight.min.js',
        'lib/jquery.timezone-picker.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
