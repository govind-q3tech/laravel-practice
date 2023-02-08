<x-layouts.front-layout>
    @section('title', 'Forgot password')

    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label' => 'Forgot password']]]) }}
    </x-slot>
    <x-slot name="content">
        <div class="login-area">
            <div class="white-box login-box">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="user-login">
                            <div class="loginhead">
                                <figure><img src="{{ asset('img/login-icon.png') }}" alt=""></figure>
                                <h2>Lost your password?</h2>
                                <p>Please enter your username or email address. You will receive a link to create a new
                                    password via email.</p>
                                <div class="clear"></div>
                                <img src="{{ asset('img/line.png') }}" alt="">
                            </div>
                            <form method="POST" action="{{ route('frontend.forgot-password.email') }}"
                                id="forgot-pwd">
                                @csrf
                                <div class="form_filds_block">
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <x-input id="email"
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            type="email" placeholder="Email" name="email" :value="old('email')" />
                                        <i class="far fa-envelope"></i>
                                        @error('email')
                                            <span class="help-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <br />
                                    <div>
                                        <button type="submit"
                                            class="greebtn align-items-center text-uppercase font-weight-bold shadow onSubmitButton">
                                            Submit <img src="img/arrow.png" alt=""> </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5 loginimg d-flex align-items-center justify-content-center">
                        <img src="{{ asset('img/loginimg.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $("form").submit(function(event) {
                    $('.onSubmitButton').attr('disabled', 'disabled');
                });
            });
            $.validator.addMethod("validateEmail", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
            }, "Please enter valid email address!");
            $('#forgot-pwd').validate({

                onkeyup: function(element) {
                    $(element).valid()
                },
                rules: {
                    email: {
                        required: true,
                        email: true,
                        validateEmail: true,                        
                    }
                },
                messages: {
                    email: {
                        required: "The email ID is required field.",
                        //remote: "Email doesn't exist."
                    }

                },
                errorElement: 'span',
                errorClass: 'help-block',
                highlight: function(element, errorClass, validClass) {
                    $(".help-block").remove();
                    $(element).parents("div.form-group").addClass('has-error');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).parents(".has-error").removeClass('has-error');
                    $('.onSubmitButton').prop("disabled", false);
                },
                submitHandler: function(form) {
                    form.submit();
                    return true;
                }
            });
        </script>
    @endpush

    </x-front-layout>
