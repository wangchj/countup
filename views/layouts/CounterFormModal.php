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

                    <div class="form-group">
                        <a href="#new-counter-more" id="new-counter-more-link" data-toggle="collapse" aria-expanded="false" aria-controls="new-counter-more">More options</a>
                    </div>

                    <div id="new-counter-more" class="collapse">

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group field-counter-type required">
                                        <input type="hidden" id="counter-type" name="Counter[type]" value="daily">
                                        <!-- label class="control-label" for="counter-type" style="margin-right:10px">Type</label -->
                                        <ul class="box-select" style="margin-right:20px">
                                            <li id="type-select-item-daily" class="type-select-item active"><a href="#">Daily</a></li>
                                            <li id="type-select-item-weekly" class="type-select-item"><a href="#">Weekly</a></li>
                                        </ul>

                                        <div id="counter-option-daily" style="display:inline">
                                            every
                                            <input type="text" id="counter-every" name="Counter[every]" value="1" class="form-control"
                                                style="display:inline;width:35px; padding:2px 6px; height:28px; margin-left:15px; margin-right:15px;text-align:center"
                                                maxlength="1">
                                            <span id="every-label">day</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="weekly-day-select" style="display:none">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">On</label>
                                        <div>
                                        <ul class="box-select" style="margin-right:20px">
                                            <li>Mon</li>
                                            <li>Tue</li>
                                            <li>Wed</li>
                                            <li>Thu</li>
                                            <li>Fri</li>
                                            <li>Sat</li>
                                            <li>Sun</li>
                                        </ul>
                                        <input type="hidden" id="day-mon" name="day[mon]">
                                        <input type="hidden" id="day-tue" name="day[tue]">
                                        <input type="hidden" id="day-wed" name="day[wed]">
                                        <input type="hidden" id="day-thu" name="day[thu]">
                                        <input type="hidden" id="day-fri" name="day[fri]">
                                        <input type="hidden" id="day-sat" name="day[sat]">
                                        <input type="hidden" id="day-sun" name="day[sun]">
                                        </div>
                                    </div>
                                </div>
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

                        </div><!-- container-fluid --> 
                    </div> <!-- collapse -->

                </form>
            </div>
            <div class="modal-footer">
                <!-- button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button-->
                <button type="button" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->