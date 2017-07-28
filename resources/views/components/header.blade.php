<header class="normal-site-header">
    <a href="{{ route('home') }}">ExMum</a>
    <input type="text" placeholder="{{ $hotSearch }}" class="header-search">
    <a>发现</a>
    <account-manager :logged-in="{{ is_null(\Globals::$user) ? 'false' : 'true' }}"></account-manager>
    <a>消息</a>
</header>
