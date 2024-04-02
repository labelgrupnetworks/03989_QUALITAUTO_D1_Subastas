<header>
    <nav class="navbar navbar-default">
        <div class="panel-container">

            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo-web.png"
                        alt="{{ config('app.name') }}" width="250" height="40">
                </a>
            </div>

            <div class="collapse navbar-collapse">
                @include('includes.header-nav')
            </div>

            <a class="btn btn-default navbar-btn">
                sesión
            </a>

        </div>
    </nav>

</header>
