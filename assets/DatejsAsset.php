<?php

namespace app\assets;

use yii\web\AssetBundle;

class DatejsAsset extends AssetBundle
{
    public $sourcePath = '@bower/Datejs/build';
    public $js = ['date.js'];
}
