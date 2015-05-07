<?php

namespace app\assets;

use yii\web\AssetBundle;

class SortableAsset extends AssetBundle
{
    public $sourcePath = '@bower/Sortable/';
    public $css = [];
    public $js = ['Sortable.min.js'];
    public $depends = [];
}