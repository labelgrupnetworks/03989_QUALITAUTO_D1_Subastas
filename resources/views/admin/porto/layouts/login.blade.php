<!doctype html>
<html>
    <head>
        @include('admin::includes.head')
    </head>
    <body>
        <section class="body">

            <div class="container">
                @yield('content')
            </div>

        </section>
        @include('admin::includes.foot')

    </body>
</html>