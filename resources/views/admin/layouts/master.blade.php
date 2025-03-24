<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{get_setting('description')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="Jadesdev" name="author" />
    <!-- Title -->
    <title>@yield('title') | {{get_setting('title')}}</title>
    <!-- Favicon -->
    <link rel="icon shortcut" href="{{my_asset(get_setting('favicon'))}}">

    <!-- Bootstrap Css -->
    <link href="{{static_asset('admin/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons CSS  -->
    <link rel="stylesheet" href="{{static_asset('admin/css/icons.min.css')}}">
    <!-- Core Stylesheet -->
    <link rel="stylesheet"  id="app-style" href="{{static_asset('admin/css/app.min.css')}}">
    <link rel="stylesheet" href="{{static_asset('admin/css/vendors.css')}}">
    {{-- <link rel="stylesheet" href="{{static_asset('admin/css/bootstrap-toggle.min.css')}}"> --}}
    @yield('styles')
    <style>
        .btn-block{
            width: 100% !important
        }
        body{
            background: #daeafd;
        }
        .vertical-menu{
            background: #1f174d;
        }
        .navbar-brand-box {
            background-color: #1f174d;
        }
        .card{
            border: 1px solid #9f04b3;
        }
    </style>
  </head>

  <body>
    {{-- JD Loader --}}
    <div id="loader-container" class="loader-container">
        {{-- <div class="loader2"></div>
        <div class="loader3"></div>
        <div class="loader1"></div>
        <div class="loader4"></div>
        <div class="loader6"></div> --}}
        <div class="loader5"></div>
    </div>

    <div id="layout-wrapper">
      {{-- HEader --}}
      @include('admin.layouts.header')
      {{-- left sidebar --}}
      @include('admin.layouts.sidebar')
      {{-- Page Content  --}}
      <div class="main-content">
        <div class="page-content">
          <div class="container-fluid">
            @yield('breadcrumb')
            {{-- Content --}}
              @yield('content')
          </div>
        </div>
      </div>
        <!-- End Page-content -->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script> © {{get_setting('title')}}.
              </div>
              <div class="col-sm-6">
                {{-- {!! main_scripts() !!} --}}
                  <div class="text-sm-end d-none d-sm-block">
                    <span class="float-end ms-3">V 1.0</span>
                     Developed by <a target="blank" href="https://wa.me/2348035852702">Jadesdev </a>
                  </div>
              </div>
            </div>
          </div>
      </footer>
    </div>

    {{-- confirmation modal --}}
    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang("Confirmation Notice")!</h5>
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Footer Nav -->
    <!-- All JavaScript Files -->
    <script src="{{static_asset('admin/js/jquery.min.js')}}"></script>
    <script src="{{static_asset('admin/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{static_asset('admin/js/metisMenu.min.js')}}"></script>
    <script src="{{static_asset('admin/js/simplebar.min.js')}}"></script>
    <script src="{{static_asset('admin/js/waves.min.js')}}"></script>
    <script src="{{static_asset('admin/js/vendors.js')}}"></script>
    <script src="{{static_asset('admin/js/core.js')}}"></script>
    <script src="{{static_asset('admin/js/app.js')}}"></script>
    <script src="{{static_asset('admin/js/sweetalert.min.js')}}"></script>
    <script src="{{static_asset('admin/js/snackbar.min.js')}}"></script>
    @yield('scripts')

    @include('inc.js')
  </body>
</html>
