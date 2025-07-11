@extends('admin::layouts.logged')

@php
	$progressBarColor = match(true) {
		$usedSpacePercent < 50 => 'success',
		$usedSpacePercent < 80 => 'warning',
		default => 'danger'
	};
@endphp

@section('content')
    <link href="{{ $base_url }}/vendor/jstree/themes/default/style.min.css" rel="stylesheet" />
    <script src="{{ $base_url }}/vendor/jstree/jstree.js"></script>

    <style>

		.progress {
			background-color: #edeaf6;
		}
        .jstree-icon.image-icon {
            background-image: url("{{ $base_url }}/images/icons/image-file-svgrepo-com.svg");
            background-size: contain;
        }

		.jstree-node > .jstree-anchor {
			display: inline-flex;
			width: 100%;
			gap: 1rem;
		}

		.node_size {
			min-width: 10ch;
			text-align: right;
		}
    </style>

    <section class="content-body" role="main">
                @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1 class="m-0">Estado del disco</h1>
            </div>
        </div>

        <div class="row well">

			<div class="d-flex justify-content-space-between">
				<p>Espacio total: {{ $spaceInDisk }}</p>
				<p>Espacio libre: {{ $freeSpaceInDisk }}</p>
			</div>

            <div class="progress">
                <div class="progress-bar progress-bar-{{$progressBarColor}} progress-bar-striped" role="progressbar" aria-valuenow="{{ $usedSpacePercent }}"
                    aria-valuemin="0" aria-valuemax="100" style="width: {{ $usedSpacePercent }}%">
                    <span>{{ round($usedSpacePercent, 2) }}% Complete</span>
                </div>
            </div>

            {{-- <p>Espacio total: {{ $spaceInDisk }}</p>
            <p>Espacio libre: {{ $freeSpaceInDisk }}</p>
            <p>% Utilizado: {{ $usedSpacePercent }}</p> --}}

			<div class="col-xs-12">
				<div class="directories" id="directories-tree"></div>
			</div>

        </div>

    </section>

    <script>
        $('#directories-tree').jstree({
            'core': {
                'data': {
                    'url': '/admin/disk-status/folder',
                    'data': function(node) {
                        return {
                            'path': node.id === '#' ? '' : node.id
                        };
                    }
                }
            },
            'plugins': ['types'],
            'types': {
                'image': {
                    'icon': 'image-icon'
                }
            }
        });

        $("#plugins7").jstree({
            "types": {
                "default": {
                    "icon": "glyphicon glyphicon-flash"
                },
                "demo": {
                    "icon": "glyphicon glyphicon-ok"
                }
            },
            "plugins": ["types"]
        });
    </script>
@endsection
