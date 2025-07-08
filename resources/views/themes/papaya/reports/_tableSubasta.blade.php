@if (!empty($tablaSubasta))
    <table class="table-bordered" style="width: 100%">

        <tbody>

            @while ($value = current($tablaSubasta))
                <tr>
                    <td class="td-title">{{ key($tablaSubasta) }}</td>
                    <td>{{ $value }}</td>

                    @php
                        next($tablaSubasta);
                        $value = current($tablaSubasta);
                    @endphp

                    @if (!empty(key($tablaSubasta)))
                        <td class="td-title">{{ key($tablaSubasta) }}</td>
                        <td>{{ $value }}</td>
                        @php
                            next($tablaSubasta);
                        @endphp
                    @else
                        <td class="td-title"></td>
                        <td></td>
                    @endif

                </tr>
            @endwhile

        </tbody>
    </table>
@endif
