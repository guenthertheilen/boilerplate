@extends('layouts.app')

@section('content')
    <h1 class="title">@lang('Update Permission')</h1>

    <div class="column is-6 is-offset-3">

        <form method="POST" action="{{ route('permission.update', $permission->id) }}">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="field">
                <label for="name" class="label">@lang('Name')</label>
                <div class="control">
                    <input id="name"
                           name="name"
                           class="input @if ($errors->has('name')) is-danger @endif"
                           value="{{ old('name', $permission->name) }}"
                           required
                           autofocus>
                </div>
                @if ($errors->has('name'))
                    <p class="help is-danger">{{ $errors->first('name') }}</p>
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
