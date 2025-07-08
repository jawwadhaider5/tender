<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header py-2">
            <h4 class="modal-title">User's Details</h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body bg-light">

            <div class="row">
                @if(!empty($user->userdetail->image))
                <div class="col-md-2"> 
                    <a href="{{$user->userdetail->image}}" target="_blank"><img src="{{$user->userdetail->image}}" style="width:120px; height:120px" alt=""></a> <br>
                </div>
                @endif
                <div class="col-md-4">
                    <h5>{{$user->name}}</h5>
                    <small class="text-info"><b>#{{ $user->userdetail->id }}</b> ({{ $user->userdetail->account_type }})</small><br>
                    <small class="text-muted mdi  mdi-email"> {{$user->email}}</small><br>
                    @if(!empty($user->userdetail->date_of_birth))
                    <small class="text-muted mdi mdi-cake">{{$user->userdetail->date_of_birth}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->gender))
                    <small class="text-muted mdi mdi-gender-male-female">{{$user->userdetail->gender}}</small><br><br>
                    @endif
                    @if ($user->status == '1')
                    <button type="button" class="btn btn-success btn-sm">
                        <div class="mdi mdi-account-check"> Active </div>
                    </button>
                    @endif
                    @if ($user->status == '0')
                    <button type="button" class="btn btn-danger btn-sm">
                        <div class="mdi mdi-account-off"> Inactive </div>
                    </button>
                    @endif
                </div>
                <div class="col-md-3">
                    <h5>Detail</h5>
                    @if(!empty($user->userdetail->phone_no_one))
                    <small class="text-muted mdi mdi-phone"> {{$user->userdetail->phone_no_one}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->phone_no_two))
                    <small class="text-muted mdi mdi-phone"> {{$user->userdetail->phone_no_two}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->address_one))
                    <small class="text-muted inline-block mdi mdi-map-marker-multiple"> {{ $user->userdetail->address_one }}</small><br>
                    @endif
                    @if(!empty($user->userdetail->address_two))
                    <small class="text-muted inline-block mdi mdi-map-marker-multiple"> {{ $user->userdetail->address_two }}</small><br>
                    @endif
                </div>
                <div class="col-md-3">
                    <h5>Other Information</h5>
                    @if(!empty($user->userdetail->trn_no))
                    <small class="text-muted mdi mdi-contact-mail"> {{$user->userdetail->trn_no}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->responsible_name))
                    <small class="text-muted mdi mdi-contact-mail"> {{$user->userdetail->responsible_name}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->passport_number))
                    <small class="text-muted mdi mdi-contact-mail"> {{$user->userdetail->passport_number}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->cnic_number))
                    <small class="text-muted mdi mdi-credit-card-multiple"> {{$user->userdetail->cnic_number}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->joining_date))
                    <small class="text-muted icon-calendar"> {{$user->userdetail->joining_date}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->leaving_date))
                    <small class="text-muted icon-calendar"> {{$user->userdetail->leaving_date}}</small><br>
                    @endif
                    @if(!empty($user->userdetail->salary_per_month))
                    <small class="text-muted mdi mdi-credit-card"> {{$user->userdetail->salary_per_month}}</small><br>
                    @endif
                </div>
            </div> 

            @if (!empty($containers)) 
            <div class="row mt-3">
                <div class="col-md-12  ">
                    <div class="">
                        <div class="card-header" style="background-color: rgb(0,0,0,0);">
                            <h3 class="card-title mb-0">Container's Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table text-nowrap">
                                <tbody>
                                    <tr class="bg-info">
                                        <th>ID</th>
                                        <th>Container</th>
                                        <th>Number</th>
                                        <th>Creation Date</th>
                                        <th>Details</th>
                                    </tr>
                                    @foreach ($containers as $cont )
                                    <tr class="">
                                        <td style="width: 20%"> {{ $cont->id }}</td>
                                        <td style="width: 20%"> {{ $cont->name }}</td>
                                        <td style="width: 20%"> {{ $cont->number }}</td>
                                        <td style="width: 20%"> {{ $cont->creation_date }}</td>
                                        <td style="width: 20%"> <a href="/container/{{ $cont->id }}" target="_blank">View</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
    <!-- /.modal-content -->
</div>