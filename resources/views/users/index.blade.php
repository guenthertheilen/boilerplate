@extends('layouts.app')

@section('content')
    <h1 class="title is-pulled-left">@lang('Users')</h1>

    <a href="{{ route('user.create') }}" class="button is-primary is-pulled-right">@lang('Add User')</a>

    <table class="table">
        <thead>
        <tr>
            <th>@lang('User')</th>
            <th>@lang('Email')</th>
            <th>@lang('Roles')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->rolesAsString() }}</td>
                <td>
                    <a href="{{ route('user.edit', $user->id) }}" id="user-edit-{{ $user->id }}">
                            <span class="icon is-small">
                                <i class="fa fa-pencil"></i>
                            </span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if(count($users) > 10)
        <a href="{{ route('user.create') }}" class="button is-primary is-pulled-right">@lang('Add User')</a>
    @endif

@endsection