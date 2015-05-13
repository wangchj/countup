<?php
namespace app\assets;

use yii\web\AssetBundle;

class CounterFormModalAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $css = ['counter-form-modal.css'];
    public $js = ['counter-form-modal.js'];
    public $depends = [
        'app\assets\DatejsAsset',
        'app\assets\JqueryUiAsset'
    ];
}
