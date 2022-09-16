@extends('layout.admin-auth.master')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <b>WELCOME BACK</b>
    </div>
    <!-- /.login-logo -->
    <div class="card card-primary">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            @if(\Session::has('error'))
            <div class="alert alert-danger">{{ \Session::get('error') }}</div>
            @endif
            <form action='{{ url("admin/login") }}' method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
    <div class="row">
        <div class="col-12">
            <img src="{{ URL::to('images/final-logo-opc-white.png') }}" style="width:100%;">
        </div>
    </div>
</div>
@stop

@push('scripts')
@endpush