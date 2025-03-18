<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{route('admin.index')}}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.stats')}}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Statistics</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.balance')}}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>API Balance</span>
                    </a>
                </li>
                @if(Auth::user()->user_role == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
                <li class="menu-title">Services</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-bar-chart-line"></i>
                        <span>Bill Plans</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.plan.airtime') }}">Network (Airtime)</a></li>
                        <li><a href="{{ route('admin.plan.data.network') }}">Data Plan</a></li>
                        <li><a href="{{ route('admin.plan.decoders') }}">Decoders</a></li>
                        <li><a href="{{ route('admin.plan.bet') }}">Betting</a></li>
                        <li><a href="{{ route('admin.plan.education') }}">Exam Pins</a></li>
                        <li><a href="{{ route('admin.plan.electricity') }}">Electricity </a></li>
                        <li><a href="{{route('admin.plan.bulksms')}}">Bulk SMS</a></li>
                        <li><a href="{{ route('admin.plan.datacard.network') }}">Datacard</a></li>
                        <li><a href="{{route('admin.plan.recharge.network')}}">Recharge PINs</a></li>
                        <li><a href="{{route('admin.plan.swap')}}">Airtime to Cash</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.bills.api.website')}}" class=" waves-effect">
                        <i class="ri-code-box-line"></i>
                        <span>API Websites</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.bills.api_setting')}}" class=" waves-effect">
                        <i class="fa fa-cog"></i>
                        <span>Bills Setting</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.bills.api.selection')}}" class=" waves-effect">
                        <i class="ri-code-box-line"></i>
                        <span>API Selection</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->user_role == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions)))
                <li class="menu-title">Users</li>
                <li>
                    <a href="{{route('admin.users.index')}}" class=" waves-effect">
                        <i class="fa fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.users.settings')}}" class=" waves-effect">
                        <i class="fa fa-cog"></i>
                        <span>User Setting</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-user-star-line"></i>
                        <span>Developers/Agents</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="maps-google.html">Pending Agents</a></li>
                        <li><a href="maps-google.html">Approved Agents</a></li>
                        <li><a href="maps-vector.html">Upgrade Payments</a></li>
                    </ul>
                </li> --}}
                @endif
                <li class="menu-title">Transactions</li>
                @if(Auth::user()->user_role == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions)))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-file-text-line"></i>
                        <span>Sales</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('admin.reports.airtime')}}">Airtime</a></li>
                        <li><a href="{{route('admin.reports.data')}}">Data Sales</a></li>
                        <li><a href="{{route('admin.reports.education')}}">Education Logs</a></li>
                        <li><a href="{{route('admin.reports.power')}}">Electricity Bills</a></li>
                        <li><a href="{{route('admin.reports.bet')}}">Bet Payments</a></li>
                        <li><a href="{{route('admin.reports.cable')}}">Cable TV</a></li>
                        <li><a href="{{route('admin.reports.swap')}}">Airtime Swap</a></li>
                        <li><a href="{{route('admin.reports.bulksms')}}">Bulk SMS</a></li>
                        <li><a href="{{route('admin.reports.datacard')}}">Datacard PINs</a></li>
                        <li><a href="{{route('admin.reports.vouchers')}}">Recharge PINs</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->user_role == 'admin' || in_array('3', json_decode(Auth::user()->staff->role->permissions)))
                <li>
                    <a href="{{route('admin.mdeposits')}}" class=" waves-effect">
                        <i class="ri-swap-line"></i>
                        <span>Manual Deposits</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.deposits')}}" class=" waves-effect">
                        <i class="ri-swap-line"></i>
                        <span>Deposits</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->user_role == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions)))
                <li>
                    <a href="{{route('admin.transactions')}}" class=" waves-effect">
                        <i class="ri-wallet-3-fill"></i>
                        <span>Transactions</span>
                    </a>
                </li>
                @endif
                <li class="menu-title">Support</li>
                @if(Auth::user()->user_role == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->permissions)))
                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-mail-check-fill"></i>
                        <span>Support Ticket</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.support.unread_tickets')}}">Unread Tickets</a></li>
                        <li><a href="{{route('admin.support.tickets')}}">All Tickets</a></li>
                    </ul>
                </li> --}}
                <li>
                    <a href="{{route('admin.notifications.index')}}" class=" waves-effect">
                        <i class="ri-notification-line"></i>
                        <span>Updates</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.email.newsletter')}}" class=" waves-effect">
                        <i class="ri-mail-line"></i>
                        <span>Newsletter</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-folder"></i>
                        <span>Pages</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.pages.create')}}">Create Page</a></li>
                        <li><a href="{{route('admin.pages.index')}}">All Pages</a></li>
                    </ul>
                </li>
                @endif
                <li class="menu-title">Settings</li>
                @if(Auth::user()->user_role == 'admin' || in_array('6', json_decode(Auth::user()->staff->role->permissions)))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-settings-2-fill"></i>
                        <span>Email Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.email.setting')}}">SMTP Settings</a></li>
                        <li><a href="{{route('admin.email.template')}}">Email Templates</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->user_role == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)))
                <li>
                    <a href="{{route('admin.setting.payment')}}" class=" waves-effect">
                        <i class="fa fa-cog"></i>
                        <span>Payment Settings</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->user_role == 'admin' || in_array('8', json_decode(Auth::user()->staff->role->permissions)))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-cog"></i>
                        <span>General Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.setting.index')}}">General Settings</a></li>
                        <li><a href="{{route('admin.setting.features')}}">Features Activation</a></li>
                        <li><a href="{{route('admin.setting.custom')}}">Custom CSS & Scripts</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->user_role == 'admin' || in_array('9', json_decode(Auth::user()->staff->role->permissions)))
                <li hidden>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-group-line"></i>
                        <span>Staff</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.roles.index')}}">Staff Roles</a></li>
                        <li><a href="{{route('admin.staffs.index')}}">Staffs</a></li>
                    </ul>
                </li>
                @endif
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-settings-line"></i>
                        <span>System</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.clear.cache')}}">Clear Cache</a></li>
                        @if(Auth::user()->user_role == 'admin' || in_array('10', json_decode(Auth::user()->staff->role->permissions)))
                        <li><a href="{{route('admin.system.update')}}">Update</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
