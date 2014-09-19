<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<style>
.button{
border-radius: .1875em;
padding:4px;
margin:0px 4px;
box-shadow: inset 0 2px 2px #fff,0 0 0 1px #bbb,0 1px 1px #aaa;
}
</style>

<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-9">
    <img src="http://www.k2g2.org/lib/plugins/avatar/stitchy/stitchy.php?seed=<?=md5($user->userName)?>&size=50&.png" />
    <span style="font-size:36px;margin-left:15px;vertical-align:middle"><?=$user->userName?></span>
</div></div>

<div class="row" style="margin-top:20px">
    <div class="col-xs-3">
        <ul class="list-group">
            <a href="#" class="list-group-item">History</a>
            <a href="#" class="list-group-item">New Counter</a>
        </ul>
    </div>

    <div class="col-xs-9">
        <table class="table table-striped">
            <tr style="border-style:none">
                <td style="border-style:none"></td>
                <td style="border-style:none">Count</td>
                <td style="border-style:none">Since</td>
            </tr>
<?php 
foreach($counters as $counter): 
    $interval = $counter->getDateInterval();
?>

            <tr>
                <th>
                    <a href="<?=Url::to(['counter/view', 'id'=>$counter->counterId])?>"><?php echo Html::encode($counter->label)?></a>
                </th>
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
            </tr>
<?php endforeach;?>
        </table>
    </div>
</div><!-- end div row -->

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