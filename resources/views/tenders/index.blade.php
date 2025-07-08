@extends('layouts.admin')


@section('content')



<div class="main-panel">
  <div class="content-wrapper">

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
      <p>{{ Session::get('message') }}</p>
    </div>
    @endif

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <div class="row">
              <div class="space">
                <div class="card-title">
                  <h4>Tender Management</h4>
                </div>

                @can('tender')
                <div class="">
                  <a class="create-tender btn btn-primary btn-sm  btn-rounded" title="Add New Tender" href="{{ route('tenders.create')}} "><i class="mdi mdi mdi-plus-box"></i></a>
                </div>
                @endcan

              </div>
            </div>

            <div class="row">
              <div>
                <div class="table-responsive" style="height:450px;">



                  <table id="tender_table2" class="table table-hover table-bordered" >
                    <thead>
                      <tr class="bg-success text-white">
                        <th>#</th> 
                        <th>Company Name</th>
                        <th>Comments</th>
                        <th>Responds</th>
                        <th>Files</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $group) 

                      <tr class="groupRow bg-light" data-group_id="{{$group['group_id']}}" style="cursor:pointer; ">
                        <td colspan="1"></td>
                        <td  class="font-weight-bold">{{$group['group_name']}} <span class="float-end "><i class="selectedrowicon mdi mdi-arrow-right"></i></span></td>
                        <td colspan="4"></td>
                      </tr>
                      <tr> 
                        @foreach($group['tenders'] as $tender)
                      <tr class="tenderRow d-none group-{{$group['group_id']}}">
                        <td>{{$tender['tender_id']}} </td> 
                        <td><a href="/tenders/{{$tender['tender_id']}}" class="text-decoration-none">{{$tender['client_company_name']}}</a></td> 
                        <td>
                          @php
                          $firstcomment = "No Comments yet";
                          $index = 0;
                          $firstrespond = "No Responds yet";
                          $resindex = 0;
                          foreach($tender['comments'] as $cmt)
                          {
                          $firstcomment = $cmt->text;
                          break;
                          }
                          foreach($tender['responds'] as $res)
                          {
                          $firstrespond = $res->text;
                          break;
                          }
                          
                          @endphp
                          <div class="dropdown p-1">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                              {{ $firstcomment}}
                            </button>
                            <ul class="dropdown-menu p-1 bg-light" aria-labelledby="dropdownMenuButton1" style="width:750px">
                              <li>
                                <form method="POST" action="/post-tender-comments">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                  <input type="hidden" name="tender_id" id="tender-id" value="{{$tender['tender_id']}}">
                                  <div class="row">
                                    <div class="form-group row mb-1">
                                      <div class="col-sm-9">
                                        <input type="text" name="text" id="comment-text" placeholder="Enter Your Comment..." class="form-control" required>
                                        <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                      </div>
                                      <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary me-2 float-end" id="submit-tender-form">Comment</button>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </li>
                              @foreach($tender['comments'] as $cmt)
                              <div class=" m-1 p-1 border rounded">
                                <div class="row">
                                  <p><strong class="text-success">{{$cmt->comment_by->name}}</strong> - <small>{{$cmt->created_at}}</small></p><br>
                                </div>
                                <div class="row d-flex justify-content-between">
                                  <p>{{$cmt->text}} <br> <small><a href="/tender-comment-delete/{{$cmt->id}}" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>
                                </div>
                              </div>
                              @endforeach
                            </ul>
                          </div>
                        </td>
                        <td>
                          <div class="dropdown p-1">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                              {{ $firstrespond }}
                            </button>
                            <ul class="dropdown-menu p-1 bg-light" aria-labelledby="dropdownMenuButton1" style="width:650px">
                              <li>
                                <form method="POST" action="/post-tender-responds">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                  <input type="hidden" name="tender_id" id="tender-id" value="{{$tender['tender_id']}}">
                                  <div class="row">
                                    <div class="form-group row mb-1">
                                      <div class="col-sm-2">
                                        <input type="date" name="date" id="respond-text" placeholder="Selete a date" class="form-control" required>
                                        <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                      </div>
                                      <div class="col-sm-2">
                                        <input type="time" name="time" id="respond-text" placeholder="Select Time" step="1" class="form-control" required>
                                        <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                      </div>
                                      <div class="col-sm-2">
                                        <select name="subject" class="form-control" id="" required>
                                          <option value="-">Select Subject</option>
                                          <option value="Subject One">Subject One</option>
                                          <option value="Subject Two">Subject Two</option>
                                          <option value="Subject Three">Subject Three</option>
                                          <option value="Subject Four">Subject Four</option>
                                        </select>
                                      </div>
                                      <div class="col-sm-3">
                                        <input type="text" name="text" id="respond-text" placeholder="Enter Your respond..." class="form-control" required>
                                        <div id="text-error" class="alert alert-danger mt-3" style="display: none;"></div>
                                      </div>
                                      <div class="col-sm-2">
                                        <select name="assigned_user_id[]" class="form-control select2 tender_response_users" multiple>
                                        {!! $userss !!} 
                                    </select> 
                                      </div>
                                      <div class="col-sm-1">
                                        <button type="submit" class="btn btn-primary me-2 float-end" id="submit-tender-form">Respond</button>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </li>

                              @foreach($tender['responds'] as $resp)
                              @php 
                              $assignedUsers = ""; 
                                $assignedUsers = collect($resp['assigned_user_id'] ?? [])->map(function($uid) {
                                    $u = \App\Models\User::find($uid);
                                    return $u ? $u->name : '';
                                })->filter()->implode(', '); 

                              @endphp
                              <div class=" m-1 p-1 border rounded">
                                <div class="row">
                                  <p><strong class="text-primary">{{$resp->subject}}</strong> -
                                    <strong class="text-success">{{$resp->responds_by->name}}</strong> -
                                    <small>{{$resp->date}} </small> -
                                    <small> {{$resp->time}} </small> -
                                    <strong class="text-primary">Assigned To: </strong><strong> {{ $assignedUsers }}</strong>
                                  </p><br>
                                </div>
                                <div class="row d-flex justify-content-between">
                                  <p> {{$resp->text }} <br> <small><a href="/tender-respond-delete/{{$resp->id}}" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>
                                </div>
                              </div>
                              @endforeach
                            </ul>

                          </div>
                        </td>
                        <td>
                        
                          <div class="dropdown p-1">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                              Files
                            </button>
                            <ul class="dropdown-menu p-1 bg-light" aria-labelledby="dropdownMenuButton1" style="width:500px">
                              <li>
                                <form method="POST" action="/post-tender-files" enctype="multipart/form-data">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                  <input type="hidden" name="tender_id" id="tender-id" value="{{$tender['tender_id']}}">
                                  <div class="row">
                                    <div class="form-group row mb-1">
                                      <div class="col-sm-10 p-1">
                                        <input type="file" name="files[]" placeholder="Select Files" class="form-control" multiple>
                                      </div>
                                      <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary me-2 float-end" id="submit-tender-form">Upload</button>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </li>
                              <li>
                                <div class="row">
                                  <div class="col-md-12 p-1 border rounded">
                                    <table class="w-100">
                                      @foreach($tender['files'] as $file)
                                      <tr>
                                        <td style="width: 80%;"><a href="{{$file->url}} " target="_blank" class="text-decoration-none">{{$file->url}} </a></td>
                                        <td style="width: 20%;"><a href="/tender-file-delete/{{$file->id}} " class="btn btn-sm btn-danger delete-file">Delete</a></td>
                                      </tr>
                                      @endforeach
                                    </table>
                                  </div>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </td>
                        <td> 
                          @can("client")
                          <a href="{{action('TenderController@edit', $tender['tender_id'])}}"  class="edit-tender2 btn btn-dark mdi mdi-table-edit p-1 m-1"></a>
                          <a href="{{action('TenderController@destroy', $tender['tender_id'])}}" class="delete-tender btn btn-danger  mdi mdi-delete p-1 m-1"></a>
                          @endcan 
                        </td>
                      </tr>
                      @endforeach
                      
                      </tr>
                      @endforeach
                    </tbody>
                  </table>


                  

                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->

  <div class="modal fade" id="edit_tender_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <!-- <div class="modal fade" id="comment_tender_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="respond_tender_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  <div class="modal fade" id="file_tender_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div> -->


  <div class="modal fade" id="tender_create_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class=" modal-dialog modal-lg ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Tender</h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">


          {!! Form::open(['url' => action('TenderController@store'),'method' => 'POST',
          'id' => 'tender_add_form','class' => '', 'enctype' => 'multipart/form-data']) !!}

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label required">City</label>
                <div class="col-sm-8">

                <select name="city_id" id="citysearch" class="form-control" required>
                    <option value="">Select a city</option>
                  </select> 

                  <!-- <select name="city_id" id="customer_search" class="form-control" required>
                    <option value="">Select a city</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }} - {{ $city->code }}</option>
                    @endforeach
                  </select> -->
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
                    <option value="{{ $client->id }}">{{ $client->company_name }}</option>
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
                  {!! Form::text('tender_number', null, array('placeholder' => 'Tender Number','class' => 'form-control', 'required')) !!}

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
                  {!! Form::date('year', null, array('placeholder' => 'Year','class' => 'form-control', 'readonly', 'id'=>'year')) !!}
                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label required">Description</label>
                <div class="col-sm-8">
                  <textarea name="description" id="" class="form-control" rows="3" required></textarea>
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
                    <option value="approved">Approved</option>
                    <option value="not approved">Not Approved</option>
                    <option value="pending">Pending</option>
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
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>

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
                  {!! Form::date('start_date', null, array('placeholder' => 'Start Date','class' => 'form-control')) !!}

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Close Date</label>
                <div class="col-sm-8">
                  {!! Form::date('close_date', null, array('placeholder' => 'Close Date','class' => 'form-control')) !!}

                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Announce Date</label>
                <div class="col-sm-8">
                  {!! Form::date('announce_date', null, array('placeholder' => 'Announce Date','class' => 'form-control')) !!}

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Submit Date</label>
                <div class="col-sm-8">
                  {!! Form::date('submit_date', null, array('placeholder' => 'Submit Date','class' => 'form-control', 'id'=>'submit_date')) !!}

                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Time Period</label>
                <div class="col-sm-8">
                  {!! Form::text('period', null, array('placeholder' => 'Time Period','class' => 'form-control')) !!}

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Period Term</label>
                <div class="col-sm-8">
                  <select name="term" id="term" class="form-control">
                    <option value="">Select a term</option>
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                    <option value="years">Years</option>
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
                  {!! Form::text('amount', null, array('placeholder' => 'Amount','class' => 'form-control')) !!}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-1">
                <label class="col-sm-3 col-form-label ">Select User</label>
                <div class="col-sm-8">
                  <select name="user_id" id="user_id" class="form-control">
                    <option value="">Select User</option>
                    {!! $userss !!}
                  </select>

                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 offset-md-6">
              <div class="col-md-8 offset-md-3">
                <input type="hidden" name="submit_type" id="submit_type">
                <button type="submit" value="submit" class="btn btn-primary  me-2 float-end">Submit</button>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="add_city_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class=" modal-dialog  ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New City</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => action('CityController@create_ajax'),'method' => 'POST', 'id' => 'add_city_form']) !!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">City Name</label>
              <div class="col-sm-9">
                {!! Form::text('name', null, array('placeholder' => 'City Name','class' => 'form-control' , 'required', 'id'=>'city_name_box')) !!}
              </div>
            </div>
          </div> 
          <div class="col-md-12">
            <div class="form-group row mb-1">
              <label class="col-sm-3 col-form-label required">City Code</label>
              <div class="col-sm-9">
                {!! Form::text('code', null, array('placeholder' => 'City Code','class' => 'form-control' , 'required', 'id'=>'city_code_box')) !!}
              </div>
            </div>
          </div> 
          <button type="submit" value="submit" class="btn btn-info text-dark submit_city_form me-2">Submit</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
 

