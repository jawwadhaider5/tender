<div class=" modal-dialog modal-lg ">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{$role->name}}'s Permissions</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">  
      <div class="row">
        <fieldSet class="scheduler-border "> 
            <div class="row"> 
                <div class="col-md-12">
                    <div class="form-group">
                        <strong>Permissions:</strong><br>
                        <div class="row">
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $key => $v) 
                                    <div class="col-md-2">
                                    <label class="label label-success">{{ $v->name }},</label><br>
                                    </div> 
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>


        </fieldSet>
  </div>
      </div>
      
    </div>
    <!-- /.modal-content -->
  </div>
  




  












