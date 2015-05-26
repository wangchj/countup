<div id="mark-date-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Mark Date</b></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" style="display:none"></div>
                <form id="mark-date-form">
                    <input type="hidden" id="counter-counterid" name="Counter[counterId]">
                    <div class="form-group field-counter-label required">
                        <label class="control-label" for="counter-label">Action</label>
                        <select id="mark-date-modal-action" class="form-control" name="action">
                            <option value="1">Mark Done</option>
                            <option value="2">Mark Miss</option>
                            <option value="0">Clear</option>
                        </select>
                    </div>

                    <div class="form-group field-date">
                        <label class="control-label" for="mark-date-modal-date">Date</label>
                        <input id="mark-date-modal-date" class="form-control" name="date" placeholder="YYYY-MM-DD">
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