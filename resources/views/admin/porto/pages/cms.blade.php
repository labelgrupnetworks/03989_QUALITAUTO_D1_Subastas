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
                                    <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer"><div class="row datatables-header form-inline"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="datatable-default_length"><label><select name="datatable-default_length" aria-controls="datatable-default" class="select2-hidden-accessible" tabindex="-1" aria-hidden="true"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select><span class="select2 select2-container select2-container--bootstrap" dir="ltr" style="width: 75px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-datatable-default_length-vc-container"><span class="select2-selection__rendered" id="select2-datatable-default_length-vc-container" title="10">10</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span> records per page</label></div></div><div class="col-sm-12 col-md-6"><div id="datatable-default_filter" class="dataTables_filter"><label><input type="search" class="form-control" placeholder="Search" aria-controls="datatable-default"></label></div></div></div><div class="table-responsive"><table class="table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                            <thead>
                                                    <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 269px;">Rendering engine</th><th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 353px;">Browser</th><th class="sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 320px;">Platform(s)</th><th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 231px;">Engine version</th><th class="hidden-xs sorting" tabindex="0" aria-controls="datatable-default" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 164px;">CSS grade</th></tr>
                                            </thead>
                                            <tbody>

                                            <tr role="row" class="odd">
                                                            <td class="sorting_1">Webkit</td>
                                                            <td>Safari 1.2</td>
                                                            <td>OSX.3</td>
                                                            <td class="center hidden-xs">125.5</td>
                                                            <td class="center hidden-xs">A</td>
                                                    </tr><tr role="row" class="even">
                                                            <td class="sorting_1">Webkit</td>
                                                            <td>Safari 1.3</td>
                                                            <td>OSX.3</td>
                                                            <td class="center hidden-xs">312.8</td>
                                                            <td class="center hidden-xs">A</td>
                                                    </tr><tr role="row" class="odd">
                                                            <td class="sorting_1">Webkit</td>
                                                            <td>Safari 2.0</td>
                                                            <td>OSX.4+</td>
                                                            <td class="center hidden-xs">419.3</td>
                                                            <td class="center hidden-xs">A</td>
                                                    </tr><tr role="row" class="even">
                                                            <td class="sorting_1">Webkit</td>
                                                            <td>Safari 3.0</td>
                                                            <td>OSX.4+</td>
                                                            <td class="center hidden-xs">522.1</td>
                                                            <td class="center hidden-xs">A</td>
                                                    </tr><tr role="row" class="odd">
                                                            <td class="sorting_1">Webkit</td>
                                                            <td>OmniWeb 5.5</td>
                                                            <td>OSX.4+</td>
                                                            <td class="center hidden-xs">420</td>
                                                            <td class="center hidden-xs">A</td>
                                                    </tr><tr role="row" class="even">
                                                            <td class="sorting_1">Webkit</td>
                                                            <td>iPod Touch / iPhone</td>
                                                            <td>iPod</td>
                                                            <td class="center hidden-xs">420.1</td>
                                                            <td class="center hidden-xs">A</td>
                                                    </tr><tr role="row" class="odd">
                                                            <td class="sorting_1">Webkit</td>
                                                            <td>S60</td>
                                                            <td>S60</td>
                                                            <td class="center hidden-xs">413</td>
                                                            <td class="center hidden-xs">A</td>
                                                    </tr></tbody>
                                    </table></div><div class="row datatables-footer"><div class="col-sm-12 col-md-6"><div class="dataTables_info" id="datatable-default_info" role="status" aria-live="polite">Showing 51 to 57 of 57 entries</div></div><div class="col-sm-12 col-md-6"><div class="dataTables_paginate paging_bs_normal" id="datatable-default_paginate"><ul class="pagination"><li class="prev"><a href="#"><span class="fa fa-chevron-left"></span></a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="active"><a href="#">6</a></li><li class="next disabled"><a href="#"><span class="fa fa-chevron-right"></span></a></li></ul></div></div></div></div>
                            </div>
                    </section>
		</div>	
		</div>
            </section>

@stop
