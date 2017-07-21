@extends('layouts.app')

@section('content')
    @if (session('status'))
        {{ session('status') }}
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
            <strong>{{ $errors->first('email') }}</strong>
        @endif
        <button>Send Password Reset Link</button>
    </form>
@endsection
