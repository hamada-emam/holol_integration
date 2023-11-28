<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>
            <h4 class="modal-title"><b>Add Punishment</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('punishment.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="min_period" class="col-sm-5 control-label">Min Period in minutes</label>

                        <div class="bootstrap-numpicker">
                            <input type="number" min="1" class="form-control" id="min_period" name="min_period"
                                required>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="max_period" class="col-sm-5 control-label">Max Period in minutes</label>

                        <div class="bootstrap-numpicker">
                            <input type="number" min="1" class="form-control" id="max_period" name="max_period"
                                required>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-5 control-label">Punishment in pounds</label>
                        <div class="bootstrap-numpicker">
                            <input type="number" min="1" class="form-control" id="amount" name="amount"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="active" class="col-sm-3 control-label">Active</label>
                        <div class="bootstrap-timepicker">
                            <select class="form-control" id="active" name="active" required>
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
