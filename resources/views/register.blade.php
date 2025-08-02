@extends('layout.app')
@section('title', 'Register')

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
    <div class="login-area login-bg">
        <div class="container-fluid">
            <div class="row g-0 justify-content-sm-center align-items-lg-stretch">
                <div class="col-custom col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 shadow-lg">
                    <div class="login-card login-card-left d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column align-items-center justify-content-center text-center gap-10">
                            <img src="{{customAsset('assets/images/logo/logo.png')}}" alt="logo" style="width: 90px">
                            <h2 class="text-white fw-bold">{{ config('app.name') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-custom col-xxl-4 col-xl-5 col-lg-7 col-md-10 col-sm-10 shadow-lg">
                    <div class="login-card h-100 d-flex flex-column justify-content-center">

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
                                <img src="{{customAsset('assets/images/logo/logo.png')}}" alt="logo" style="width: 90px">
                            </a>
                            <h4 class="text-18 font-600 text-center">Register to {{config('app.name')}}</h4>
                        </div>
                        <!-- Form -->
                        <form action="{{route('register')}}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="contact-form mb-24">
                                        <label class="contact-label">Select Account Type</label>
                                        <select class="form-control" name="account_type" id="account-type">
                                            <option value="">Select Account Type</option>
                                            @foreach($user_types as $type)
                                                @continue($type->value === 0)
                                                <option value="{{$type->value}}" {{old('account_type') == $type->value ? 'selected' : ''}}>{{$type->name === 'LawEnforcement' ? 'Law Enforcement' : $type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="contact-form mb-24">
                                        <label class="contact-label">Name </label>
                                        <input class="form-control contact-input" type="text" placeholder="Enter Your Name" name="name" value="{{old('name')}}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="contact-form mb-24">
                                        <label class="contact-label">Phone </label>
                                        <input class="form-control contact-input" type="text" placeholder="Enter Your Phone Number" name="phone" value="{{old('phone')}}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="contact-form mb-24">
                                        <label class="contact-label">Email </label>
                                        <input class="form-control contact-input" type="email" placeholder="Enter Your Email" name="email" value="{{old('email')}}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12"><!-- Password -->
                                    <div class="contact-form mb-24">
                                        <label class="contact-label">Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control contact-input password-input" id="txtPasswordLogin" placeholder="Enter Your Password" name="password">
                                            <i class="toggle-password ri-eye-line"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="contact-form mb-24">
                                        <label class="contact-label">Confirm Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control contact-input password-input" placeholder="Confirm Password" name="password_confirmation">
                                            <i class="toggle-password ri-eye-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn-primary-fill justify-content-center w-100" type="submit">
                                        <span class="d-flex align-items-center justify-content-center gap-6">
                                            <i class="ri-check-line"></i>
                                            <span>Register</span>
                                        </span>
                            </button>
                        </form>

                        <div class="login-footer">
                            <div class="create-account">
                                <p>Already have an account?
                                    <a href="{{route('login')}}">
                                        <span>Login</span>
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

                let accountType = $('#account-type').val();
                let name = $('input[name=name]').val();
                let phone = $('input[name=phone]').val();
                let email = $('input[name=email]').val();
                let password = $('input[name=password]').val();
                let passwordConfirmation = $('input[name=password_confirmation]').val();

                if (accountType === '' || name === '' || email === '' || phone === '' || password === '' || passwordConfirmation === '') {
                    toastr.error('Please fill in all the fields');
                    return false;
                }

                if (password !== passwordConfirmation) {
                    toastr.error('Password and Confirm Password do not match');
                    return false;
                }

                $('form').submit();
            });
        });
    </script>
@endsection
