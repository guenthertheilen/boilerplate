@extends('layouts.app')

@section('content')
    <h1 class="title is-pulled-left">@lang('Roles')</h1>

    <a href="{{ route('role.create') }}" class="button is-primary is-pulled-right">@lang('Add Role')</a>

    <table class="table">
        <thead>
        <tr>
            <th>@lang('Role')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>
                    <a href="{{ route('role.edit', $role->id) }}" id="role-edit-{{ $role->id }}">
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