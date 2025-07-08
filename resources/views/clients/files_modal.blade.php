<div class=" modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header p-1 m-1">
            <h4 class="modal-title">Files in <strong>({{$client->company_name}})</strong></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-2">

            {!! Form::open(['url' => action('ClientController@post_client_files'), 'method' => 'POST',
            'enctype' => 'multipart/form-data', 'id' => 'file_client_form']) !!}
            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <div class="row">
                <div class="form-group row mb-1">
                    <div class="col-sm-10 p-1">
                        <input type="file" name="files[]" placeholder="Select Files" class="form-control" multiple>
                        @if ($errors->has('file'))
                        <div class="alert  alert-danger mt-3">{{ $errors->first('file') }}</div>
                        @endif
                    </div>
                    <div class="col-sm-2 p-1">
                        <button type="submit" value="Post File" class="btn btn-primary  me-2 float-end" id="file_client_formbtn">Upload File</button>
                    </div>
                </div>

            </div>
            {!! Form::close() !!}

            <div class="row">
                <div class="col-md-12 p-1 border rounded">

                    <table class="w-100">
                        @forelse($files as $res)
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