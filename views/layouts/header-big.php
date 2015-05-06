<style>
body {
    background-color: #e9e9e9;
}

h1 {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 20px;
    font-weight:bold;
    color:#505050;
    margin-top:0px;
}

.followBtn {
    font-family: proxima-nova, proxima-nova, Helvetica, Arial, sans-serif;
    font-size: 12px;
    font-weight:bold;
    color:#505050;
}

</style>

<div id="header-wrap" style="
    /*border:1px solid black;*/
    box-shadow:0px 1px 5px #808080;
    padding-top:0px;
    padding-bottom:0px;
    margin-bottom:20px;
    background-color:#fff">
    
    <?php echo $this->render('@app/views/layouts/navbar.php', ['bigHeader'=>true]);?>

    <?php if(Yii::$app->controller->id == 'user' && Yii::$app->controller->action->id == 'index'):?>
    <hr style="margin:10px">

    <div class="container">
        <div class="row">
            <div class="col-xs-12" style="text-align:center">
                <h1>
                    <img src="<?=$viewee->getPicture()?>" style="width:50px; margin-top:6px" class="img-circle">
                    <?="$viewee->forename $viewee->surname"?>
                    <button class="btn btn-default followBtn pull-right" style="margin-top:12px">Follow</button>
                </h1>    
            </div>
        </div><!-- row -->
    </div><!-- container -->
    <?php endif;?>
</div>