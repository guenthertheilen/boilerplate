@extends('layouts.app')

@section('content')
    <div class="column is-8">
        <table class="table">
            <thead>
            <tr>
                <th>@lang('Permission')</th>
                <th>@lang('Roles')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $permission)
                <tr>
                    <td>{{$permission->name}}</td>
                    <td>{{$permission->rolesAsString()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection