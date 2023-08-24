<?php

use Illuminate\Support\Facades\DB;

//mostrar las consultas sql y el lugar donde se ejecutan
DB::listen(function($query){
    echo "<code>".$query->sql."</code>";
    echo "<br>";
});
