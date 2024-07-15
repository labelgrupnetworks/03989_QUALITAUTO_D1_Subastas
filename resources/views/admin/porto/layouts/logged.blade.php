<!doctype html>
<html @class(['fixed', 'sidebar-left-collapsed' => session('admin.navigator_collapse')])>
    <head>
        @include('admin::includes.head')
    </head>

	<body>
        <section class="body">
            @include('admin::includes.header')

            <div class="inner-wrapper">
                @include('admin::includes.left_sidebar')
                @yield('content')
            </div>

            @include('admin::includes.right_sidebar')
        </section>

         <div id="modal_message" class="modal fade @yield('page-modal')" role="dialog">
          <div class="modal-dialog">

            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title"></h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
              </div>
            </div>

          </div>
        </div>

        @include('admin::includes.foot')

    </body>
</html>
