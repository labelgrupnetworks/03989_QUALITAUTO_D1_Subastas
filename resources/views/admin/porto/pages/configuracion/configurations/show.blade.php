@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-9">
                <h1 class="m-0">Configuración de {{ $section }}</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12">
                @csrf
                <table class="table table-striped table-bordered table-responsive" id="" style="width:100%">
                    <thead>
                        <tr>
                            <th>Configuración</th>
                            <th>Descripción</th>
                            <th>Valor</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($configurations as $configuration)
                            <tr>
                                <td>{{ $configuration->key }}</td>
                                <td>{{ $configuration->meta['description'] ?? '' }}</td>
                                <td>
									@if(!empty($configuration->meta))
										@if($configuration->meta['type'] === 'boolean')
											<select class="form-control form-select" name="{{ $configuration->key }}" id="">
												<option value="1" {{ $configuration->value ? 'selected' : '' }}>Sí</option>
												<option value="0" {{ !$configuration->value ? 'selected' : '' }}>No</option>
											</select>
										@elseif($configuration->meta['type'] === 'select_multiple')
											@foreach ($configuration->meta['values'] as $keyValue => $value)
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="{{ $configuration->key }}[]" value="{{ $keyValue }}" id="{{ $configuration->key }}_{{ $keyValue }}"
														@checked(in_array($keyValue, array_map('trim', explode(',', $configuration->value))))>
													<label class="form-check-label" for="{{ $configuration->key }}_{{ $keyValue }}">
														{{ $value }}
													</label>
												</div>
											@endforeach
										@elseif($configuration->meta['type'] === 'string')
											<input type="text" class="form-control" name="{{ $configuration->key }}" value="{{ $configuration->value }}">
										@elseif($configuration->meta['type'] === 'integer')
											<input type="number" class="form-control" name="{{ $configuration->key }}" value="{{ $configuration->value }}">
										@else

											{{ $configuration->value }}
										@endif
									@else
										{{ $configuration->value }}
									@endif
								</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    @stop
