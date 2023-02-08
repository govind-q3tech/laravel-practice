<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot  name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('frontend.verification.send') }}">
                @csrf

                <div>
                    <x-jet-button type="submit" class="greebtn align-items-center text-uppercase font-weight-bold shadow mt-2">
                        {{ __('Resend Verification Email') }}
                    </x-jet-button>
                </div>
            </form>

            <form method="POST" action="{{ route('frontend.logout') }}">
                @csrf
                <button type="submit" class="forgot_password_link">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </x-jet-authentication-card>
    <style type="text/css">
        .greebtn {
            border-radius: 4px;
            background-color: #00c364;
            padding: 11px 21px;
            border: 0px;
            font-size: 14px;
            color: #fff;
            position: relative;
            height: 46px;
        }
        .forgot_password_link {
            font-weight: 300;
            color: #2793fa;
            font-size: 14px;
            text-decoration: underline;
        }
    </style>
</x-guest-layout>
