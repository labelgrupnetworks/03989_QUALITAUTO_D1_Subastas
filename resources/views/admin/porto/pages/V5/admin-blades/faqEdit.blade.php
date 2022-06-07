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
                <a class="sidebar-right-toggle" href="/admin/faqs"><i class="fa fa-chevron-left"></i></a>
            </div>
        </header>

        <div id="faqs">

            <section class="panel">
                <div class="panel-body">

                    <h1>Faqs - Edit</h1>

                    <div class="tabs tabs-bottom tabs-primary">
                        <form name="formWEB_FAQ" id="formWEB_FAQ" method="post" action="javascript:saveFaq();">

                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Categoria:</label>
                                    {!! $data['formulario']['COD_FAQCAT'] !!}
                                </div>
                            </div>
                            <br>


                            {!! $data['formulario']['COD_FAQ'] !!}

                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Título:</label>
                                    {!! $data['formulario']['TITULO_FAQ'] !!}
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Texto descriptivo:</label>
                                    {!! $data['formulario']['DESC_FAQ'] !!}
                                </div>

                            </div>

                            <br><br>
                            <center>{!! $data['formulario']['SUBMIT'] !!}</center>
                            <br>
                        </form>

                    </div>
            </section>
        </div>
    </section>

@stop
