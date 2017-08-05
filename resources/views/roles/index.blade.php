@extends('layouts.app')

@section('content')
    <h1 class="title">@lang('Roles')</h1>

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
                    <a href="{{ route('role.edit', $role->id) }}">
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