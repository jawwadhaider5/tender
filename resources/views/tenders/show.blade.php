@extends('layouts.admin')


@section('content')



<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row my-2">
                            <h3 class="text-center p-2 text-success">Tender Details</h3>
                            <div class="col-md-12 bg-light p-4">
                                <div class="table-responsive">
                                    <table id="" class="table table-hover table-bordered w-100">
                                        <thead>
                                            <tr class="bg-success text-white">
                                                <th>#</th>
                                                <th>City Name</th>
                                                <th>City Code</th>
                                                <th>Company Name</th>
                                                <th>Tender Number</th>
                                                <th>Description</th>
                                                <th>Assigned No#</th>
                                                <th>Status</th>
                                                <th>Year</th>
                                                <th>Start Date</th>
                                                <th>Close Date</th>
                                                <th>Announce Date</th>
                                                <th>Submit Date</th>
                                                <th>Period</th>
                                                <th>Term</th>
                                                <th>Amount</th> 
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$tender['id']}} </td>
                                                <td>{{$tender['city']['name']}}</td>
                                                <td>{{$tender['city']['code']}}</td>
                                                <td>{{$tender['client']['company_name']}}</td>
                                                <td>{{$tender['tender_number']}}</td>
                                                <td>{{$tender['description']}}</td>
                                                <td>{{$tender['assigned_number']}}</td>
                                                <td>{{$tender['status']}}</td>
                                                <td>{{$tender['year']}}</td>
                                                <td>{{$tender['start_date']}}</td>
                                                <td>{{$tender['close_date']}}</td>
                                                <td>{{$tender['announce_date']}}</td>
                                                <td>{{$tender['submit_date']}}</td>
                                                <td>{{$tender['period']}}</td>
                                                <td>{{$tender['term']}}</td>
                                                <td>{{$tender['amount']}}</td>
                                                <td>{{$tender['user']['name']}}</td>
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
                                    @forelse($tender->comments as $cmt)

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree-{{$loop->index}}">
                                            <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree-{{$loop->index}}" aria-expanded="false" aria-controls="collapseThree-{{$loop->index}}">
                                                <p><strong class="text-success">{{$cmt->comment_by->name}}</strong> - <small>{{ $cmt->created_at }}</small></p>
                                            </button>
                                        </h2>
                                        <div id="collapseThree-{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="headingThree-{{$loop->index}}" data-bs-parent="#accordionExample1">
                                            <div class="accordion-body">
                                                <p>{{$cmt->text}} <br> <small><a href="/tender-comment-delete/{{$cmt->id}}" class="btn btn-sm btn-danger delete-comment">Delete</a></small></p>

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
                                    @forelse($tender->responds as $res)

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
                                                <small><a href="/tender-respond-delete/{{$res->id}}" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>

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
                                    @forelse($tender->files as $res)
                                    <tr>
                                        <td style="width: 80%;"><a href="{{$res->url}}" target="_blank" class="text-decoration-none">{{$res->url}}</a></td>
                                        <td style="width: 20%;"><a href="/tender-file-delete/{{$res->id}}" class="btn btn-sm btn-danger delete-file">Delete</a></td>
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
<script src="{{ asset('js/tenders.js') }}"></script>
@endsection