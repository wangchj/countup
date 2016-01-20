<?php
namespace app\assets;

use yii\web\AssetBundle;

class StopModalAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $css = [];
    public $js = ['StopModal.js'];
    public $depends = [
        'app\assets\DatejsAsset',
        'app\assets\JqueryUiAsset'
    ];
}
