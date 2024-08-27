@if (session('errors'))
    @php
        $formatErrors = is_array($errors) ? $errors : $errors->all();
    @endphp

    @foreach ($formatErrors as $error)
        <div class="alert alert-danger" role="alert">
            <button class="close" data-dismiss="alert" type="button" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <strong>{{ $error }}</strong>
        </div>
    @endforeach
@endif

@if (is_array(session('success')))
    @foreach (session('success') ?? [] as $key => $success)
        <div class="alert alert-success" role="alert">
            <button class="close" data-dismiss="alert" type="button" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <strong>
                {{-- show key only if not number --}}
                {{ intval($key) === $key ? '' : $key . ': ' }}
                {{ $success }}
            </strong>
        </div>
    @endforeach
@elseif(!empty(session('success')))
    <div class="alert alert-success" role="alert">
        <button class="close" data-dismiss="alert" type="button" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        <strong>{{ session('success') }}</strong>
    </div>
@endif

@foreach (session('warning') ?? [] as $key => $warning)
    <div class="alert alert-warning" role="alert">
        <button class="close" data-dismiss="alert" type="button" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        <strong>
            {{ intval($key) === $key ? '' : $key . ': ' }}
            {!! $warning !!}
        </strong>
    </div>
@endforeach
