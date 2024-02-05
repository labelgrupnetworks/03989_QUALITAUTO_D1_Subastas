<div class="description" style="--max-lines: 3; --line-height: 1.5">
    <p>{!! str_replace('&nbsp;', ' ', $lote_actual->desc_hces1) !!}</p>
</div>

{{-- <button class="btn btn-outline-lb-primary active d-none" data-js="show-more" data-txt-showmore="Ver más"
    data-txt-showless="Ver menos">
    Ver menos
</button> --}}

{{-- <script>
    if (isDescriptionOverflow()) {
        $('[data-js="show-more"]').removeClass('d-none');
    }

    $('[data-js="show-more"]').on('click', function() {
        $(this).toggleClass('active');

        $(this).text($(this).hasClass('active') ? $(this).data('txt-showless') : $(this).data('txt-showmore'));
        $('.description').toggleClass('max-lines');
    });

    function isDescriptionOverflow() {
        const description = document.querySelector('.description');

        // esta condición es para que se muestre el botón de ver más si el texto es muy largo
        // pero la última petición es que por defecto se muestre el texto completo
        //return description.scrollHeight > description.clientHeight;
        const coputedStyle = window.getComputedStyle(description);

        const maxLines = parseInt(coputedStyle.getPropertyValue('--max-lines'));
        const lineHeight = parseFloat(coputedStyle.getPropertyValue('--line-height'));
        const fontSize = parseFloat(coputedStyle.getPropertyValue('font-size'));

        return description.clientHeight > (maxLines * lineHeight * fontSize);
    }
</script> --}}
