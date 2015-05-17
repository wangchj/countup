<?php

namespace app\assets;

use yii\web\AssetBundle;

class BlueimpFileUploadAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload/';
    public $css = ['css/jquery.fileupload.css'];
    public $js = ['js/jquery.fileupload.js'];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\JqueryUiAsset'
    ];
}