@php

use Illuminate\Http\Request;
$qparams = app('request')->query();
@endphp
<x-layouts.front-layout>
@section('title', 'Register')
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "Register"]]]) }}
    </x-slot>
    <x-slot name="content">
        <div class="login-area">
            <div class="white-box login-box">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="user-login">
                            <div class="text-right d-flex justify-content-end align-items-center">Already have an account ? 
                                <a href="{{ route('frontend.login') }}"><button class="bord-btn ml-2">SIGN IN </button></a>
                            </div>
                            <!-- @dump(Session::all()) -->
                            
                            <div class="loginhead">
                                <figure><img src="{{ asset('img/login-icon.png') }}" alt=""></figure>
                                <h2>Sign Up to your Account</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                                <div class="clear"></div>
                                <img src="{{ asset('img/line.png') }}" alt="">
                            </div>
                            <div class="form_filds_block">
                                <form method="POST" enctype="multipart/form-data" action="{{ route('frontend.register',$qparams) }}" id='userRegisterForm'>
                                @csrf
                                {{ Form::hidden('is_verified', '', ['id' => 'is_verified']) }}
                                    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                        {{ Form::text('first_name', old('first_name'), ['class' => $errors->has('first_name') ? 'form-control is-invalid' : 'form-control','placeholder' => 'First Name', 'maxlength' =>'50']) }} <i class="far fa-user"></i>
                                        @error('first_name')
                                            <span class="help-block" role="alert">
                                                {{ $errors->first('first_name') }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        {{ Form::text('last_name', old('last_name'), ['class' => $errors->has('last_name') ? 'form-control is-invalid' : 'form-control','placeholder' => 'Last Name (Optional)', 'maxlength' =>'50']) }} <i class="far fa-user"></i>
                                        <span class="help-block" role="alert">
                                        @error('last_name')
                                            {{ $errors->first('last_name') }}
                                        @enderror
                                        </span>
                                    </div>
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        {{ Form::text('email', old('email'), ['id' => 'email',  'class' => $errors->has('email') ? 'form-control is-invalid' : 'form-control','placeholder' => 'Email']) }} <i class="far fa-envelope"></i>
                                        <span class="help-block" role="alert">
                                        @error('email')
                                           {{ $errors->first('email') }}
                                        @enderror
                                        </span>
                                    </div>
                                    @php
                                    $phone = old('phone');
                                    if(!empty(Session::get('session_otp'))){
                                        $phone =  Session::get('mobile_number');
                                    }
                                    @endphp
                                    <div class="checksendotp justify-content-between align-items-start ">
                                        <div class="phoneNumber form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                        {{ Form::text('phone', $phone, ['id' => 'phoneNumber', 'class' => $errors->has('phone') ? 'form-control is-invalid' : 'form-control','placeholder' => '254xxxxxxxxx or 0xxxxxxxxx','maxlength' => 12]) }} <i class="fas fa-phone"></i>
                                        <span class="help-block phoneNumberMessage" role="alert">
                                            @error('phone')
                                            {{ $errors->first('phone') }}                                            @enderror
                                        </span>
                                        </div>
                                        <div  style="border: 0;">
                                            <button style="margin:0; width: 230px;display: none" type="button" class="bluebtn align-items-center text-uppercase font-weight-bold ml-2 sendOTP"> Verify mobile number </button>
                                        </div>
                                    </div>
                                    <div class="form-group passwordOuter {{ $errors->has('password') ? 'has-error password-error' : '' }}">
                                        <input name="password" type="password" value="" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : ''}}" maxlength="15" placeholder="Password"> <i class="far fa-eye hide-password"></i><i class="far fa-eye-slash show-password"></i>
                                         @error('password')
                                            <span class="help-block" role="alert">
                                                {{ $errors->first('password') }}
                                            </span>
                                          @enderror
                                    </div>
                                    <div class="form-group passwordOuter {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                        <input name="password_confirmation" type="password" value="" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : ''}}" maxlength="15" placeholder="Confirm Password"> <i class="far fa-eye hide-password"></i><i class="far fa-eye-slash show-password"></i>
                                        @error('password_confirmation')
                                            <span class="help-block" role="alert">
                                                {{ $errors->first('password_confirmation') }}
                                            </span>
                                        @enderror
                                    </div>
                                    <!-- http://127.0.0.1:8000/page/terms-and-conditions -->
                                    <div class="form-group" style="border: 0">
                                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                        <div class="mt-4">
                                            <x-jet-label for="terms">
                                                <div class="flex items-center">
                                                    <x-jet-checkbox name="terms" id="terms" />
                                                        &nbsp;{!! __(' By creating an account, you agree to Laravel:terms_of_service and:privacy_policy', [
                                                                'terms_of_service' => ' <a target="_blank" href="'.route('frontend.page.show','terms-and-conditions').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a> ',
                                                                'privacy_policy' => '<a target="_blank" href="'.route('frontend.page.show','privacy-policy').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__(' Privacy Policy').'</a>',
                                                        ]) !!}
                                                    </div>
                                            </x-jet-label>
                                        </div>
                                        @endif
                                        @if($errors->has('terms'))
                                            <span class="help-block" role="alert">
                                                {{ $errors->first('terms') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="submit" class="onSubmitButton greebtn align-items-center text-uppercase font-weight-bold shadow mt-2"> Sign Up <img src="{{ asset('img/arrow.png') }}" alt=""> </button>
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
        <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
              <div class="modal-content">
                <div class="modal-header head-model">
                <h5 class="modal-title pb-0" id="otpModalLabel">OTP Verify</h5>
                <button type="button" id="closeOtp" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="POST" action="{{ route('frontend.user.sendotpverify') }}" id="otpForm" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="action" value="verify_otp">
                  <div id="message-danger-otp" style="display: none;"></div>
                  <div id="message-success-otp" style="display: none;"></div>
                  <div class="modal-body popup-verify">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="margin-bottom: 10px;" class="OTPVerify form-group">
                                {{ Form::text('otp', old('otp'), ['id' => 'OTPVerify', 'class' => 'form-control','placeholder' => 'Verify OTP','maxlength' => 6]) }}
                                <span class="OTPVerifyMessage help-block" role="alert">
                                </span>
                            </div>
                            
                        </div>
                        <!-- <div style="margin-bottom: 10px;" class="OTPVerify verify-button">
                            <button style="margin:0;width: 160px;" type="button" class="greebtn align-items-center text-uppercase font-weight-bold shadow mt-2 OTPVerifyButton"> Verify OTP <img src="{{ asset('img/arrow.png') }}" alt=""> </button>
                        </div> -->
                    </div>
                  </div>
                  <div class="modal-footer">
                    <a style="margin:0;" class="align-items-center text-uppercase mt-2  resend-link OTPResendLink"> Resend OTP</a>
                    <!-- <button type="button" id="otpCancel" class="bluebtn">Cancel</button> -->
                    <button type="submit" class="greebtn submitOtp">Verify OTP</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
    @push('styles')
    <style type="text/css">
        .OTPLoaderBlock{
            display: none;
        }
        .OTPLoaderBlock img {
            width: 50px;
        }
        .sendOTP{
            display: none;
        }
        @if(!empty(Session::get('session_otp')))
            .OTPVerify{
                display: block;
            }
            @if(!empty(Session::get('OTPVerified')))
                .OTPResendLink{
                    display: none;   
                }
                .sendOTP{
                    display: none;
                }
            @else
                .OTPResendLink{
                    display: inline-block;   
                }
            @endif
        @else
            .OTPVerify{
                display: none;
            }
            .OTPResendLink{
                display: none;   
            }
        @endif
    </style>
    @endpush

    @push('scripts')
    <script type="text/javascript">
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
        $('.sendOTP').on('click', function(){
            $(".phoneNumberMessage").html("").hide();
            var number = $("#phoneNumber").val();
            var pattern= /^(254|0)([0-9][0-9]|[0-9][0-1]){1}[0-9]{1}[0-9]{6}$/;
            if(pattern.test( number ) && number != null){
               
            // if (number.length == 10 && number != null) {
                var input = {
                    "mobile_number" : number,
                    "action" : "send_otp"
                };
                $("#loader").show();
                $.ajax({
                    url : "{{ route('frontend.user.sendotpverify') }}",
                    type: 'post',
                    dataType: 'json',
                    data : input,
                    beforeSend: function (xhr) {
                        $('.wrap-loader').removeClass('hide');
                        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success : function(response) {
                        $('.wrap-loader').addClass('hide');
                        if(response.status == 1){
                            $(".OTPVerify").show();
                            $(".phoneNumberMessage").css('color','green');
                            $(".phoneNumberMessage").show();
                            $(".phoneNumberMessage").html(response.message);
                            $('.OTPResendLink').show();
                            // $('.sendOTP').hide();
                            $('#otpModal').modal(
                                {
                                    show: true,
                                    backdrop: 'static',
                                    keyboard: false
                                }
                                );
                            setTimeout(function(){ 
                                $('.phoneNumberMessage').hide();
                             }, 3000);
                            // setTimeout(function(){ 
                            //     $('.OTPResendLink').show();
                            //  }, 60000);
                        }else{
                            $(".phoneNumberMessage").css('color','red');
                            $(".phoneNumberMessage").show();
                            $(".phoneNumberMessage").html(response.message);
                            $(".OTPVerify").hide();
                        }
                    }
                });
            } else {
                $(".phoneNumberMessage").css('color','red');
                $(".phoneNumberMessage").show();
                $(".phoneNumberMessage").html('Please enter a valid number!')
            }
        });
        $('.OTPResendLink').on('click', function(){
            $(".phoneNumberMessage").html("").hide();
            var number = $("#phoneNumber").val();
            $("#loader").show();
            var pattern= /^(254|0)([0-9][0-9]|[0-9][0-1]){1}[0-9]{1}[0-9]{6}$/;
            if(pattern.test( number ) && number != null){
                var input = {
                    "mobile_number" : number,
                    "action" : "resend_otp"
                };
                $('#otpForm')[0].reset();
                $.ajax({
                    url : "{{ route('frontend.user.sendotpverify') }}",
                    type: 'post',
                    dataType: 'json',
                    data : input,
                    beforeSend: function (xhr) {
                        $('.wrap-loader').removeClass('hide');
                        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success : function(response) {
                        
                        // data = JSON.parse(response);
                        $('#message-danger-otp').html('');
                        $('#message-success-otp').html('');
                        $('#message-danger-otp').show();
                        $('#message-success-otp').show();
                        if(response.status == 1){
                            $('.wrap-loader').addClass('hide');
                             $('#message-success-otp').html('<div class="alert alert-success">'+response.message+'</div>');
                            $(".OTPVerify").show();
                            setTimeout(function(){ 
                                $('#message-success-otp').hide();
                             }, 5000);
                        }else{
                            $('#message-danger-otp').html('<div class="alert alert-danger">'+response.message+'</div>');
                            $(".OTPVerify").hide();
                        }
                    },
                    error: function(error){
                        console.log(error);
                        $('#message-danger-otp').html('<div class="alert alert-danger">'+error+'</div>');
                    },
                    complete: function (data) {
                        $('.wrap-loader').addClass('hide');
                        // location.reload(true);
                    }
                });
            } else {
                $(".phoneNumberMessage").css('color','red');
                $(".phoneNumberMessage").show();
                $(".phoneNumberMessage").html('Please enter a valid number!')
            }
        });
        @if(!empty(Session::get('session_otp')))
            // $('.sendOTP').attr('disabled', true);
            $('.sendOTP').hide();
            $('.OTPVerify').show();
            // setTimeout(function(){ 
            //     $('.OTPResendLink').show();
            //  }, 60000);
            @if(!empty(Session::get('OTPVerified')))
                $('#phoneNumber').attr('readonly', true);
                $('.OTPResendLink').hide();
                $('.OTPVerify').hide();
                $('.sendOTP').hide();   
                $(".phoneNumberMessage").css('color','green');
                $(".phoneNumberMessage").show();
                $(".phoneNumberMessage").html('Your mobile number OTP is verified!');
                
            @endif
        @else
        @endif
        $('#otpForm').validate({
            rules: {
                otp: {
                    required: true,
                    number:true,
                    minlength: 6,
                }
            },
            messages: {
                otp:{
                    required: "Please enter OTP",
                    number: "Please enter a valid OTP.",
                    minlength: "Minimum length of OTP 6",
                }
            },
            errorElement: 'div',
            errorClass: 'help-block',
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('text-danger');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".text-danger").removeClass('text-danger');
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                $('#message-danger-otp').hide();
                $('#message-success-otp').hide();
                var formData = new FormData($('#otpForm')[0]);
                $.ajax({
                    url: "{{ route('frontend.user.sendotpverify') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function (xhr) {
                       $('.wrap-loader').removeClass('hide');
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#message-danger-otp').html('');
                        $('#message-success-otp').html('');
                        $('#message-danger-otp').show();
                        $('#message-success-otp').show();
                        if(data.status == 1) {
                            $('#otpForm')[0].reset();
                            $('.wrap-loader').addClass('hide');
                            $('#message-success-otp').html('<div class="alert alert-success">'+data.message+'</div>');
                            $('#is_verified').val(1);
                            setTimeout(function(){ 
                                $('.sendOTP').attr('disabled', true);
                                $('#otpModal').modal('hide');
                                $('#phoneNumber').attr('readonly', true);
                                $('.OTPResendLink').hide();
                                $('.OTPVerify').hide();
                                $('.sendOTP').hide();
                                $('.checksendotp').removeClass('d-flex');
                                $(".phoneNumberMessage").css('color','green');
                                $(".phoneNumberMessage").show();
                                $(".phoneNumberMessage").html('Your mobile number OTP is verified!');
                            }, 1000);
                        }else {
                            $('#is_verified').val(0);
                            $('#message-danger-otp').html('<div class="alert alert-danger">'+data.message+'</div>');
                        }
                    },
                    error: function(error){
                        console.log(error);
                        $('#message-danger-otp').html('<div class="alert alert-danger">'+error+'</div>');
                    },
                    complete: function (data) {
                        $('.wrap-loader').addClass('hide');
                        // location.reload(true);
                    }
                });
            }
        });


       
    </script>
    <script type="text/javascript">
        $.validator.addMethod("mobileKN", function( value, element ) {
            // var pattern= /^(254)([0-9]{9})$/;
            // var pattern= /^(254|0)([7][0-9]|[1][0-1]){1}[0-9]{1}[0-9]{6}$/;
            var pattern= /^(254|0)([0-9][0-9]|[0-9][0-1]){1}[0-9]{1}[0-9]{6}$/;
            if(this.optional( element ) || pattern.test( value )){

                if($("#phoneNumber").is('[readonly]')){

                }else{
                    console.log('okkk');
                    $('.checksendotp').addClass('d-flex');
                    $('.sendOTP').show();
                    $('.sendOTP').attr('disabled', false);
                }
                return true;
            }else{
                $('.checksendotp').removeClass('d-flex');
                $('.sendOTP').attr('disabled', true);
                $('.sendOTP').hide();
                $('.OTPResendLink').hide();
                $(".phoneNumberMessage").hide();
                return false;
            }
        }, "Please specify a valid mobile number." );
        $.validator.addMethod("alpha", function(value, element) {
            return this.optional(element) || value.match(/^[\w]+([-_ \s]{1}[a-z0-9]+)*$/);
            // return this.optional(element) || value == /^[\w.]+$/i.test(value);
        }, "Special characters not allowed.");
        $.validator.addMethod("validateEmail", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
            }, "Please enter valid email address!");
        $('#userRegisterForm').validate({
            onkeyup: function(element) {$(element).valid()},
            rules: {
                first_name: {
                    required: true,
                    alpha: true,
                },
                email : {
                    required: true,
                    email: true,
                    validateEmail: true,
                    remote:{
                        url: '{{ route("frontend.user.checkemail") }}',
                        type: 'post',
                        async: false,
                        dataType: 'json',
                        data: { email: function() {
                                return $("#email").val();
                            } },
                        headers: {
                            "accept": "application/json",
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }
                },
                phone : {
                    required: true,
                    mobileKN: true
                },
                password : {
                    required: true,
                    minlength : 6
                },
                password_confirmation : {
                    required: true,
                    minlength : 6,
                    equalTo : "#password"
                },
                terms: "required"
            },
            messages: {
                first_name: {
                    required: "The first name is required field.",
                },
                email : {
                    required: "The email ID is required field.",
                    remote: 'Email has been taken already.'
                },
                phone : {
                    required: "The phone number is required field",
                },
                password : {
                    required: "The password is required field.",
                },
                password_confirmation : {
                    required: "The confirm password is required field.",
                    equalTo : "The password and confirmation password do not match.",
                },
                terms: "Please check terms of service and privacy policy.",

            },
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error');
            },
            submitHandler: function(form) {
                if($('#is_verified').val()){
                    $('.sendOTP').hide();
                    $('.onSubmitButton').attr('disabled', 'disabled');
                    form.submit();
                }else{
                     toastr.error('Mobile number not verified.');
                }
            }
        });

    </script>
    @endpush
    </x-slot>
</x-front-layout>
