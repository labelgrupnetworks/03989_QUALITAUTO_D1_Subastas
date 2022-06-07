@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
        <header class="page-header">
			<h2></h2>

			<div class="right-wrapper pull-right">
				<ol class="breadcrumbs">
					<li>
						<a href="javascript:;">
							<i class="fa fa-home"></i>
						</a>
					</li>
					<li><span> </span></li>
				</ol>

				<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
			</div>
		</header>

	<div id="cms">
            <section class="panel">

                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                                <?php $categories = new \App\Models\SeoCateg;?>
                                                    
                                                <tbody>
                                                    @foreach($data as $categ)
                                                         <?php $inf_categ = $categories->InfCategSeo($categ->cod_sec,Config::get('app.gemp'))?>
                                                        <tr role="row" class="odd">
                                                            <td style="width: 90%; ">
                                                                <a class="" href="seo-categories/name/<?= $categ->cod_sec ?> "> 
                                                                {{$categ->des_sec}}
                                                                 </a>
                                                                 ({{$categ->cod_sec}})
                                                                 
                                                                 -
                                                                 <span class="text-danger">
                                                                 @if(!empty($inf_categ))
                                                                    @foreach($inf_categ as $content)
                                                                        @if(empty($content->webname_seo_sec) || empty($content->webfriend_seo_sec) || empty($content->webmetad_seo_sec) || empty($content->webmetat_seo_sec) || empty($content->webcont_seo_sec))
                                                                            {{$content->codlang_seo_sec}}
                                                                        @endif
                                                                    @endforeach
                                                                 @else
                                                                    @foreach(Config::get('app.locales') as $lang => $idiom)
                                                                        {{strtoupper ($lang)}} &nbsp;
                                                                    @endforeach
                                                                 @endif
                                                                 </span>
                                                            </td>
                                                            <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="seo-categories/name/<?= $categ->cod_sec ?> ">{{ trans('admin-app.title.edit') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                </div>
            </section>
	</div>	
</section>

@stop
