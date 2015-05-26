<?php
namespace app\assets;

use yii\web\AssetBundle;

class MarkDateModalAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $css = [];
    public $js = ['mark-date-modal.js'];
    public $depends = [
        'app\assets\DatejsAsset',
        'app\assets\JqueryUiAsset'
    ];
}
