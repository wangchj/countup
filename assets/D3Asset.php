<?php
namespace app\assets;

class D3Asset extends \yii\web\AssetBundle
{
    public $sourcePath = '@npm/d3/';
    public $css = [];
    public $js = ['d3.min.js'];
    public $depends = [];
}