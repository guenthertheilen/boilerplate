@extends('layouts.app')

@section('content')
    <h1 class="title">@lang('Reset Password')</h1>

    <div class="column is-6 is-offset-3">

        @if (session('status'))
            {{ session('status') }}
        @endif

        <form method="POST" action="{{ route('password.request') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <label for="email" class="label">@lang('E-Mail Address')</label>
                <div class="control">
                    <input id="email"
                           class="input @if ($errors->has('email')) is-danger @endif"
                           type="email"
                           name="email"
                           value="{{ $email or old('email') }}"
                           required
                           autofocus>
                </div>
                @if ($errors->has('email'))
                    <p class="help is-danger">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="field">
                <label for="password" class="label">@lang('Password')</label>
                <div class="control">
                    <input id="password"
                           class="input @if ($errors->has('password')) is-danger @endif"
                           type="password"
                           name="password"
                           required>
                </div>
                @if ($errors->has('password'))
                    <p class="help is-danger">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="field">
                <label for="password-confirm" class="label">@lang('Confirm Password')</label>
                <div class="control">
                    <input id="password-confirm"
                           class="input @if ($errors->has('password_confirmation')) is-danger @endif"
                           type="password"
                           name="password_confirmation"
                           required>
                </div>
                @if ($errors->has('password_confirmation'))
                    <p class="help is-danger">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="field is-pulled-right">
                <div class="control">
                    <button class="button is-primary">@lang('Save New Password')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
