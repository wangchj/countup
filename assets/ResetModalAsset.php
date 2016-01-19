<?php
namespace app\assets;

use yii\web\AssetBundle;

class ResetModalAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $css = [];
    public $js = ['ResetModal.js'];
    public $depends = [
        'app\assets\DatejsAsset',
        'app\assets\JqueryUiAsset'
    ];
}
