<h1>CUSTOMER DASHBOARD</h1>
<form method="POST" action="{{ route('logout') }}" id="logout-form">
    @csrf
    <a href="#" class="dropdown-item"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="ti ti-logout"></i>
        <span>Logout</span>
    </a>
</form>
