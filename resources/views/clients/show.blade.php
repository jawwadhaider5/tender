@extends('layouts.admin')


@section('content')

<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row my-2">
                            <h3 class="text-center p-2 text-success">Client Details</h3>
                            <div class="col-md-12 bg-light p-4">
                                <div class="table-responsive">
                                    <table id="" class="table table-hover table-bordered w-100">
                                        <thead>
                                            <tr class="bg-success text-white">
                                                <th>Company Name</th>
                                                <th>Web Address</th>
                                                <th>Address</th>
                                                <th>Contact Number</th>
                                                <th>Email</th>
                                                <th>City</th>
                                                <th>Group</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$client->company_name}}</td>
                                                <td><a href="{{$client->web_address}}" target="_blank" class="text-decoration-none">{{$client->web_address}}</a></td>
                                                <td>{{$client->address}}</td>
                                                <td>{{$client->contact_no}}</td>
                                                <td>{{$client->email}}</td>
                                                <td>{{$client->city->name}}</td>
                                                <td>{{$client->group->name}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="row">
                                <div class="col-md-3">
                                    <h3 class="text-center p-2 text-success">Persons</h3>
                                </div>
                                <div class="col-md-3 offset-md-6 ">
                                    <a href="#" class="btn btn-sm btn-primary py-1 px-4 mt-2 float-end create-person">Add Person</a>
                                </div>
                            </div>
                            <div class="col-md-12 bg-light p-4">
                                <div class="table-responsive">
                                    <table id="" class="table table-hover table-bordered w-100">
                                        <thead>
                                            <tr class="bg-success text-white">
                                                <th>Image</th>
                                                <th>Person Name</th>
                                                <th>Position</th>
                                                <th>Contact Number</th>
                                                <th>Email</th>
                                                <th>Linkedin</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($client->persons as $person)
                                            <tr>
                                                <td>
                                                    @if($client->image)
                                                    <img src="{{ asset('/persons/' . $client->image) }}" class="img-thumbnail" alt="Client Image">
                                                    @endif
                                                </td>
                                                <td>{{$person->name}}</td>
                                                <td>{{$person->contact_no}}</td>
                                                <td>{{$person->email}}</td>
                                                <td>{{$person->position->name}}</td>
                                                <td><a href="{{$person->linkedin}}" target="_blank">Visit Profile</a></td>
                                                <td>
                                                    @can("client")
                                                    <a href="/clients/edit/{{$person->id}}/person" class="edit-person-detail btn btn-dark mdi mdi-table-edit p-1 m-1"></a>
                                                    <a href="/clients/delete/{{$person->id}}/person" class="delete-person btn btn-danger  mdi mdi-delete p-1 m-1"></a>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2 bg-light">
                            <div class="col-md-4 p-2 rounded">
                                <h4 class="text-primary">all Comments</h4>
                                <div class="accordion" id="accordionExample1">
                                    @forelse($client->comments as $cmt)

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree-{{$loop->index}}">
                                            <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree-{{$loop->index}}" aria-expanded="false" aria-controls="collapseThree-{{$loop->index}}">
                                                <p><strong class="text-success">{{$cmt->comment_by->name}}</strong> - <small>{{ $cmt->created_at }}</small></p>
                                            </button>
                                        </h2>
                                        <div id="collapseThree-{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="headingThree-{{$loop->index}}" data-bs-parent="#accordionExample1">
                                            <div class="accordion-body">
                                                <p>{{$cmt->text}} <br> <small><a href="/client-comment-delete/{{$cmt->id}}" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>

                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="alet alert-danger p-1 rounded">No Comments</div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-4 p-2  rounded">
                                <h4 class="text-primary">all Responds</h4>
                                <div class="accordion" id="accordionExample2">
                                    @forelse($client->responds as $res)

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFour-{{$loop->index}}">
                                            <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour-{{$loop->index}}" aria-expanded="false" aria-controls="collapseFour-{{$loop->index}}">
                                                <p><strong class="text-success">{{$res->subject}}</strong><small> - {{$res->responds_by->name}} - {{ $res->date }}</small></p>
                                            </button>
                                        </h2>
                                        <div id="collapseFour-{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="headingFour-{{$loop->index}}" data-bs-parent="#accordionExample2">
                                            <div class="accordion-body">
                                                <p>{{$res->text}} <br> 
                                                <strong class="text-primary">Assigned To:</strong> {{ $res->assigned_user_names }} <br>
                                                <small><a href="/client-respond-delete/{{$res->id}}" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>

                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="alet alert-danger p-1 rounded">No responds</div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-4 p-2  rounded">
                                <h4 class="text-primary">all Files</h4>
                                <table class="w-100">
                                    @forelse($client->files as $res)
                                    <tr>
                                        <td style="width: 80%;"><a href="{{$res->url}}" target="_blank" class="text-decoration-none">{{$res->url}}</a></td>
                                        <td style="width: 20%;"><a href="/client-file-delete/{{$res->id}}" class="btn btn-sm btn-danger delete-file">Delete</a></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>No files</td>
                                    </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="person_create_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class=" modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create person</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    {!! Form::open(['url' => action('ClientController@store_person'),'method' => 'POST',
                    'id' => 'person_add_form','class' => '', 'enctype' => 'multipart/form-data']) !!}
                    @csrf
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label ">Profile Image</label>
                                <div class="col-sm-8">
                                    <input type="file" name="image" placeholder="Select Image" class="form-control">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label required">Name</label>
                                <div class="col-sm-8">
                                    {!! Form::text('name', null, array('placeholder' => 'Person Name','class' => 'form-control', 'required')) !!}

                                    @if ($errors->has('name'))
                                    <div class="alert  alert-danger mt-3">{{ $errors->first('name') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label ">position</label>
                                <div class="col-sm-8">

                                    {!! Form::select('position_id', [], null, ['id' => 'positionsearch',
                                    'placeholder' => 'Person Position', 'style' => 'width: 100%',
                                    'class' => ' form-control customer',
                                    ]) !!}


                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- @foreach($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                                        @endforeach -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label ">Contact Number</label>
                                <div class="col-sm-8">
                                    {!! Form::text('contact_no', null, array('placeholder' => 'Phone Number','class' => 'form-control', 'required')) !!}

                                    @if ($errors->has('contact_no'))
                                    <div class="alert  alert-danger mt-3">Phone Number is required!</div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label ">Email Address</label>
                                <div class="col-sm-8">
                                    {!! Form::text('email', null, array('placeholder' => 'Email Address','class' => 'form-control', 'required')) !!}

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label ">Linkedin Profile</label>
                                <div class="col-sm-8">
                                    {!! Form::text('linkedin', null, array('placeholder' => 'Linkedin Profile Link','class' => 'form-control')) !!}

                                    @if ($errors->has('linkedin'))
                                    <div class="alert  alert-danger mt-3">Linkedin Profile Link is required!</div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="col-md-8 offset-md-3">
                                <input type="hidden" name="submit_type" id="submit_type">
                                <button type="submit" value="submit" class="btn btn-primary submit_person_form me-2 float-end">Submit</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit_person_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

    <div class="modal fade" id="add_position_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class=" modal-dialog  ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Position</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => action('PositionController@create_ajax'),'method' => 'POST', 'id' => 'add_position_form']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label required">Position Name</label>
                                <div class="col-sm-9">
                                    {!! Form::text('name', null, array('placeholder' => 'Position Name','class' => 'form-control' , 'required', 'id'=>'position_name_box')) !!}
                                </div>
                            </div>
                        </div>
                        <button type="submit" value="submit" class="btn btn-info text-dark submit_position_form me-2">Submit</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</div>


@endsection

@section('javascript')

<script src="{{ asset('js/clients.js') }}"></script>
@endsection