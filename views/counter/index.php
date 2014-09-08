<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<style>
.cdays:before
{
    content: "";
    background-position: 0 0px;
    background: url("<?=Yii::getAlias('@web')?>/images/flair.png") no-repeat scroll 0px 0px transparent;
    display: inline-block;
    height: 20px;
    margin-right: 4px;
    vertical-align: middle;
    width: 20px;
}
</style>

<?php 
foreach($counters as $counter): 
    $interval = $counter->getDateInterval();
?>
    <div class="count" style="
        float:left; width:100%;
        padding:20px 1px;
        border-bottom:1px dashed gray;
        ">
        
        <div class="chead" style="
            width:30%;
            float:left;
            overflow: hidden;
            border:0px solid blue">
            <div class="clabel" style="/*font-size:18px*/"><?php echo Html::encode($counter->label)?></div>
            <div class="cdays" style="/*font-size:16px*/"><?= getCountStr($interval)?></div>
        </div>
        
        <div class="csince" style="
            width:30%;
            margin:0 auto;
            float:left;
            text-align:center;
            border:0px solid blue">
            <div>Since</div>
            <div class="" style=""><?= $counter->startDate?></div>
        </div>

        <div style="float:right;border:0px solid blue;top:50%">
            <div class="" style="line-height:40px"><?= $counter->startDate?></div>
        </div>
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