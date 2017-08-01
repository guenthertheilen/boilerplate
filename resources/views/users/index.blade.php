@extends('layouts.app')

@section('content')
    <div class="column is-8">
        <table class="table">
            <thead>
            <tr>
                <th>@lang('User')</th>
                <th>@lang('Roles')</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->rolesAsString()}}</td>
                    <td>
                        <a href="{{route('user.edit', $user->id)}}">
                            <span class="icon is-small">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection