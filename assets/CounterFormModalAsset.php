<?php
namespace app\assets;

use yii\web\AssetBundle;

class CounterFormModalAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $css = [];
    public $js = ['counter-form-modal.js'];
    public $depends = ['app\assets\JqueryUiAsset'];
}
