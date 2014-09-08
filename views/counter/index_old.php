<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<style>
</style>

<div style="text-align:center">
<?php 
$img = [
    'http://131.204.27.60/~wangchj/countup/web/images/bulb.png',
    'http://131.204.27.60/~wangchj/countup/web/images/smile.png',
    'http://131.204.27.60/~wangchj/countup/web/images/suitcase.png',
    'http://131.204.27.60/~wangchj/countup/web/images/surf.png'
];

foreach($counters as $counter): 
    $interval = $counter->getDateInterval();
?>
    <div class="counter" style="display:inline-block;margin:20px;text-align:center;border:0px solid black;">
        <img src="<?=$img[rand(0,3)]?>" style="margin:10px"/>
        <div class="l" style="font-size:18px"><?php echo Html::encode($counter->label)?></div>
        <div class="d" style="font-size:16px"><?= getCountStr($interval)?></div>
    </div>
<?php endforeach;?>
</div>

<?php

/**
 * Get formatted string of day count.
 * @param $interval DateInterval object
 * @return a string.
 */
function getCountStr($interval)
{
    $result = $interval->days . ' days';
    if($interval->y > 0)
        $result = $result . ' (' . $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ')';
    else if($interval->m > 0)
        $result = $result . ' (' . $interval->m . ' year' . ($interval->m > 1 ? 's' : '') . ')';
    return $result;
}
?>