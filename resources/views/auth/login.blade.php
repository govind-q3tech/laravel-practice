<?php 
$email = old('email');
$password = old('password');
$remember = false;
if((isset($_COOKIE['email']) || !empty($_COOKIE['email'])) && (isset($_COOKIE['password']) || !empty($_COOKIE['password']))){
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];
    $remember = true;
}
?>
<x-layouts.front-layout>
    @section('title', 'Login')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "Login"]]]) }}

    </x-slot>
    <x-slot name="content">
        <div class="login-area">
            <div class="white-box login-box">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="user-login">
                            <div class="text-right d-flex justify-content-end align-items-center">Don’t have an account ? <a href="{{ route('frontend.register') }}">
                                <button class="bord-btn ml-2">SIGN UP </button></a></div>
                            <div class="loginhead">
                                <figure><img src="{{ asset('img/login-icon.png') }}" alt=""></figure>
                                <h2>Login to your Account</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                                <div class="clear"></div>
                                <img src="{{ asset('img/line.png') }}" alt="">
                            </div>
                            
                            <div class="form_filds_block">
                                <form method="POST" action="{{ route('frontend.login') }}">
                                    @csrf
                                    <!-- required autofocus -->
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <x-input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : ''}}" type="email" placeholder="Email" name="email" value="{{ $email }}" maxlength="50"/>
                                        <i class="far fa-envelope"></i>
                                        @error('email')
                                        <span class="help-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group passwordOuter {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <!-- required -->
                                        <x-input id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : ''}}"
                                        type="password"
                                        name="password"
                                        placeholder="Password"
                                        value="{{ $password }}"
                                        autocomplete="current-password" maxlength="50"/>
                                        <i class="far fa-eye hide-password"></i><i class="far fa-eye-slash show-password"></i>
                                        @error('password')
                                        <span class="help-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="remember_box_outer  d-flex align-items-center justify-content-between">
                                        <div class="remember_box">
                                            <label class="custom_checkbox">Remember me
                                                <input id="remember_me" type="checkbox" class="" name="remember" {{ ($remember) ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <a href="{{ route('frontend.password.request') }}" class="forgot_password_link">Forgot Password</a>
                                    </div>
                                    <div>
                                        <button type="submit" class="greebtn align-items-center text-uppercase font-weight-bold shadow onSubmitButton"> Login 
                                            <img src="{{ asset('img/arrow.png') }}" alt=""> 
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 loginimg d-flex align-items-center justify-content-center">
                        <img src="{{ asset('img/loginimg.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    @push('scripts')
    <script type="text/javascript">
    $(document).ready(function(){
      $("form").submit(function( event ) {
        $('.onSubmitButton').attr('disabled', 'disabled');
      });
    });
        $(document).ready(function() {
            $(".show-password, .hide-password").on('click', function() {
                var passwordId = $(this).parents('div.passwordOuter').find('input').attr('id');
                if ($(this).hasClass('show-password')) {
                    $("#" + passwordId).attr("type", "text");
                    $(this).parent().find(".show-password").hide();
                    $(this).parent().find(".hide-password").show();
                } else {
                    $("#" + passwordId).attr("type", "password");
                    $(this).parent().find(".hide-password").hide();
                    $(this).parent().find(".show-password").show();
                }
            });
        });
    </script>
    @endpush
    </x-slot>
</x-front-layout>
