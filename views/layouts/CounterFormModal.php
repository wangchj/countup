<div id="add-counter-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>New Counter</b></h4>
            </div>
            <div class="modal-body">
                <form id="counter-form">
                    <input type="hidden" id="counter-counterid" name="Counter[counterId]">
                    <div class="form-group field-counter-label required">
                        <label class="control-label" for="counter-label">Label</label>
                        <input type="text" id="counter-label" class="form-control" name="Counter[label]" maxlength="30">
                    </div>

                    <div class="form-group field-counter-summary">
                        <label class="control-label" for="counter-summary">Summary</label>
                        <textarea id="counter-summary" class="form-control" name="Counter[summary]"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-xs-5">
                            <div class="form-group field-counter-startdate required">
                                <label class="control-label" for="counter-startdate">Starting</label>
                                <input type="text" id="counter-startdate" class="form-control" name="Counter[startDate]">
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <div class="form-group field-counter-timezone required">
                                <label class="control-label" for="counter-timezone">Time Zone</label>
                                <select id="counter-timezone" class="form-control" name="Counter[timeZone]">
                                <?php foreach(DateTimeZone::listIdentifiers() as $timezone):?>
                                    <option value="<?=$timezone?>"<?=Yii::$app->user->identity->timeZone == $timezone ? 'selected' : ''?>><?=$timezone?></option>
                                <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-5">
                            <div class="form-group field-counter-startdate required">
                                <!-- input type="hidden" name="Counter[public]" value="0" -->
                                <label><input type="checkbox" id="counter-public" name="Counter[public]" value="1"> Public</label>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <!-- button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button-->
                <button type="button" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->