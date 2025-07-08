<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header p-1 m-1">
            <h4 class="modal-title">Responds on <strong>({{$tender->tender_number}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-2">

            {!! Form::open(['url' => action('TenderController@post_tender_responds'),'method' => 'POST', 'id' => 'respond_tender_form']) !!}
            <input type="hidden" name="tender_id" value="{{ $tender->id }}">

            <div class="row">
                <div class="form-group row mb-1">
                    <div class="col-sm-2 p-1">
                        <label for="">Deadline</label>
                        {!! Form::date('date', null, array('placeholder' => 'Select Deadline','class' => 'form-control', 'required')) !!}
                        @if ($errors->has('date'))
                        <div class="alert  alert-danger mt-3">{{ $errors->first('date') }}</div>
                        @endif 
                    </div>
                    <div class="col-sm-2 p-1"> 
                        <label for="">Topic</label>
                        <select name="subject" class="form-control" id="">
                            <option value="-">Select Topic</option>
                            <option value="Topic One">Topic One</option>
                            <option value="Topic Two">Topic Two</option>
                            <option value="Topic Three">Topic Three</option>
                            <option value="Topic Four">Topic Four</option>
                        </select>
                        @if ($errors->has('subject'))
                        <div class="alert  alert-danger mt-3">{{ $errors->first('subject') }}</div>
                        @endif 
                    </div>
                    <div class="col-sm-2 p-1"> 
                        <label for="">Assign to</label>
                        <select name="assigned_user_id" class="form-control" id="">
                            <option value="-">Assign to</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option> 
                            @endforeach
                        </select>
                        @if ($errors->has('assigned_user_id'))
                        <div class="alert  alert-danger mt-3">{{ $errors->first('assigned_user_id') }}</div>
                        @endif 
                    </div>
                    <div class="col-sm-4 p-1">  
                        <label for="">Respond Text</label>
                        {!! Form::text('text', null, array('placeholder' => 'Enter Responds...', 'class' => 'form-control', 'required')) !!}
                        @if ($errors->has('text'))
                        <div class="alert  alert-danger mt-3">{{ $errors->first('text') }}</div>
                        @endif
                    </div>
                    <div class="col-sm-2 p-1 pt-4"> 
                        <button type="submit" value="Post Responds" class="btn btn-primary  me-2 float-end" id="respond_tender_formbtn">Post Responds</button>
                    </div>
                </div>

            </div>
            {!! Form::close() !!}

            <div class="row">
                <div class="col-md-12">
                
                    <div class="accordion" id="accordionExample">
                        @forelse($responds as $res)
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree-{{$loop->index}}">
                                <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree-{{$loop->index}}" aria-expanded="false" aria-controls="collapseThree-{{$loop->index}}">
                                    <p ><strong class="text-success">{{$res->subject}}</strong><small> - {{$res->responds_by->name}} - {{ $res->date }}</small></p> 
                                </button> 
                            </h2>
                            
                            <div id="collapseThree-{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="headingThree-{{$loop->index}}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                <p><strong class="text-success">Assigned To</strong><small> - {{$res->assigned_to->name}} </small></p> 
                                   <p>{{$res->text}} <br> <small><a href="/tender-respond-delete/{{$res->id}}" class="btn btn-sm btn-danger delete-respond">Delete</a></small></p>
                                    
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="alet alert-danger p-1 rounded">No responds</div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>