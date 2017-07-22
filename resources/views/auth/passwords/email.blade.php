@extends('layouts.app')

@section('content')
    <div class="column is-6 is-offset-3">

        <h1 class="title">@lang('Reset Password')</h1>

        @if (session('status'))
            {{ session('status') }}
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

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
                <div class="control">
                    <button class="button is-primary">@lang('Send Password Reset Link')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
