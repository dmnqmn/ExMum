<header class="normal-site-header">
    <nav class="navbar navbar-fixed-top navbar-default header-navbar">
        <div class="container-fluid">
            <a href="/" class="navbar-brand header-brand">ExMum</a>
            <div class="navbar-right header-link-list">
                <a href="{{ route('home') }}" class="navbar-text header-link">首页</a>
                <a href="{{ route('home') }}" class="navbar-text header-link">热门</a>
                @if (\Globals::$user)
                    <a
                        href="{{ route('settings') }}"
                        class="navbar-text header-link"
                        title="{{ \Globals::$user->user_name }}"
                    >我</a>
                @else
                    <a
                        href="javascript:void(0);"
                        class="navbar-text header-link"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="true"
                        title="{{ \Globals::$user ? \Globals::$user->user_name : '' }}"
                    >登录</a>
                @endif
                <a href="javascript:void(0);" class="dropdown navbar-right dropdown header-message">
                    <span class="navbar-text fa fa-commenting-o" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="button"></span>
                    <div class="dropdown-menu"></div>
                </a>
            </div>
            <form class="navbar-form header-search" action="javascript:void(0);">
                <div>
                    <input class="form-control" type="text" placeholder="{{ $hotSearch }}">
                </div>
            </form>
        </div>
    </nav>
    <account-manager ref="accountManager"></account-manager>
</header>
