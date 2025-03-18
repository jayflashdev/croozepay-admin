@extends('admin.layouts.auth')

@section('title', 'Login Password')
@section('content')

<div class="card">
    <div class="card-body">
        <h3 class="text-center mt-0 mb-3">
            <a href="{{route('admin.index')}}" class="logo"><img src="{{my_asset(get_setting('logo'))}}" height="24" alt="logo-img"></a>
        </h3>
        <h5 class="text-center mt-0 text-color"><b>Admin Sign In</b></h5>

        <form class="form-horizontal mt-3 mx-3 ajaxForm" method="POST" action="{{route('admin.login')}}">
            @csrf
            <div class="form-group mb-3">
                <div class="col-12">
                    <input class="form-control" name="email" type="email" required="" placeholder="Email">
                </div>
            </div>

            <div class="form-group mb-3">
                <div class="col-12">
                    <input class="form-control" name="password" type="password" required="" placeholder="Password">
                </div>
            </div>

            <div class="form-group">
                <div class="col-12">
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox-signup" name="remember" type="checkbox" checked="">
                        <label for="checkbox-signup" class="text-color">
                            Remember me
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group text-center mt-3">
                <div class="col-12">
                    <button class="btn btn-primary btn-block btn-lg waves-effect waves-light w-100" type="submit">
                        Log In</button>
                </div>
            </div>

            <div class="form-group row mt-4 mb-0">
                <div class="col-sm-7">
                    <a href="{{route('admin.password.reset')}}" class="text-color">
                        <i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
