<?php
/**
 * Assets for the user sign-up page.
 * @link http://codenuggets.com/
 * @copyright Copyright (c) 2014 codenuggets.com
 */

namespace app\assets;

use yii\web\AssetBundle;

class SignupAsset extends AssetBundle
{
    public $sourcePath = '@app/views/user';
    public $css = [];
    public $js = ['signup.js'];
    public $depends = ['app\assets\TimezonePickerAsset'];
}
