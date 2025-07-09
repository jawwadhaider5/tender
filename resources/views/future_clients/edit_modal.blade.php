<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Future Client <strong>({{$future_client->client->company_name}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">


            {!! Form::open(['url' => route('future-clients.update', $future_client->id),'method' => 'PUT',
            'id' => 'edit_future_client_form','class' => '', 'enctype' => 'multipart/form-data']) !!}
            <input type="hidden" id="future_client_id" value="{{ $future_client->id }}">
 

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Select Client</label>
                        <div class="col-sm-8">

                            <select name="client_id" id="client_id" class="form-control">
                                <option value="">Select a Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" @if( $future_client->client_id == $client->id) selected @endif>{{ $client->company_name }}</option>
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
                <label class="col-sm-3 col-form-label required">Tender Type</label>
                <div class="col-sm-8">

                  <select name="tender_type_id" id="tender_type_id" class="form-control" required>
                    <option value="">Select a tender type</option>
                    @foreach($tender_types as $tender_type)
                    <option value="{{ $tender_type->id }}" @if( $future_client->tender_type_id == $tender_type->id) selected @endif>{{ $tender_type->name }}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('tender_type_id'))
                  <div class="alert  alert-danger mt-3">{{ $errors->first('tender_type_id') }}
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label">Assigned No#</label>
                        <div class="col-sm-8"> 
                            <select name="assigned_number" class="form-control">
                                <option value="">Assigned Number</option>
                                <option value="1" @if($future_client->assigned_number == '1') selected @endif>1</option>
                                <option value="2" @if($future_client->assigned_number == '2') selected @endif>2</option>
                                <option value="3" @if($future_client->assigned_number == '3') selected @endif>3</option>
                                <option value="4" @if($future_client->assigned_number == '4') selected @endif>4</option>
                                <option value="5" @if($future_client->assigned_number == '5') selected @endif>5</option>
                                <option value="6" @if($future_client->assigned_number == '6') selected @endif>6</option>
                                <option value="7" @if($future_client->assigned_number == '7') selected @endif>7</option>
                                <option value="8" @if($future_client->assigned_number == '8') selected @endif>8</option>
                                <option value="9" @if($future_client->assigned_number == '9') selected @endif>9</option>
                                <option value="10" @if($future_client->assigned_number == '10') selected @endif>10</option>
                                <option value="11" @if($future_client->assigned_number == '11') selected @endif>11</option>
                                <option value="12" @if($future_client->assigned_number == '12') selected @endif>12</option>
                                <option value="13" @if($future_client->assigned_number == '13') selected @endif>13</option>
                                <option value="14" @if($future_client->assigned_number == '14') selected @endif>14</option>
                                <option value="15" @if($future_client->assigned_number == '15') selected @endif>15</option>
                                <option value="16" @if($future_client->assigned_number == '16') selected @endif>16</option>
                                <option value="17" @if($future_client->assigned_number == '17') selected @endif>17</option>
                                <option value="18" @if($future_client->assigned_number == '18') selected @endif>18</option>
                                <option value="19" @if($future_client->assigned_number == '19') selected @endif>19</option>
                                <option value="20" @if($future_client->assigned_number == '20') selected @endif>20</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
 


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label required">Description</label>
                        <div class="col-sm-8">
                            <textarea name="description" id="" class="form-control" rows="3" required>{{$future_client->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Start Date</label>
                        <div class="col-sm-8">
                            {!! Form::date('start_date', $future_client->start_date, array('placeholder' => 'Start Date','class' => 'form-control')) !!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Coming Date</label>
                        <div class="col-sm-8">
                            {!! Form::date('coming_date', $future_client->coming_date, array('placeholder' => 'Coming Date','class' => 'form-control')) !!}

                        </div>
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Time Period</label>
                        <div class="col-sm-8">
                            {!! Form::text('period', $future_client->period, array('placeholder' => 'Time Period','class' => 'form-control')) !!}

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row mb-1">
                        <label class="col-sm-3 col-form-label ">Period Term</label>
                        <div class="col-sm-8">
                            <select name="term" id="term" class="form-control">
                                <option value="">Select a term</option>
                                <option value="days" @if($future_client->term == 'days') selected @endif>Days</option>
                                <option value="months" @if($future_client->term == 'months') selected @endif>Months</option>
                                <option value="years" @if($future_client->term == 'years') selected @endif>Years</option>
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
                            {!! Form::text('amount', $future_client->amount, array('placeholder' => 'Amount','class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="col-md-8 offset-md-3">
                        <input type="hidden" name="submit_type" id="submit_type">
                        <button type="submit" value="submit" class="btn btn-primary submit_future_client_form me-2 float-end" id="updateBtn">Update</button>
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