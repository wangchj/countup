<?php
/**
 * @link http://codenuggets.com/
 * @copyright Copyright (c) 2014 codenuggets.com
 */

namespace app\assets;

use yii\web\AssetBundle;

class JqueryUiAsset extends AssetBundle
{
    public $css = ['http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css'];
    public $js =  ['http://code.jquery.com/ui/1.11.1/jquery-ui.js'];
    public $depends = ['yii\web\JqueryAsset'];
}