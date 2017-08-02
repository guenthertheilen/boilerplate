@extends('layouts.app')

@section('content')
    <div class="column is-6 is-offset-3">

        <h1 class="title">@lang('Update User')</h1>

        <form method="POST" action="{{ route('user.update', $user->id) }}">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="field">
                <label for="name" class="label">@lang('Name')</label>
                <div class="control">
                    <input id="name"
                           name="name"
                           class="input @if ($errors->has('name')) is-danger @endif"
                           value="{{ old('name', $user->name) }}"
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
                           value="{{ old('email', $user->email) }}"
                           required>
                </div>
                @if ($errors->has('email'))
                    <p class="help is-danger">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="field">
                <div class="control">
                    <button class="button is-primary">@lang('Update')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
