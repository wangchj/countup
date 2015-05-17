<?php

namespace app\assets;

use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle
{
    public $sourcePath = '@bower/dropzone/dist';
    public $css = [];
    public $js = ['dropzone.js'];
}