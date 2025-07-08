       <header>
           <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
               <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start border-bottom">
                   <div class="me-3">
                       <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                           <span class="icon-menu"></span>
                       </button>
                   </div>
                   <div>
                       <a class="navbar-brand brand-logo" href="/login">
                           <h3>Tenders</h3>
                       </a>
                   </div>
               </div>
               <div class="navbar-menu-wrapper d-flex align-items-top border-bottom">
                   <ul class="navbar-nav">
                       <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                           <h5>Welcome, <span class="welcome-text"> <span class="text-black fw-bold">{{ Auth::user()->name }}</span></span> </h5>

                       </li>
                   </ul>
                   <ul class="navbar-nav ms-auto">


                       <li class="nav-item d-none d-lg-block">
                           <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                               <span class="input-group-addon input-group-prepend border-right">
                                   <span class="icon-calendar input-group-text calendar-icon"></span>
                               </span>
                               <input type="text" class="form-control">
                           </div>
                       </li>

                       <li class="nav-item dropdown">
                           <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="icon-bell"></i>
                               @if(auth()->user()->unreadNotifications->count() > 0)
                               <span class="text-danger">{{auth()->user()->unreadNotifications->count()}}</span>
                               @endif
                           </a>
                           <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-0" aria-labelledby="countDropdown"
                           style="height:400px; max-height: 400px; width:400px; overflow-y:scroll;">
                               <!-- <a class="dropdown-item">
                                   @if ($unreadnotifications)
                                   <p class="mb-0 font-weight-medium float-left">You have {{count($unreadnotifications) }} unread notifications </p>
                                   @else
                                   <p class="mb-0 font-weight-medium float-left">You have no unread notifications </p>
                                   @endif
                                   <a href="/notification" class="badge badge-pill badge-primary float-right">View all</a>
                               </a>
                               <div class="dropdown-divider"></div> -->

                               <div class=" py-1">

                                   @forelse (auth()->user()->unreadNotifications as $notification)
                                   <div class="row p-1">
                                       <div class="col-md-12"  style="background-color: #f9f9f9;">
                                            <div class="p-1">
                                                <a href="/mark-as-read" class="text-decoration-none"> <strong class="text-primary"> {{ $notification->data['topic']}}</strong> <br>
                                                <small class="text-dark">{{ $notification->data['comment']}}</small> <br>
                                                <small class="float-start text-dark">By: <strong>{{ $notification->data['responded_by']}}</strong></small>
                                                <small class="float-end text-dark">{{ $notification->data['deadline']}}</small>
                                                </a>
                                            </div>
                                       </div>
                                   </div>
                                   @empty
                                   <div class="row p-1">
                                       <div class="col-md-12"  style="background-color: #f9f9f9;">
                                            <div class="p-1">
                                                <p class="fw-light small-text">No new notification</p>
                                            </div>
                                       </div>
                                   </div>

                                   @endforelse

                                   @foreach (auth()->user()->readNotifications as $rn)
                                   <div class="row p-1">
                                       <div class="col-md-12"  style="border-bottom: 1px solid gray">
                                            <div class="p-1">
                                                <p > <strong class="text-primary"> {{ $rn->data['topic']}}</strong> <br>
                                                <small class="">{{ $rn->data['comment']}}</small> <br>
                                                <small class="float-start">By: <strong>{{ $rn->data['responded_by']}}</strong></small>
                                                <small class="float-end">{{ $rn->data['deadline']}}</small>
                                                </p>
                                            </div>
                                       </div>
                                   </div>
                                   @endforeach

                               </div>
                           </div>
                       </li>


                       <li class="nav-item dropdown d-lg-block user-dropdown">
                           <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">

                               <img class="img-xs rounded-circle" src="@if(!empty($user->userdetail->image)) {{ asset($user->userdetail->image) }} @else {{ asset('open/images/userdetail-images/admin.png') }} @endif" alt="Profile image"> </a>

                           <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                               <div class="dropdown-header text-center">
                                   @if(!empty($user->userdetail->image)) <img class="img-md rounded-circle img-xs" src="{{ asset($user->userdetail->image) }}" alt="Profile image"> @else <img class="img-md rounded-circle img-xs" src="{{ asset('open/images/userdetail-images/admin.png') }}" alt="Profile image"> @endif

                                   @if(!empty($user)) <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name }}</p> @else <p class="mb-1 mt-3 font-weight-semibold"></p> @endif
                                   @if(!empty($user)) <p class="fw-light text-muted mb-0">{{ Auth::user()->email }}</p> @else <p class="fw-light text-muted mb-0"></p> @endif
                               </div>
                               <a class="dropdown-item" href="/userprofile"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile </a>
                               <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                   <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i> {{ __('Logout') }}
                               </a>

                               <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                   @csrf
                               </form>


                           </div>
                       </li>
                   </ul>
                   <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                       <span class="mdi mdi-menu"></span>
                   </button>
               </div>
           </nav>

       </header>
