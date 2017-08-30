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
            @guest
                <a href="{{ route('login') }}" class="navbar-item">@lang('Login')</a>
                <a href="{{ route('register') }}" class="navbar-item">@lang('Register')</a>
            @endguest

            @auth
                <a href="#" class="navbar-item">{{ Auth::user()->name }}</a>
                @authorized('user.index')
                <a href="{{ route('user.index') }}" class="navbar-item">@lang('Users')</a>
                @endauthorized

                @authorized('role.index')
                <a href="{{ route('role.index') }}" class="navbar-item">@lang('Roles')</a>
                @endauthorized

                @authorized('permission.index')
                <a href="{{ route('permission.index') }}" class="navbar-item">@lang('Permissions')</a>
                @endauthorized

                <a href="{{ route('logout') }}"
                   class="navbar-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    @lang('Logout')
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endauth
        </div>
    </div>
</nav>