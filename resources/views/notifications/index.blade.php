@extends('layouts.admin')


@section('content')



<div class="main-panel">
    <div class="content-wrapper">

      @if ($message = Session::get('success'))
      <div class="alert alert-success">
        <p>{{ $message }}</p>
      </div>
      @endif

      <div class="row">

        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">

              <div class="row">
                <div class="space">
                    <div class="card-title">
                        <h4>Notification Management</h4>
                    </div>
                    
                     {{-- @can('bill-create')
                    <div class="">
                      <a class="btn btn-primary btn-sm  btn-rounded  mdi mdi mdi-plus-box" title="Add New Bill" href="{{ route('bill.create')}} " ></a>
                    </div>
                     @endcan --}}
                   
                </div>
              </div>

              
             
              <div class="table-responsive">
                <table id="notification_table"  class="table table-hover w-100" >
                  <thead>
                    <tr class="bg-success text-white">
                      <th>Item</th>
                      <th>Status</th> 
                      <th>Customer</th>
                      <th>Load Date</th>
                      <th>Load note</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>



          </div>
          
          

          <div class="modal fade" id="view_itemauction_modal" tabindex="-1" role="dialog" 
          aria-labelledby="gridSystemModalLabel">
         </div>

         <div class="modal fade" id="view_load_modal" tabindex="-1" role="dialog" 
         aria-labelledby="gridSystemModalLabel">
        </div>


        </div>

        
    



      </div>
    </div>
    <!-- content-wrapper ends -->
   
  </div>
  <!-- main-panel ends -->

@endsection

@section('javascript')
    <script src="{{ asset('js/notification.js') }}"></script>
@endsection