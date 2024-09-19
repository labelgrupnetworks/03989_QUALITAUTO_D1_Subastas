@extends('admin::layouts.logged')
@section('content')
    <section class="content-body" id="blog-index-page" role="main">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>Upload File</h1>
            </div>
        </div>

        <div class="row well tabs-well">

            <div class="col-xs-4 pt-2 categories-block">
                {{--  @if (session('success'))
                    <p>{{ session('success')[0] }}</p>
                    <p>File URL:
                        <a
                            href="{{ Storage::disk('public_uploads')->url(session('file_path')) }}">{{ Storage::disk('public_uploads')->url(session('file_path')) }}</a>
                    </p>
                    <img src="{{ Storage::disk('public_uploads')->url(session('file_path')) }}" alt="Uploaded Image">
                @endif --}}

                <div class="pt-4">
                    <form action="{{ route('admin.contenido.uploads.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input class="form-control mb-3" name="file" type="file" required>
                        <button class="btn btn-primary btn-block" type="submit">Upload</button>
                    </form>
                </div>
            </div>

            <div class="col-xs-8">
                <table class="table table-striped table-condensed table-responsive" id="" style="width:100%">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Last Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($files as $file)
                            <tr>
                                <td class="js-editable-column" onclick="makeEditable(event, '{{ $file['name'] }}')">
                                    {{ $file['name'] }}
                                </td>
                                <td>{{ $file['size'] }}</td>
                                <td>{{ $file['lastModified'] }}</td>
                                <td>
                                    @if ($file['isImage'])
                                        <button class="btn btn-xs btn-primary" onclick="showModal('{{ $file['url'] }}')">
                                            View
                                        </button>
                                    @else
                                        <a class="btn btn-xs btn-primary" href="{{ $file['url'] }}" target="_blank">
                                            View
                                        </a>
                                    @endif
                                    <form style="display:inline;"
                                        action="{{ route('admin.contenido.uploads.delete', ['fileName' => $file['name']]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4">
                                    <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
                                </td>
                            </tr>
                        @endforelse


                    </tbody>

                </table>
            </div>
        </div>

    </section>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title">Image Preview</h4>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Image" style="width: 100%;">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetCells() {
            const cells = document.querySelectorAll('.js-editable-column');
            cells.forEach(cell => {
                if (cell.querySelector('input')) {
                    cell.innerHTML = cell.querySelector('input').dataset.originalName;
                }
            });
        }

        function makeEditable(event, fileName) {

            const cell = event.target;

            if (cell.querySelector('input') || !cell.classList.contains('js-editable-column')) {
                return;
            }

            resetCells();

            //remove extension from the file name
            const fileNameWithoutExtension = fileName.split('.').slice(0, -1).join('.');

            cell.innerHTML =
                `<div class="input-group">
					<input type="text" class="form-control input-sm" value="${fileNameWithoutExtension}" data-original-name="${fileName}">
      				<div class="btn btn-xs btn-success input-group-addon" onclick="saveChanges(event, '${fileName}')">
						<i class="fa fa-check"></i>
					</div>
    				<div class="btn btn-danger btn-xs input-group-addon" onclick="revertChanges(event, '${fileName}')">
						<i class="fa fa-times"></i>
					</div>
				</div>`;

            cell.querySelector('input').focus();
        }

        function saveChanges(event, fileName) {
            const cell = event.target.closest('td');
            const newName = cell.querySelector('input').value;

            if (newName === fileName) {
                cell.innerHTML = newName;
                return;
            }

            let url = '{{ route('admin.contenido.uploads.update', ['fileName' => '0']) }}';

            //replace the placeholder with the actual file name
            url = url.replace('0', fileName);

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').content
                },
                body: JSON.stringify({
                    new_name: newName
                })
            }).then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('An error occurred while updating the file name');
                }
            })
        }

        function revertChanges(event, fileName) {
            const cell = event.target;
            cell.closest('td').innerHTML = fileName;
        }

        function showModal(url) {
            document.getElementById('modalImage').src = url;
            $('#imageModal').modal('show');
        }
    </script>
@endsection
