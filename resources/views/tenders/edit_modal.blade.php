<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Tender <strong>({{$tender->tender_number}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('tenders.update', $tender->id) }}" method="POST" id="edit_tender_form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="tender_id" value="{{ $tender->id }}">

                <!-- City (full row) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row mb-1">
                            <label class="col-sm-2 col-form-label required">City</label>
                            <div class="col-sm-8">
                                <select name="city_id" id="city_id" class="form-control" required>
                                    <option value="">Select a city</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @if( $tender->city_id == $city->id) selected @endif>{{ $city->name }} - {{ $city->code }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('city_id'))
                                <div class="alert alert-danger mt-3">{{ $errors->first('city_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Select Client (full row) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row mb-1">
                            <label class="col-sm-2 col-form-label required">Select Client</label>
                            <div class="col-sm-8">
                                <select name="client_id" id="client_id" class="form-control" required>
                                    <option value="">Select a Client</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @if( $tender->client_id == $client->id) selected @endif>{{ $client->company_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('client_id'))
                                <div class="alert alert-danger mt-3">{{ $errors->first('client_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tender Number + Year -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label required">Tender Number</label>
                            <div class="col-sm-8">
                                <input type="text" name="tender_number" value="{{ $tender->tender_number }}" placeholder="Tender Number" class="form-control" required>
                                @if ($errors->has('tender_number'))
                                <div class="alert alert-danger mt-3">{{ $errors->first('tender_number') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label ps-4">Year</label>
                            <div class="col-sm-8">
                                <input type="date" name="year" value="{{ $tender->year }}" placeholder="Year" class="form-control" readonly id="year_edit">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description (full row) -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row mb-1">
                            <label class="col-sm-2 col-form-label required">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" class="form-control" rows="3" required>{{ $tender->description }}</textarea>
                                @if ($errors->has('description'))
                                <div class="alert alert-danger mt-3">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status + Assigned No# -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label required">Status</label>
                            <div class="col-sm-8">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="approved" @if($tender->status == 'approved') selected @endif>Approved</option>
                                    <option value="not approved" @if($tender->status == 'not approved') selected @endif>Not Approved</option>
                                    <option value="pending" @if($tender->status == 'pending') selected @endif>Pending</option>
                                </select>
                                @if ($errors->has('status'))
                                <div class="alert alert-danger mt-3">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label ps-4">Assigned No#</label>
                            <div class="col-sm-8">
                                <select name="assigned_number" class="form-control">
                                    <option value="">Assigned Number</option>
                                    @for($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" @if($tender->assigned_number == $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Start Date + Close Date -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8">
                                <input type="date" name="start_date" value="{{ $tender->start_date }}" placeholder="Start Date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label ps-4">Close Date</label>
                            <div class="col-sm-8">
                                <input type="date" name="close_date" value="{{ $tender->close_date }}" placeholder="Close Date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announce Date + Submit Date -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label">Announce Date</label>
                            <div class="col-sm-8">
                                <input type="date" name="announce_date" value="{{ $tender->announce_date }}" placeholder="Announce Date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label ps-4">Submit Date</label>
                            <div class="col-sm-8">
                                <input type="date" name="submit_date" value="{{ $tender->submit_date }}" placeholder="Submit Date" class="form-control" id="submit_date_edit">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Period + Period Term -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label">Time Period</label>
                            <div class="col-sm-8">
                                <input type="text" name="period" value="{{ $tender->period }}" placeholder="Time Period" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label ps-4">Period Term</label>
                            <div class="col-sm-8">
                                <select name="term" id="term" class="form-control">
                                    <option value="">Select a term</option>
                                    <option value="days" @if($tender->term == 'days') selected @endif>Days</option>
                                    <option value="months" @if($tender->term == 'months') selected @endif>Months</option>
                                    <option value="years" @if($tender->term == 'years') selected @endif>Years</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amount + Select User -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label">Amount</label>
                            <div class="col-sm-8">
                                <input type="text" name="amount" value="{{ $tender->amount }}" placeholder="Amount" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-4 col-form-label ps-4">Select User</label>
                            <div class="col-sm-8">
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if($tender->user_id == $user->id) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row mb-1">
                            <div class="col-sm-8 offset-sm-2">
                                <button type="submit" class="btn btn-primary submit_tender_form me-2 float-end">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#submit_date_edit").change(function() {
            let sub_date = $(this).val();
            $('#year_edit').val(sub_date);
        });
    });
</script>