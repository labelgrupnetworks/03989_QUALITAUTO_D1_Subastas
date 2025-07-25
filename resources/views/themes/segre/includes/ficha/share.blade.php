@php
    $lot = $data['subasta_info']->lote_actual;
    $name = Config::get('app.name');
    $url = url()->current();
    $titulo = $lot->descweb_hces1;
    $description = $lot->desc_hces1;
@endphp

<div class="zone-share-social">
    <button class="btn btn-link btn-icon btn-share-lot" data-url="{{ $url }}" data-title="{{ $titulo }}"
        data-text="{{ $description }}">
        <x-icon.boostrap icon="share" />
        {{ trans("$theme-app.lot.share") }}
    </button>

    @if (Session::has('user') && !$retirado)
        <button id="del_fav" onclick="action_fav_modal('remove')" @class(['btn btn-link btn-icon', 'hidden' => !$lote_actual->favorito])>
            <x-icon.boostrap icon="heart-fill" />
            {{ trans("web.lot.like") }}
        </button>
        <button id="add_fav" onclick="action_fav_modal('add')" @class(['btn btn-link btn-icon', 'hidden' => $lote_actual->favorito])>
            <x-icon.boostrap icon="heart" />
            {{ trans("web.lot.like") }}
        </button>
	@else
		<button id="add_fav"
			class="btn btn-link btn-icon btn_login">
			<x-icon.boostrap icon="heart" />
			{{ trans("web.lot.like") }}
		</button>
    @endif
</div>

{{-- modal to fallback navigtor.share --}}
<div class="modal fade" id="modal-share-lot" aria-labelledby="modal-share-lot" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <h5 class="modal-title">{{ trans("$theme-app.lot.share") }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="share-buttons mt-3">
                    <a class="share-facebook" href="https://www.facebook.com/sharer.php?u={{ $url }}"
                        title="Compartir en Facebook" target="_blank">
                        <x-icon.boostrap icon="facebook" />
                    </a>
                    <a class="share-twitter"
                        href="https://twitter.com/share?text={{ $titulo }}&url={{ $url }}"
                        title="Compartir en Twitter" target="_blank">
                        @include('components.x-icon', ['size' => '1em'])
                        {{-- <x-icon.boostrap icon="twitter-x" /> --}}
                    </a>

                    <a class="share-email" href="mailto:?Subject={{ $name }}&body={{ $url }}"
                        title="Compartir por correo" target="_blank">
                        <x-icon.boostrap icon="envelope-fill" />
                    </a>
                    <a class="share-whatsapp" href="whatsapp://send?text={{ $url }}"
                        title="Compartir en WhatsApp" target="_blank">
                        <x-icon.boostrap icon="whatsapp" />
                    </a>

                    <a class="share-link" href="{{ $url }}" title="Copiar enlace" target="_blank">
                        <x-icon.boostrap icon="link-45deg" />
                        <span class="copy-text mt-1 d-none">
							{{ trans("web.lot.copied") }}
						</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const btnShareLot = document.querySelector('.btn-share-lot');
        const modalShareLot = new bootstrap.Modal(document.getElementById('modal-share-lot'));
        const shareLink = document.querySelector('.share-link');

        btnShareLot.addEventListener('click', function() {

            if (navigator.share) {
                navigator.share({
                        title: this.getAttribute('data-title'),
                        text: this.getAttribute('data-text'),
                        url: this.getAttribute('data-url')
                    })
                    .then(() => console.log("Compartido con éxito"))
                    .catch((error) => console.error("Error al compartir:", error));
            } else {
                modalShareLot.show();
            }

        });

        shareLink.addEventListener('click', function(event) {
            event.preventDefault();
            const url = this.getAttribute('href');

            const showCopyText = () => {
                const copyTextElem = this.querySelector('.copy-text');
                copyTextElem.classList.remove('d-none');
                setTimeout(() => {
                    copyTextElem.classList.add('d-none');
                }, 2000);
            };

            try {
                navigator.clipboard.writeText(url)
                    .catch((error) => {
                        console.error('Error al copiar el enlace:', error);
                    });
            } finally {
                showCopyText();
            }
        });
    });
</script>
