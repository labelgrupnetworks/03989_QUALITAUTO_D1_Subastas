
<div class="bread-content">
        @if (!empty($bread))

        <ul itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a class="color-letter bread-link" itemtype="https://schema.org/Thing" itemprop="item" title="{{ trans($theme.'-app.subastas.breadcrumb') }}" href="https://{{ \Request::getHttpHost() }}">
                    <span itemprop="name">{{ trans($theme.'-app.subastas.breadcrumb') }}</span>
                </a>
                <meta itemprop="position" content="1" />
            </li>
            @foreach ($bread as $k => $crumb)
                /
                <?php
                if(empty($crumb["url"])){
                    $crumb["url"] =  "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                }
                ?>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a class="color-letter bread-link" itemtype="https://schema.org/Thing" itemprop="item" title="{{$crumb['name']}}" href="{{ $crumb["url"] }}">
                        <span itemprop="name">{!! $crumb["name"] !!}</span>
                    </a>
                    <meta itemprop="position" content="{{$k+2}}" />
                </li>
            @endforeach
        </ul>

        @endif
</div>
