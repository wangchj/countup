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
                    <?php if($viewee->picture): ?>
                        <img src="<?=$viewee->getPicture()?>" style="width:50px; margin-top:6px" class="img-circle">
                    <?php else: ?>
                        <img src="<?=$viewee->getPicture()?>" style="width:50px; margin-top:6px; border-width:3px; border-color:#eee" class="img-circle img-thumbnail">
                    <?php endif;?>
                    <?="$viewee->forename $viewee->surname"?>
                    <?php if($viewer->userId != $viewee->userId):?>
                        <?php if($viewer->isFollowerOf($viewee->userId)):?>
                            <button class="btn btn-default btn-unfollow pull-right" user-id="<?=$viewee->userId?>" style="">Unfollow</button>
                        <?php else:?>
                            <button class="btn btn-default btn-follow pull-right" user-id="<?=$viewee->userId?>" style="margin-top:12px">Follow</button>
                        <?php endif;?>
                    <?php endif;?>
                </h1>    
            </div>
        </div><!-- row -->
    </div><!-- container -->
    <?php endif;?>
</div>