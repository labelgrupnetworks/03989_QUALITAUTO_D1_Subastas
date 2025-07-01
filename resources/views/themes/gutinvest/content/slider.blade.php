
@php
	use App\Services\Content\BannerService;
	$slidders = (new BannerService())->getOldBannerByKeyname($data['key']);
@endphp

@foreach ($slidders as $slidder)

    @if ($slidder->type == 'H')


        <?php
            $html_print = str_replace("{html}",$slidder->content, $data['html']);
            echo $html_print;
        ?>

    @elseif ($slidder->type == 'I')
         <?php

            $html_print = str_replace("{url}",$slidder->url_link, $data['html']);

            $html_print = str_replace("{img}",$slidder->url_resource, $html_print);

            if($slidder->new_window){
                  $html_print = str_replace("{target}",'target="_blank"' , $html_print);
            }

            $html_print = str_replace("{html}",$slidder->content, $html_print);
            echo $html_print;
        ?>

    @endif

@endforeach

