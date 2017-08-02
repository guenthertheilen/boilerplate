@extends('layouts.app')

@section('content')
    <div class="column is-8">
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
    </div>
@endsection