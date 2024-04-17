<ul class="">
    <li class="btn-color">
        <a title="Compartir por Facebook" href="http://www.facebook.com/sharer.php?u={{ $url }}">
            <i class="fa fa-facebook"></i>
        </a>
    </li>
    <li class="btn-color">
        <a title="Compartir por x" href="http://twitter.com/share?url={{ $url }}&text={{ $text }}">
            @include('components.x-icon', ['size' => '14'])
        </a>
    </li>
    <li class="btn-color">
        <a title="Compartir por e-mail"
            href="mailto:?Subject={{ trans("$theme-app.head.title_app") }}&body={{ $url }}">
            <i class="fa fa-envelope"></i>
        </a>
    </li>
</ul>
