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
    public $js = [
        'signup.js',
        'https://maps.googleapis.com/maps/api/js?key=[api_key]&libraries=geometry&sensor=false'
    ];
    public $depends = ['app\assets\TimezonePickerAsset'];
}
