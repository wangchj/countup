<?php

namespace app\assets;

use yii\web\AssetBundle;

class DatejsAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $js = ['date.js'];
}
