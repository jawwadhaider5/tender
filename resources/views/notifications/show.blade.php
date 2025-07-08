@extends('layouts.admin')

@section('content')


<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row border-bottom">
                            <div class="space">
                                <div class="card-title my-2">
                                   
                                </div>
                                @can('bill-list')
                                <div class="">
                                    <a class="btn btn-primary btn-sm  btn-rounded mdi mdi-arrow-left-bold" title="Back" href="{{ route('notification.index') }}">Back</a>
                                </div>
                                @endcan
                            </div>
                        </div>
                        {{-- @forelse($sellsnotification as $notification) --}}
                        <div class="row">






                            <div class="col-md-6"> 
                                <h5 class=" mt-3 ">Business</h5> 
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <p class="text-primary">{{ $business->name }}</p>
                                        <p class="text-primary">{{ $user->name }}</p>
                                        <small class="">{{ $business->address_one }}</small><br>
                                        <small class="">{{ $business->phone_no_one }}</small><br>
                                        <small class="">{{ $business->phone_no_two }}</small>
                                    </div> 
                                </div> 
                            </div>
                            <div class="col-md-6"> 
                                <h5 class=" mt-3 ">User</h5> 
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <p class="text-primary">{{ $user->name }}</p>
                                        <small class="">{{ $user->userdetail->account_type }}</small> <br>
                                        <small class=""> {{ $user->email }} </small><br>
                                        <small class="">{{ $user->userdetail->phone_no_one }}</small>
                                    </div> 
                                </div> 
                            </div> 


                           
                               
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-12"> 
                                <div class="table-responsive"> 
                                    <table class="table"> 
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>Bill ID</th>
                                                <th>Item</th>
                                                <th>Status</th>
                                                <th>Customer</th>
                                                <th>Contact No</th>
                                                <th>Load Date</th>
                                                <th>Load Note</th>
                                                <th>Read At</th>

                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <td>{{ $cn->data['data']['bill_id']}}</td>
                                            <td>{{ $cn->data['data']['item']['name']}}</td>
                                                   
                                            @if ($cn->data['data']['load_status'] == 1)
                                           <td><a href="/load/{{ $cn->data['data']['id'] }}" class="view-notification btn btn-success " >Loaded</a></td> 
                                            @else
                                           <td> <a href="/load/{{ $cn->data['data']['id'] }}" class="view-notification btn btn-danger " >Unloaded</a> </td>

                                            @endif
                                            <td>{{ $cn->data['data']['customer']['customer_name']}}</td> 
                                            <td>{{ $cn->data['data']['customer']['contact_no']}}</</td> 
                                            <td>{{ $cn->data['data']['loaded_date'] }}</</td> 
                                            <td>{{ $cn->data['data']['loaded_note'] }}</td> 
                                            <td>{{ $cn->read_at}}</td> 

                                           
                                        </tbody>  
                                    </table> 
                                </div>
                            </div> 
                        </div>
                        
             
    </div>
</div>

<div class="modal fade" id="view_load_modal" tabindex="-1" role="dialog" 
aria-labelledby="gridSystemModalLabel">
</div>


<div class="modal fade" id="view_itemauction_modal" tabindex="-1" role="dialog" 
aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
<script src="{{ asset('js/notification.js') }}"></script>
@endsection