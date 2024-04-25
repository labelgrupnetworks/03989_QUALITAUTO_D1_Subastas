@extends('admin::layouts.logged')


@section('content')
    <link href="{{ $base_url }}/vendor/jstree/themes/default/style.min.css" rel="stylesheet" />
    <script src="{{ $base_url }}/vendor/jstree/jstree.js"></script>

    <section class="content-body" role="main">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1 class="m-0">Estado del disco</h1>
            </div>
        </div>

        <div class="row well">

            <p>Espacio total: {{ $spaceInDisk }}</p>
            <p>Espacio libre: {{ $freeSpaceInDisk }}</p>
            <p>% Utilizado: {{ $usedSpacePercent }}</p>

            <div class="directories" id="directories-tree">
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
            }
        });
    </script>
@endsection
