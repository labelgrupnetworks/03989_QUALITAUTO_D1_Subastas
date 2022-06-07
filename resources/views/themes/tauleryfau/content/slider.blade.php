
<?php             

$slidder_obj = new \App\Models\Banners;
$slidders = $slidder_obj->getBannerByKeyname($data['key'],50);
$dates= array();

?>
@foreach ($slidders as $slidder) 
    
    @if ($slidder->type == 'H')
       
        
        <?php
            $html_print = str_replace("{html}",$slidder->content, $data['html']);
            echo $html_print; 
        ?>

    @elseif ($slidder->type == 'I')
         <?php
            if(empty($slidder->url_link)){
                 $data['html'] = str_replace("{hidden}",'style="display:none"', $data['html']);
            }

            $html_print = str_replace("{url}",$slidder->url_link, $data['html']);
            
            $html_print = str_replace("{img}",$slidder->url_resource, $html_print);
            
            if($slidder->new_window){
                  $html_print = str_replace("{target}",'target="_blank"' , $html_print);
            }else{
                $html_print = str_replace("{target}",'' , $html_print);
            }
            
            $html_print = str_replace("{html}",$slidder->content, $html_print);
            echo $html_print; 
        ?>

    @elseif ($slidder->type == 'C')
    <?php
        $html = $slidder;
        $html->img = $slidder->url_resource;
        $html->url = $slidder->url_link;
        $html->date = $slidder->content;
        array_push($dates, $html);
        $jsDate = json_encode($dates);
        echo "var dates = ". $jsDate . ";\n";

?>
    
    @endif

@endforeach
