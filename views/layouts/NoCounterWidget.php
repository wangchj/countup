<div class="row" style="
    /*border:1px solid gray;*/
    background-color:#fff;
    padding:20px;
    margin-top:20px;
    font-size:18px;
    font-weight:bold;
    color:#808080;
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.22);
    border-radius: 6px;
    text-align:center">
    <div class="col-xs-12">
        <?php if($viewer->userId == $viewee->userId) : ?>
            You currently have no counters. Click on Add Counter to add one!
        <?php else: ?>
            <?=$viewee->forename?> currently have no counters.
        <?php endif;?>
    </div>
</div>