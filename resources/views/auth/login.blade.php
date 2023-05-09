@extends('layouts.app')
@section('content')
<section id="wrapper">
    <div class="login-register">
        <div class="login-box card">
            <div class="card-body">
                <form method="POST" class="form-horizontal form-material" action="{{ route('login') }}">
                    @csrf
                    <h3 class="text-center m-b-20">Sign In</h3>
                    <div class="form-group m-t-40">
                        <div class="col-xs-12">
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}"> 
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="password" name="password" class="form-control @error('password') is-invalid @enderror" type="password"  placeholder="{{ __('Password') }}"> 
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="customCheck1">{{ __('Remember Me') }}</label>
                                </div>
                                @if (Route::has('password.request'))
                                <div class="ms-auto">
                                    <a href="{{ route('password.request') }}" class="text-muted"><i class="fas fa-lock m-r-5"></i>{{ __('Forgot Your Password') }}</a> 
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn w-100 btn-lg btn-info btn-rounded text-white" type="submit">{{ __('Login') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
