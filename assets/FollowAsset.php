<?php

namespace app\assets;

use yii\web\AssetBundle;

class FollowAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $css = ['follow.css'];
    public $js = ['follow.js'];
    public $depends = [];
}
