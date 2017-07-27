<nav class="navbar">
    <div class="navbar-brand">
        <a href="{{ url('/') }}" class="navbar-item">{{ config('app.name', 'Laravel') }}</a>
        <div class="navbar-burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="navbar-menu">
        <div class="navbar-end">
            @if (Auth::guest())
                <a href="{{ route('login') }}" class="navbar-item">@lang('Login')</a>
                <a href="{{ route('register') }}" class="navbar-item">@lang('Register')</a>
            @else
                <a href="#" class="navbar-item">{{ Auth::user()->name }}</a>
                @authorize
                <a href="{{ route('foo') }}" class="navbar-item">Foo</a>
                @endauthorize
                <a href="{{ route('logout') }}"
                   class="navbar-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    @lang('Logout')
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
        </div>
    </div>
</nav>