@extends('layouts.app')

@section('content')
    <h1 class="title">@lang('Update User')</h1>

    <div class="column is-6 is-offset-3">

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
                <label class="label">@lang('Roles')</label>
                @foreach($roles as $role)
                    <label class="checkbox">
                        <input type="checkbox"
                               name="roles[]"
                               value="{{ $role->id }}"
                               id="role-{{ $role->name }}"
                               @if((empty(old()) && $user->hasRole($role->name)) || (is_array(old('roles')) && in_array($role->id, old('roles')))) checked @endif
                        >
                        {{ $role->name }}
                    </label>
                @endforeach
                @if ($errors->has('roles'))
                    <p class="help is-danger">{{ $errors->first('roles') }}</p>
                @endif
            </div>

            <div class="field is-pulled-right">
                <div class="control">
                    <button class="button is-primary">@lang('Update')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
