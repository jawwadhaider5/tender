<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Tender <strong>({{$tender->tender_number}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">


            {!! Form::open(['url' => action('TenderController@update',[$tender->id]),'method' => 'PUT',
            'id' => 'edit_tender_form','class' => '', 'enctype' => 'multipart/form-data']) !!}
            <input type="hidden" id="tender_id" value="{{ $tender->id }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">City</label>
                        <div class="col-sm-8">

                            <select name="city_id" id="city_id" class="form-control" required>
                                <option value="">Select a city</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}" @if( $tender->city_id == $city->id) selected @endif>{{ $city->name }} - {{ $city->code }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('city_id'))
                            <div class="alert  alert-danger mt-3">{{ $errors->first('city_id') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Select Client</label>
                        <div class="col-sm-8">

                            <select name="client_id" id="client_id" class="form-control">
                                <option value="">Select a Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" @if( $tender->client_id == $client->id) selected @endif>{{ $client->company_name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('client_id'))
                            <div class="alert  alert-danger mt-3">{{ $errors->first('client_id') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Tender Number</label>
                        <div class="col-sm-8">
                            {!! Form::text('tender_number', $tender->tender_number , array('placeholder' => 'Tender Number','class' => 'form-control', 'required')) !!}

                            @if ($errors->has('tender_number'))
                            <div class="alert  alert-danger mt-3">{{ $errors->first('tender_number') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label">Year</label>
                        <div class="col-sm-8">
                            {!! Form::date('year', $tender->year, array('placeholder' => 'Year','class' => 'form-control', 'readonly', 'id'=>'year_edit')) !!}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Description</label>
                        <div class="col-sm-8">
                            <textarea name="description" id="" class="form-control" rows="3" required>{{$tender->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Status</label>
                        <div class="col-sm-8">
                            <select name="status" id="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="approved" @if($tender->status == 'approved') selected @endif>Approved</option>
                                <option value="not approved" @if($tender->status == 'not approved') selected @endif>Not Approved</option>
                                <option value="pending" @if($tender->status == 'pending') selected @endif>Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label">Assigned No#</label>
                        <div class="col-sm-8"> 
                            <select name="assigned_number" class="form-control">
                                <option value="">Assigned Number</option>
                                <option value="1" @if($tender->assigned_number == '1') selected @endif>1</option>
                                <option value="2" @if($tender->assigned_number == '2') selected @endif>2</option>
                                <option value="3" @if($tender->assigned_number == '3') selected @endif>3</option>
                                <option value="4" @if($tender->assigned_number == '4') selected @endif>4</option>
                                <option value="5" @if($tender->assigned_number == '5') selected @endif>5</option>
                                <option value="6" @if($tender->assigned_number == '6') selected @endif>6</option>
                                <option value="7" @if($tender->assigned_number == '7') selected @endif>7</option>
                                <option value="8" @if($tender->assigned_number == '8') selected @endif>8</option>
                                <option value="9" @if($tender->assigned_number == '9') selected @endif>9</option>
                                <option value="10" @if($tender->assigned_number == '10') selected @endif>10</option>
                                <option value="11" @if($tender->assigned_number == '11') selected @endif>11</option>
                                <option value="12" @if($tender->assigned_number == '12') selected @endif>12</option>
                                <option value="13" @if($tender->assigned_number == '13') selected @endif>13</option>
                                <option value="14" @if($tender->assigned_number == '14') selected @endif>14</option>
                                <option value="15" @if($tender->assigned_number == '15') selected @endif>15</option>
                                <option value="16" @if($tender->assigned_number == '16') selected @endif>16</option>
                                <option value="17" @if($tender->assigned_number == '17') selected @endif>17</option>
                                <option value="18" @if($tender->assigned_number == '18') selected @endif>18</option>
                                <option value="19" @if($tender->assigned_number == '19') selected @endif>19</option>
                                <option value="20" @if($tender->assigned_number == '20') selected @endif>20</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Start Date</label>
                        <div class="col-sm-8">
                            {!! Form::date('start_date', $tender->start_date, array('placeholder' => 'Start Date','class' => 'form-control')) !!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Close Date</label>
                        <div class="col-sm-8">
                            {!! Form::date('close_date', $tender->close_date, array('placeholder' => 'Close Date','class' => 'form-control')) !!}

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Announce Date</label>
                        <div class="col-sm-8">
                            {!! Form::date('announce_date', $tender->announce_date, array('placeholder' => 'Announce Date','class' => 'form-control')) !!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Submit Date</label>
                        <div class="col-sm-8">
                            {!! Form::date('submit_date', $tender->submit_date, array('placeholder' => 'Submit Date','class' => 'form-control', 'id'=>'submit_date_edit')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Time Period</label>
                        <div class="col-sm-8">
                            {!! Form::text('period', $tender->period, array('placeholder' => 'Time Period','class' => 'form-control')) !!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Period Term</label>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Amount</label>
                        <div class="col-sm-8">
                            {!! Form::text('amount', $tender->amount, array('placeholder' => 'Amount','class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="col-md-8 offset-md-3">
                        <input type="hidden" name="submit_type" id="submit_type">
                        <button type="submit" value="submit" class="btn btn-primary submit_tender_form me-2 float-end">Update</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
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