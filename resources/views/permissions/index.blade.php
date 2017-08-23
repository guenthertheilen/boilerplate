@extends('layouts.app')

@section('content')
<h1 class="title">@lang('Permissions')</h1>

<table class="table">
    <thead>
        <tr>
            <th>@lang('Permission')</th>
            <th>@lang('Roles')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissions as $permission)
        <tr>
            <td>{{ $permission->name }}</td>
            <td>{{ $permission->rolesAsString() }}</td>
            <td>
                <a href="{{ route('permission.edit', $permission->id) }}">
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
