<x-layouts.front-layout>
    <x-slot name="breadcrumb">
        {{ Breadcrumbs::view('partials.breadcrumbs-front', 'front', ['append' => [['label'=> "Reset Password"]]]) }}
    </x-slot>

    <x-slot name="content">
        <div class="login-area">
            <div class="white-box login-box">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <div class="user-login">
                            <div class="loginhead">
                                <figure><img src="{{ asset('img/login-icon.png') }}" alt=""></figure>
                                <h2>Reset Password</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                                <div class="clear"></div>
                                <img src="{{ asset('img/line.png') }}" alt="">
                            </div>
                            @include('flash.alert')
                            <div class="form_filds_block">
                                <form method="POST" action="{{ route('frontend.password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <x-jet-input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : ''}}" type="email" name="email" :value="old('email', $request->email)" required /> <i class="far fa-envelope"></i>
                                         @error('email')
                                        <span class="help-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group passwordOuter {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <x-jet-input id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : ''}}" type="password" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}"/> <i class="far fa-eye hide-password"></i><i class="far fa-eye-slash show-password"></i>
                                         @error('password')
                                        <span class="help-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group passwordOuter {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                        <x-jet-input id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : ''}}" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}" /> <i class="far fa-eye hide-password"></i><i class="far fa-eye-slash show-password"></i>
                                         @error('password_confirmation')
                                        <span class="help-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-jet-button style="width: auto;" type="submit" class="greebtn align-items-center text-uppercase font-weight-bold shadow">
                                            {{ __('Reset Password') }}
                                        </x-jet-button>
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
