@extends('admin::layouts.logged')
@section('content')

    <?php
    $cbs = isset($_GET['cbs']) ? $_GET['cbs'] : '';
    $see = isset($_GET['see']) ? $_GET['see'] : 'B';
    ?>
    <section role="main" class="content-body">
        <header class="page-header">
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="/admin">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                </ol>
                <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
            </div>
        </header>

        <div id="faqs">

            <section class="panel">
                <div class="panel-body">

                    <div class="row">

                        <div class="col-xs-8">
                            <h1>Faqs</h1>
                            <br>
                        </div>
                    </div>

                    <!-- Nav menu -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tabs tabs-bottom tabs-primary">
                                <ul class="nav nav-tabs nav-justified">
                                    @if ($data['lang'] === 'es')
                                        <li class="active" id="">
                                            <a href="#español" data-toggle="tab" class="text-center"> Español </a>
                                        </li>
                                        <li class="" id="lang_en">
                                            <a href="#english" data-toggle="tab" class="text-center"> English</a>
                                        </li>
                                    @else
                                        <li class="" id="lang_es">
                                            <a href="#español" data-toggle="tab" class="text-center"> Español </a>
                                        </li>
                                        <li class="active" id="">
                                            <a href="#english" data-toggle="tab" class="text-center"> English</a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content">

                                    <div id="español" class="tab-pane active">
                                        @include('admin::pages.V5.admin-blades.faqContentList', ['lang' => 'ES', 'data' =>
                                        $data])
                                    </div>
                                    <div id="english" class="tab-pane">
                                        @include('admin::pages.V5.admin-blades.faqContentList', ['lang' => 'EN', 'data' =>
                                        $data])
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>


                    <br><br>

                    <h3>Nueva categoría</h3>

                    <form name="newCat" id="newCat" action="javascript:newFaqCat();">
                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer"
                            id="datatable-default" role="grid" aria-describedby="datatable-default_info">

                            <tr>
                                <th>{{ trans(\Config::get('app.theme') . '-app.faq.newCategory_name') }}</th>
                                <th>{{ trans(\Config::get('app.theme') . '-app.faq.newCategory_parent') }}</th>
                                <th></th>
                            </tr>

                            <tr>
                                <td>
                                    <input value="" name="new" id="new" class="form-control input-lg">
                                </td>
                                <td>
                                    <select name="parent" id="parent" class="form-control input-lg">
                                        <option value="0">Ninguno</option>
                                        @foreach ($data['catsU'] as $kk => $vv)
                                            <option value="{{ $vv['info']->cod_faqcat }}">
                                                {{ $vv['info']->nombre_faqcat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="submit" class="btn btn-primary"></td>
                            </tr>

                        </table>
                    </form>




                </div>
            </section>
        </div>
    </section>

@stop
