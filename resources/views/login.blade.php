@extends('layout.app')
@section('title', 'Login')

@section('styles')
    <style>
        .login-card-left {
            background-color: #810947 !important; /* Bootstrap bg-danger color */
            height: 100%;              /* Important for stretching */
            min-height: 100%;          /* Ensures height match on smaller screens */
        }

        .row .col-custom {
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .login-card-left {
                display: none !important;
            }
        }
    </style>
@endsection

@section('contents')
    <!-- Login area S t a r t  -->
    <div class="login-area login-bg">
            <div class="container-fluid">
                <div class="row g-0 justify-content-sm-center align-items-lg-stretch">
                    <div class="col-custom col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                        <div class="login-card login-card-left d-flex align-items-center justify-content-center">
                            <div class="d-flex flex-column align-items-center justify-content-center text-center gap-10">
                                <img src="{{customAsset('assets/images/logo/logo.png')}}" alt="logo" style="width: 90px">
                                <h2 class="text-white fw-bold">{{ config('app.name') }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-custom col-xxl-3 col-xl-5 col-lg-6 col-md-8 col-sm-10">
                        <div class="login-card">
                            @if($errors->any())
                                <div
                                    class="alert alert-danger"
                                    role="alert">
                                    <ol class="m-0">
                                        @foreach($errors->all() as $index => $error)
                                            <li>{{++$index}}. {{$error}}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif

                            <!-- Logo -->
                            <div class="logo logo-large mb-40">
                                <a href="{{url('/')}}" class="mb-30 d-block">
                                    <img src="{{customAsset('assets/images/logo/logo.png')}}" alt="logo" style="width: 90px;">
                                </a>
                                <h4 class="text-18 font-600 text-center">Login to {{config('app.name')}}</h4>
                            </div>

                            <!-- Form -->
                            <form action="{{route('login')}}" method="POST">
                                @csrf

                                <div class="position-relative contact-form mb-24">
                                    <label class="contact-label"> Username or Email </label>
                                    <input class="form-control contact-input" type="text" placeholder="Enter Your Username or Email" name="username" id="username">
                                </div>
                                <!-- Password -->
                                <div class="position-relative contact-form mb-24">
                                    <div class="d-flex justify-content-between aligin-items-center">
                                        <label class="contact-label">Password</label>
                                        <a href="#"><span class="text-danger-soft text-12"> Forgot password? </span></a>
                                    </div>

                                    <div class="position-relative">
                                        <input type="password" class="form-control contact-input password-input" id="password" placeholder="Confirm Password" name="password">
                                        <i class="toggle-password ri-eye-line"></i>
                                    </div>
                                </div>

                                <button class="btn-primary-fill justify-content-center w-100" type="submit">
                                        <span class="d-flex align-items-center justify-content-center gap-6">
                                            <i class="ri-check-line"></i>
                                            <span>Login</span>
                                        </span>
                                </button>
                            </form>

                            <div class="login-footer">
                                <div class="create-account">
                                    <p>Don’t have an account?
                                        <a href="{{route('register')}}">
                                            <span>Register</span>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('button[type=submit]').on('click', function(e) {
                e.preventDefault();

                let username = $('#username').val();
                let password = $('#password').val();

                if (username === '' || password === '') {
                    toastr.error('Please fill in all the fields');
                    return false;
                }

                $('form').submit();
            });
        });
    </script>
@endsection
