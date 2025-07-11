@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
    <?php $lang = Config::get('app.locales');?>
	<div id="cms">
            <div class="row">
                    <div class="col-md-12">
                            <div class="tabs tabs-bottom tabs-primary">

                                    <ul class="nav nav-tabs nav-justified">
                                        <?php $i=0 ?>
                                        @foreach($lang as $key => $language)
                                            <li class="<?=($i==0)?'active':'';?>">
                                                    <a href="#{{$key}}" data-toggle="tab" class="text-center">{{$language}}</a>
                                            </li>
                                             <?php $i++ ?>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                         <?php $i=0 ?>
                                         @foreach($lang as $key => $language)
                                            <div id="{{$key}}" class="tab-pane <?=($i==0)?'active':'';?>">
                                                    <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                                        <div class="">
                                                                <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                                                        <tbody>
                                                                            @foreach($data as $content)
                                                                                @if($content->lang_web_page == strtoupper($key))
                                                                                <tr role="row" class="odd">
                                                                                    <td style="width: 85%; ">
                                                                                        <a class="" href="content/name/<?= $content->id_web_page ?> " >
                                                                                        {{$content->name_web_page}}
                                                                                         </a>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                    <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="content/name/<?= $content->id_web_page ?> ">{{ trans('admin-app.title.edit') }}</a></td>
                                                                                </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        </tbody>
                                                                </table>
                                                        </div>
                                                </div>
                                            </div>
                                         <?php $i++ ?>
                                        @endforeach
                                    </div>

                            </div>
                    </div>
            </div>
	</div>
</section>

@stop
