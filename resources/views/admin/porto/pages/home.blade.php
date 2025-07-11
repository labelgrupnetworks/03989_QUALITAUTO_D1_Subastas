@extends('admin::layouts.logged')
@section('content')

	<section role="main" class="content-body">
		<div class="row">
                    <div class="col-md-12">
                            <h1>Dashboard</h1>
                    </div>

                    <div class="col-md-3">
                        @if($pos = strpos(Session::get('user.usrw'), '@labelgrup') || $pos = strpos(Session::get('user.usrw'), '@LABELGRUP'))
                        <form method="get">
                           Codigo de cliente: <input class='form-control' type="text" name="codcli"><br>
                           Token:<input class="form-control" type="text" name="token">
                           <br>
                           <button class="mb-xs mt-xs mr-xs btn btn-md btn-primary"> Submit </button>
                        </form>
                        <p style="font-size: 16px;">

                        <?php
                           $redirect = false;
                           if(!empty(Request::input('codcli')) && !empty(Request::input('token'))){
                               $user = new App\Models\User();
                               $userCont = new App\Http\Controllers\UserController();
                               $user->cod_cli = Request::input('codcli');
                               $inf_cli = $user->getUser();
                               if(!empty($inf_cli) && !empty($inf_cli->tk_cliweb) && $inf_cli->tk_cliweb == Request::input('token')){
                                   Session::flush();
                                    $userCont->SaveSession($inf_cli);
                                    $redirect = true;
                               }
                           }
                        ?>
                        @if($redirect)
                            <script type="text/javascript">
                                window.location.href = "/";
                            </script>
                        @endif
                        </p>
                        @endif
                    </div>
		</div>

	</section>

@stop
