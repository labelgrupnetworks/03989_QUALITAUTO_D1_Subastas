<div class="streaming ratio ratio-16x9">
    <!-- Añadir esta misma página en el themes del cliente, con su iframe correspondiente
    <iframe src="//stream01.labelgrup.com:5443/WebRTCAppEE/play.html?name=273039929349767838886597" height="288" width="512" frameborder="0" allowfullscreen></iframe>
	-->

	{{-- Iframe you tube en bucle --}}
	<iframe width="512" height="288" src="https://www.youtube.com/embed/4ICM50Ksu1E?loop=1&playlist=4ICM50Ksu1E&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

	<!-- video youtube iniciado por javascript
		<div id="video"></div>
	-->
</div>
<p class="delay mt-2 px-1">{{ trans(\Config::get('app.theme').'-app.sheet_tr.delay') }}</p>

<?php /* script para iniciar vido de youtube en div

<script async src="https://www.youtube.com/iframe_api"></script>
<script>
function onYouTubeIframeAPIReady() {
    var player;
    player = new YT.Player("video", {
    videoId: "4ICM50Ksu1E", // Id del vídeo de YouTube christiandve.com
    width: 512, // Ancho del reproductor (en px)
    height: 288, // Alto del reproductor (en px)
    playerVars: {
    autoplay: 1, // Reproducir automáticamente el vídeo al comenzar
    controls: 1, // Mostrar botones de play/pausa
    showinfo: 0, // Ocultar el título del vídeo
    modestbranding: 1, // Ocultar el logo de YouTube
    loop: 1, // Reproducir el vídeo en bucle
    fs: 1, // Mostrar el botón de pantalla completa
    cc_load_policty: 0, // Ocultar modo de privacidad
    iv_load_policy: 3, // Ocultar las anotaciones del vídeo
    autohide: 0, // Ocultar los controles mientras se reproduce
    playlist: "4ICM50Ksu1E" // Lista de reproducción en uso (poner aquí el identificador del vídeo de YouTube)
},
events: {
    onReady: function(e) {
    e.target.mute();
    }
  }
});
}</script>

*/?>
