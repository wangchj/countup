<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<style>
.button{
border-radius: .1875em;
padding:4px;
margin:0px 4px;
box-shadow: inset 0 2px 2px #fff,0 0 0 1px #bbb,0 1px 1px #aaa;
}
</style>

<table class="table"><tr><th></th><th>Count</th><th>Since</th><th></th></tr>
<?php 
foreach($counters as $counter): 
    $interval = $counter->getDateInterval();
?>

    <tr>
        <th><?php echo Html::encode($counter->label)?></th>
        <td><span style="
            background-image:url('<?=Yii::getAlias('@web')?>/images/flair.png');
            background-repeat:no-repeat;
            background-position:<?=getImageCoord($interval)?>;
            display:inline-block;
            height: 20px;
            width: 20px;
            margin-right: 4px;
            vertical-align: middle;"></span>
            <span><?= getCountStr($interval)?></span>
        </td>
        <td><?= $counter->startDate?></td>
        <td align="right"><img src="<?=Yii::getAlias('@web')?>/images/reset.png" class="button"/> <img src="<?=Yii::getAlias('@web')?>/images/x.png" class="button"/></td>
    </tr>
<?php endforeach;?>
</table>

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
        $result = $result . ' (' . $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ')';
    return $result;
}

/**
 * Badge image coordinates.
 * @param $interval DateInterval object.
 * @return a string with the format Xpx Ypx, where X and Y are coordinates.
 */
function getImageCoord($interval)
{
    if($interval->days < 7) return '0px 0px';

    //Weeks
    if($interval->days < 30)
    {
        $weeks = (int)($interval->days / 7);
        return '0px ' . (-20 * $weeks) . 'px';
    }

    //Months
    if($interval->y < 1)
    {
        return '0px ' . (-101 - ($interval->m - 1) * 20) . 'px';
    }

    return '0px -318px';
}
?>