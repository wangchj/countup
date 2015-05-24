<?php

namespace app\assets;

use yii\web\AssetBundle;

class BlueimpFileUploadAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
        'blueimp-file-upload/css/jquery.fileupload.css'
    ];
    public $js = [
        'blueimp-load-image/js/load-image.all.min.js',
        'blueimp-file-upload/js/jquery.fileupload.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\JqueryUiAsset'
    ];
}