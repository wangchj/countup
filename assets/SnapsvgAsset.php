<?php
/**
 * @link http://codenuggets.com/
 * @copyright Copyright (c) 2014 codenuggets.com
 */

namespace app\assets;

use yii\web\AssetBundle;

class SnapsvgAsset extends AssetBundle
{
    public $sourcePath = '@npm/snapsvg/dist/';
    public $css = [];
    public $js = ['snap.svg-min.js'];
    public $depends = [];
}