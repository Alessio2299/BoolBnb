@extends('admin.layouts.login')

@push('css')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid mt-0">
    <div class="logo-home">
        <a href="{{ url('/') }}">
            <img class="home" src="{{asset('storage/img/BoolBnb.png')}}" alt="Logo BoolBnb">
        </a>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="container-card">
                    <img class="logo" src="{{asset('storage/img/BoolBnb.png')}}" alt="Logo BoolBnb">
                    <div class="my_card d-flex">
                        <div class="img-left">
                            <img src="{{asset('storage/img/Torre-Eiffel.png')}}" alt="Torre Eiffel">
                        </div>
                        <div class="form-right">
                            <div class="overlay"></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
            
                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right my_text">{{ __('E-Mail Address') }}</label>
            
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            
                                            @error('email')
                                                <span class="my_error invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
            
                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right my_text">{{ __('Password') }}</label>
            
                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            
                                            @error('password')
                                                <span class="my_error invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
            
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            
                                                <label class="form-check-label my_text" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn my_btn btn-primary">
                                                {{ __('Login') }}
                                            </button>

                                            <a class="btn my_btn ml-3 btn-primary" href="{{ route('register') }}">
                                                Sign Up
                                            </a>
                                            
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link my_link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
