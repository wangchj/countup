<?php
/**
 * Assets for the user sign-up page.
 * @link http://codenuggets.com/
 * @copyright Copyright (c) 2014 codenuggets.com
 */

namespace app\assets;

use yii\web\AssetBundle;

class CounterAddAsset extends AssetBundle
{
    public $sourcePath = '@app/views/counter';
    public $css = [];
    public $js = ['_form.js'];
    public $depends = ['app\assets\JqueryUiAsset'];
}