</div>
<!-- main-panel ends -->

@endsection

@section('javascript')
<script>

$(document).ready(function() {

  $('.tender_response_users').select2({
                    width: '100%',
                    placeholder: "Assign to users",
                    allowClear: true
                });
                $('.dropdown-menu').on('click', function (event) {
                    event.stopPropagation(); // Prevent dropdown from closing
                  });


  $(document).on('click', 'dropdown-menu a.edit-tender2', function(e) {
        e.preventDefault();   
    });

  $('table#tender_table2 tbody').on('click', 'a.edit-tender2', function(e) {
    e.preventDefault();   
    var url = $(this).attr("href");

    $.ajax({
        url: url,
        dataType: "html",
        success: function(result) {  
            $('#edit_tender_modal').html(result).modal('show'); 
        },
        error: function(xhr, status, error) { 
            console.error("AJAX Error: ", status, error);
        }
    });
    
});
})



  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".groupRow").forEach(row => {
      row.addEventListener("click", function() {
        let groupId = this.getAttribute("data-group_id");
        document.querySelectorAll(".group-" + groupId).forEach(tenderRow => {
          tenderRow.classList.toggle("d-none");
        });

        let icon = this.querySelector(".selectedrowicon");
            if (icon) {
                icon.classList.toggle("mdi-arrow-right"); // Replace with actual collapsed icon class
                icon.classList.toggle("mdi-arrow-down"); // Replace with actual expanded icon class
            }


      });
    });
  });


 
</script>
<script src="{{ asset('js/tenders.js') }}"></script>

@endsection