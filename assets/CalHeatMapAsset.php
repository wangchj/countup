<?php
namespace app\assets;

class CalHeatMapAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@npm/cal-heatmap/';
    public $css = ['cal-heatmap.css'];
    public $js = ['cal-heatmap.min.js'];
    public $depends = ['app\assets\D3Asset'];
}