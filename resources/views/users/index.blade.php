@extends('layouts.app')

@section('content')
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
@endsection