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
                             <?php $familysession = new \App\Models\SeoFamiliasSessiones;?>
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                                    
                                                <tbody>
                                                    @foreach($data as $categ)
                                                    <?php $inf_seo = $familysession->SeeFamilySessionsSeo($categ->id_web_auc_index,Config::get('app.emp'))?>
                                                        <tr role="row" class="odd">
                                                            <td style="width: 90%; ">
                                                                <a class="" href="seo-familias-sessiones/name/<?= $categ->id_web_auc_index ?> "> 
                                                                {{$categ->title}}
                                                                 </a>
                                                                  <span class="text-danger">
                                                                    @if(!empty($inf_seo))
                                                                       @foreach($inf_seo as $content)
                                                                           @if(empty($content->webname_auc_seo) || empty($content->webfriend_auc_seo) || empty($content->webmetad_auc_seo) || empty($content->webmetat_auc_seo) || empty($content->webcont_auc_seo))
                                                                               {{$content->codlang_auc_seo}}
                                                                           @endif
                                                                       @endforeach
                                                                    @else
                                                                       @foreach(Config::get('app.locales') as $lang => $idiom)
                                                                           {{strtoupper ($lang)}} &nbsp;
                                                                       @endforeach
                                                                    @endif
                                                                 </span>
                                                            </td>
                                                            <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="seo-familias-sessiones/name/<?= $categ->id_web_auc_index ?> ">{{ trans('admin-app.title.edit') }}</td>
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
