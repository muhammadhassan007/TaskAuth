<h1>Home</h1>
<a href="{{ route('Logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
<form id="logout-form" action="{{ route('Logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<a href="{{ route('GetTokenPage') }}">Reset/Update Password</a>
