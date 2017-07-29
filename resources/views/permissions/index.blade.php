@extends('layouts.app')

@section('content')
    <ul>
        @foreach($permissions as $permission)
            <li>{{$permission->name}}</li>
        @endforeach
    </ul>
@endsection