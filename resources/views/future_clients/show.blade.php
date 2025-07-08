@extends('layouts.admin')


@section('content')



<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row my-2">
                            <h3 class="text-center p-2 text-success">Future Client Details</h3>
                            <div class="col-md-12 bg-light p-4">
                                <div class="table-responsive">
                                    <table id="" class="table table-hover table-bordered w-100">
                                        <thead>
                                            <tr class="bg-success text-white">
                                                <th>#</th>
                                                <th>Company Name</th>
                                                <th>Website</th>
                                                <th>Address</th>
                                                <th>Contact Number</th>
                                                <th>Email</th>
                                                <th>Tender Type</th>
                                                <th>Description</th>
                                                <th>Asssigned No#</th>
                                                <th>Start Date</th>
                                                <th>Coming Date</th>
                                                <th>Period</th>
                                                <th>Term</th>
                                                <th>Amount</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$future_client->id}}</td>
                                                <td>{{$future_client->client->company_name}}</td>
                                                <td><a href="{{$future_client->client->web_address}}" target="_blank" class="text-decoration-none">{{$future_client->client->web_address}}</a></td>
                                                <td>{{$future_client->client->address}}</td>
                                                <td>{{$future_client->client->contact_no}}</td>
                                                <td>{{$future_client->client->email}}</td>
                                                <td>{{$future_client->tender_type->name}}</td>
                                                <td>{{$future_client->description}}</td>
                                                <td>{{$future_client->assigned_number}}</td>
                                                <td>{{$future_client->start_date}}</td>
                                                <td>{{$future_client->coming_date}}</td>
                                                <td>{{$future_client->period}}</td>
                                                <td>{{$future_client->term}}</td>
                                                <td>{{$future_client->amount}}</td> 
                                                <td>{{$future_client->user->name}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 
                        <div class="row my-2 bg-light">
                            <div class="col-md-4 p-2 rounded">
                                <h4 class="text-primary">all Comments</h4>
                                <div class="accordion" id="accordionExample1">
                                    @forelse($future_client->comments as $cmt)

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree-{{$loop->index}}">
                                            <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree-{{$loop->index}}" aria-expanded="false" aria-controls="collapseThree-{{$loop->index}}">
                                                <p><strong class="text-success">{{$cmt->comment_by->name}}</strong> - <small>{{ $cmt->created_at }}</small></p>
                                            </button>
                                        </h2>
                                        <div id="collapseThree-{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="headingThree-{{$loop->index}}" data-bs-parent="#accordionExample1">
                                            <div class="accordion-body">
                                                <p>{{$cmt->text}} <br> <small><a href="/future-client-comment-delete/{{$cmt->id}}" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>

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
                                    @forelse($future_client->responds as $res)

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
                                                <small><a href="/future-client-respond-delete/{{$res->id}}" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>

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
                                    @forelse($future_client->files as $res)
                                    <tr>
                                        <td style="width: 80%;"><a href="{{$res->url}}" target="_blank" class="text-decoration-none">{{$res->url}}</a></td>
                                        <td style="width: 20%;"><a href="/future-client-file-delete/{{$res->id}}" class="btn btn-sm btn-danger delete-file">Delete</a></td>
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

</div>


@endsection

@section('javascript')
<script src="{{ asset('js/futureclients.js') }}"></script>
@endsection