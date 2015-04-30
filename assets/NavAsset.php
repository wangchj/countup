<?php
/**
 * Assets for the user sign-up page.
 * @link http://codenuggets.com/
 * @copyright Copyright (c) 2014 codenuggets.com
 */

namespace app\assets;

use yii\web\AssetBundle;

class NavAsset extends AssetBundle
{
    public $sourcePath = '@app/views/layouts';
    public $css = [];
    public $js = ['navbar.js'];
    public $depends = ['app\assets\CounterAddAsset'];
}
