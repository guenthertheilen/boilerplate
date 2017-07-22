@extends('layouts.app')

@section('content')
    <div class="column is-6 is-offset-3">

        <h1 class="title">@lang('Register')</h1>

        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="field">
                <label for="name" class="label">@lang('Name')</label>
                <div class="control">
                    <input id="name"
                           name="name"
                           class="input @if ($errors->has('name')) is-danger @endif"
                           value="{{ old('name') }}"
                           required
                           autofocus>
                </div>
                @if ($errors->has('name'))
                    <p class="help is-danger">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div class="field">
                <label for="email" class="label">@lang('E-Mail Address')</label>
                <div class="control">
                    <input id="email"
                           class="input @if ($errors->has('email')) is-danger @endif"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required>
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
                           class="input"
                           type="password"
                           name="password_confirmation"
                           required>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <button class="button is-primary">@lang('Register')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
