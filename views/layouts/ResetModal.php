<div id="reset-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Reset Counter</b></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" style="display:none"></div>
                <form id="reset-form">
                    <input type="hidden" id="counter-counterid" name="Counter[counterId]">

                    <div class="form-group field-date">
                        <label class="control-label" for="reset-modal-date">Reset Date</label>
                        <input id="reset-modal-date" class="form-control" name="resetDate" placeholder="YYYY-MM-DD">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->