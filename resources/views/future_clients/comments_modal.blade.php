<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header p-1 m-1">
            <h4 class="modal-title">Comments on <strong>({{$future_client->client->company_name}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-2">

            {!! Form::open(['url' => action('FutureClientController@post_future_client_comments'),'method' => 'POST', 'id' => 'comment_future_client_form']) !!}
            <input type="hidden" name="future_client_id" value="{{ $future_client->id }}">

            <div class="row">
                <div class="form-group row mb-1">
                    <div class="col-sm-10">
                        {!! Form::textarea('text', null, array('placeholder' => 'Enter Your Comment...','class' => 'form-control', 'rows' => 5, 'style' => 'min-height: 120px; height: 120px;', 'required')) !!}
                        @if ($errors->has('text'))
                        <div class="alert  alert-danger mt-3">{{ $errors->first('text') }}</div>
                        @endif
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" value="Post Comment" class="btn btn-primary  me-2 float-end" id="submit_future_client_form">Post Comment</button>
                    </div>
                </div>

            </div>
            {!! Form::close() !!}

            <div class="row">
                <div class="col-md-12">
                
                    <div class="accordion" id="accordionExample">
                        @forelse($comments as $cmt)
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree-{{$loop->index}}">
                                <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree-{{$loop->index}}" aria-expanded="false" aria-controls="collapseThree-{{$loop->index}}">
                                    <p ><strong class="text-success">{{$cmt->comment_by->name}}</strong> - <small>{{ $cmt->created_at }}</small></p> 
                                </button>
                            </h2>
                            <div id="collapseThree-{{$loop->index}}" class="accordion-collapse collapse" aria-labelledby="headingThree-{{$loop->index}}" data-bs-parent="#accordionExample">
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
            </div>
        </div>
    </div>
</div>