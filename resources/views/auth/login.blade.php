@extends('layouts.app')

@section('content')
    <h1 class="title">@lang('Login')</h1>

    <div class="column is-6 is-offset-3">

        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="field">
                <label for="email" class="label">@lang('E-Mail Address')</label>
                <div class="control">
                    <input id="email"
                           class="input @if ($errors->has('email')) is-danger @endif"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
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
                <label class="checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('Remember Me')
                </label>
            </div>

            <div class="field is-pulled-right">
                <div class="control">
                    <button class="button is-primary is-pulled-right">@lang('Login')</button>
                </div>
                <a href="{{ route('password.request') }}" class="button is-link is-small is-paddingless">@lang('Forgot Your Password?')</a>
            </div>

        </form>
    </div>
@endsection
