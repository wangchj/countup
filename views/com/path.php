<?php
$homeUrl = Yii::$app->homeUrl;
$stop = strrpos($homeUrl, '/');
$comUrl = substr($homeUrl, 0, $stop + 1) . 'com/';
$jsUrl = substr($homeUrl, 0, $stop + 1) . 'js/';
?>