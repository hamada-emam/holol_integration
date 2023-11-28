<!-- Edit -->
<div class="modal fade" id="edit{{ $punishment->id }}">
    <div class=" modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>
            <h4 class="modal-title"><b>Update Punishment</b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('punishment.update', $punishment->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $punishment->id }}">

                    <div class="form-group">
                        <label for="edit_min_period" class="col-sm-3 control-label">Min Period</label>
                        <div class="">
                            <input type="number" min="1" class="form-control timepicker" id="edit_min_period"
                                name="min_period" value="{{ $punishment->min_period }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_max_period" class="col-sm-3 control-label">Min Period</label>
                        <div class="">
                            <input type="number" min="1" class="form-control timepicker" id="edit_max_period"
                                name="max_period" value="{{ $punishment->max_period }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_punishment" class="col-sm-3 control-label">Punishment</label>
                        <div class="">
                            <input type="number" min="1" class="form-control timepicker" id="edit_punishment"
                                name="amount" value="{{ $punishment->amount }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="active" class="col-sm-3 control-label">Active</label>
                        <div class="bootstrap-timepicker">
                            <select class="form-control" id="active" name="active" required>
                                <option value="true" {{ $punishment->active ? 'selected' : '' }}>True</option>
                                <option value="false" {{ !$punishment->active ? 'selected' : '' }}>False</option>
                            </select>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-check-square-o"></i>
                    Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $punishment->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">

                <h4 class="modal-title "><span class="employee_id">Delete punishment</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST"
                    action="{{ route('punishment.destroy', $punishment->id) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>Are you sure you want to delete:</h6>
                        <h2 class="bold del_employee_name">{{ $punishment->id }}</h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
