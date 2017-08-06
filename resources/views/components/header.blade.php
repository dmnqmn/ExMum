<header class="normal-site-header">
    <nav class="navbar navbar-fixed-top navbar-default header-navbar">
        <div class="container-fluid">
            <a href="#" class="navbar-brand header-brand">ExMum</a>
            <div class="navbar-right header-link-list">
                <a href="{{ route('home') }}" class="navbar-text header-link">首页</a>
                <a href="{{ route('home') }}" class="navbar-text header-link">热门</a>
                <a href="{{ route('home') }}" class="navbar-text header-link">我</a>
                <div class="navbar-right dropdown header-message">
                    <span class="navbar-text fa fa-commenting-o"></span>                    
                </div>
            </div>
            <form class="navbar-form header-search">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="{{ $hotSearch }}">
                    <span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                </div>
            </form>
        </div>
    </nav>
</header>
