<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{route('admin.index')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{my_asset(get_setting('touch_icon'))}}" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{my_asset(get_setting('logo'))}}" alt="" height="24">
                    </span>
                </a>

                <a href="{{route('admin.index')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{my_asset(get_setting('touch_icon'))}}" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{my_asset(get_setting('logo'))}}" alt="" height="24">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                      data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    <span class="noti-dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="{{route('admin.transactions')}}" class="small"> View All</a>
                            </div>
                        </div>
                    </div>
                    @php $notificate = App\Models\Transaction::orderByDesc('id')->limit(5)->get();  @endphp
                    <div data-simplebar style="max-height: 230px;">
                        @foreach ($notificate as $item)
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    @if ($item->type == "credit")
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                    @else
                                    <span class="avatar-title bg-danger rounded-circle font-size-16">
                                        <i class="fa fa-minus"></i>
                                    </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h6 class="mb-1">{{format_price($item->amount)}}</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">{{$item->message}}</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i>{{$item->created_at->diffForHumans()}}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <div class="p-2 border-top">
                        <div class="d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center" href="{{route('admin.transactions')}}">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{static_asset('admin/img/profile.jpg')}}"
                        alt="">
                    <span class="d-none d-xl-inline-block ms-1">{{auth('admin')->user()->name}}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                     <a class="dropdown-item" href="{{route('admin.profile')}}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{route('logout')}}"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>
