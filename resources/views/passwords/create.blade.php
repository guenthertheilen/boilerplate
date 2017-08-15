@extends('layouts.app')

@section('content')
    <h1 class="title">@lang('Create Password')</h1>

    <div class="column is-6 is-offset-3">

        <form method="POST" action="{{ route('password.store') }}">
            {{ csrf_field() }}

            <input type='hidden' name='activation_token' value='{{ $token }}'>

            <div class="field">
                <label for="name" class="label">@lang('Email')</label>
                <div class="control">
                    <input id="email"
                           name="email"
                           class="input @if ($errors->has('email')) is-danger @endif"
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
                <label for="password_confirmation" class="label">@lang('Password Confirmation')</label>
                <div class="control">
                    <input id="password"
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
                    <button class="button is-primary">@lang('Save Password')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
