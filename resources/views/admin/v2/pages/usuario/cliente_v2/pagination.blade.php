@if($clientes->hasPages())
  <nav>
    <ul class="pagination">
      {{-- Previous --}}
      <li class="page-item @if($clientes->onFirstPage()) disabled @endif">
        <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev">&laquo;</a>
      </li>
      {{-- Páginas --}}
      @foreach ($clientes->links()->elements[0] as $page => $url)
        <li class="page-item @if($page == $clientes->currentPage()) active @endif">
          <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
      @endforeach
      {{-- Next --}}
      <li class="page-item @if(!$clientes->hasMorePages()) disabled @endif">
        <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next">&raquo;</a>
      </li>
    </ul>
  </nav>
@endif
