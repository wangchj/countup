<?php

use yii\helpers\Url;
use yii\web\AssetBundle;
//use app\views\site\HomeAsset;

class HomeAsset extends AssetBundle
{
    public $sourcePath = '@app/views/site';
    public $css = [
        //'home.css',
    ];
    public $js = ['home.js'];
    public $depends = [
        'app\assets\SnapsvgAsset',
        'yii\web\JqueryAsset',
    ];
}

class ColorShifter {
    private $R = 0, $G = 1, $B = 2;     //Color constant code
    private $c;                    //Color that is currently changing
    private $t = 1;
    private $ar = 0, $ag = 30, $ab = 30; //Current angle for r, g, b.
    private $div;                       //How many portion to divide 90 degrees
    private $diff;                      //Degrees per portion
    private $min;
    private $max;

    public function __construct($d=25, $min=180, $max=255) {
        //150-200, 180-255
        $this->c = $this->R;
        $this->div = $d;
        $this->diff = 90 / $this->div;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Change to next hue. Return nothing. Call get getColor() to get a color.
     */
    public function changeHue() {
        if($this->c == $this->R)
            $this->ar += $this->diff;
        else if($this->c == $this->G)
            $this->ag += $this->diff;
        else
            $this->ab += $this->diff;

        if($this->t == $this->div)
            $this->c = ($this->c + 1) % 3;

        $this->t = ($this->t + 1) % ($this->div + 1);
    }

    /**
     * Return HTML color code.
     */
    public function getColor($level) {
        $r = sin(deg2rad($this->ar)) * ($this->max - $this->min) + $this->min;
        $g = sin(deg2rad($this->ag)) * ($this->max - $this->min) + $this->min;
        $b = sin(deg2rad($this->ab)) * ($this->max - $this->min) + $this->min;

        $level_fact = 15;

        $r += -1 * $level * $level_fact;
        $g += -1 * $level * $level_fact;
        $b += -1 * $level * $level_fact;

        return $this->rgb2html($r, $g, $b);
    }

    public static function rgb2html($r, $g=-1, $b=-1)
    {
        if (is_array($r) && sizeof($r) == 3)
            list($r, $g, $b) = $r;

        $r = intval($r); $g = intval($g);
        $b = intval($b);

        $r = dechex($r<0?0:($r>255?255:$r));
        $g = dechex($g<0?0:($g>255?255:$g));
        $b = dechex($b<0?0:($b>255?255:$b));

        $color = (strlen($r) < 2?'0':'').$r;
        $color .= (strlen($g) < 2?'0':'').$g;
        $color .= (strlen($b) < 2?'0':'').$b;
        return '#'.$color;
    }  
}

HomeAsset::register($this);

?>
<style>
.navbar .nav li a {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 16px;
    font-weight:bold;
    color:#505050;
    /*opacity:0.5;*/
    /*text-shadow:1px 1px 8px #AAA;*/
}

.navbar .nav li a:hover {
    background-color:transparent;
}

.navbar a.navbar-brand {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 24px;
    font-weight:bold;
    color:#505050;
    /*opacity:0.5;*/
    /*text-shadow:-1px -1px 0px #AAA;*/
}

body {
    background-color: #e9e9e9;
}

nav.navbar {
    margin-top:0px;
    margin-bottom:0px;
    background-color: #fff;
    border-color: #fff;
    padding-top:10px;
    padding-bottom:10px;
}
</style>

<div id="header-wrap" style="
    /*border:1px solid black;*/
    box-shadow:0px 1px 5px #808080;
    padding-top:0px;
    padding-bottom:0px;
    margin-bottom:20px">
    <nav id="w1" class="navbar" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">CountUp</a>
            </div>
            <div id="w1-collapse" class="collapse navbar-collapse">
                <ul id="w2" class="navbar-nav navbar-right nav">
                    <li><a href="<?=Url::to(['counter/add'])?>" data-method="post">Add Counter</a></li>
                    <li><a href="<?=Url::to(['user/settings'])?>" data-method="post">Settings</a></li>
                    <li><a href="<?=Url::to(['site/logout'])?>" data-method="post">Logout</a></li>
                    <li>
                        <img src="<?=Yii::$app->user->identity->picture?>"
                            title="Logged in as <?=Yii::$app->user->identity->forename?>"
                            style="width:40px; margin-top:6px"
                            class="img-circle">
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container">
    
<?php if(!Yii::$app->user->identity->counters): ?>
    <div class="row" style="
        /*border:1px solid gray;*/
        background-color:#fff;
        padding:20px;
        font-size:18px;
        font-weight:bold;
        color:#808080;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);
        border-radius: 6px;">
        <div class="col-sm-12 col-md-12 col-lg-12" style="text-align:center;">
            You currently have no counters. Click on Add Counter to add one!
        </div>
    </div>
<?php else: ?><?php foreach(Yii::$app->user->identity->counters as $counter):?>
    <div class="row" style="
        /*border:1px solid gray;*/
        background-color:#fff;
        padding:20px;
        font-size:18px;
        font-weight:bold;
        color:#505050;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);
        border-radius: 6px;
        margin-bottom:20px;
        ">
        <center>
        <div class="col-sm-12 col-md-12 col-lg-12" style="">
            <div class="row clabel">
                <div class="col-md-12 col-lg-12" style=""><?=$counter->label?></div>
            </div>
            <hr>
            <div class="row">
                <!--div class="col-md-3 col-lg-3"><?=$counter->startDate?></div -->
                <div class="col-md-3 col-lg-3 col-md-offset-3"><?=$counter->getDays()?> Days</div>
                <div class="col-md-3 col-lg-3"><?=$counter->longest?> Days</div>
            </div>
            <hr>
            <div class="row cimg">
                <div class="col-md-12 col-lg-12">
                    <?php
                        $w = 10; //Rec width in pixels
                        $s = 2;  //Spacing in pixels
                        $a = $w * 51 + $s * 51;
                        $b = $w * 6 + $s * 6;

                        $cs = new ColorShifter();
                    ?>
                    <svg width="<?=$a?>" height="<?=$b?>" style="visibility:visible;opacity:.8">
                        <?php for($col = 0; $col < 52; $col++): ?>
                            <?php for($row = 0; $row < 7; $row++): ?>
                                <?php
                                    $id = $col * 7 + $row;
                                    $x  = $col * $w + $col * $s; //Horizontal position
                                    $y  = $row * $w + $row * $s; //Vertical position
                                    $co = $counter->getDays() >= $id + 1 ? $cs->getColor($row) : '#eeeeee';
                                ?>
                                <rect id="d<?=$id?>" width="<?=$w?>" height="<?=$w?>" x="<?=$x?>" y="<?=$y?>" fill="<?=$co?>" data-s="<?=$id?>" rx="2", ry="2" />
                            <?php endfor;?>
                            <?php $cs->changeHue();?>
                        <?php endfor;?>
                    </svg>
                </div>
            </div>
        </div>
        </center>
    </div>
<?php endforeach;?><?php endif;?>
</div>
