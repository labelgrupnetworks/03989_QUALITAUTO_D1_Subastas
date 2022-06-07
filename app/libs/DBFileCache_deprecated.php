<?php

namespace App\libs;

use Config;
use File;
use Illuminate\Support\Str;


#JA Classe per la caché de querys.

class DBFileCache
{
    # Query.
    private $sql = '';

    # Resultado de la query.
    private $content = '';

    # Zona de la web donde se visualiza. Sirve para separar la caché en carpetas.
    private $zone = '';

    public function __construct($zone, $sql) 
    {
        $this->setZone($zone);
        $this->setSql($sql);
    }


    # Getters
    public function getContent()
    {
        $file = $this->getFilePath();

        # Si no existe el fichero de caché o si está la caché deshabilitada no devuelve datos.
        if (!File::exists($file) || !intval(Config::get('app.enable_cache')))
            return false;

        return unserialize(file_get_contents($file));
    }

    private function getFilePath()
    {
        $path = storage_path().Config::get('app.cache_path') . '/' . $this->zone;

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0775, true);
        }

        return $path.'/'.$this->sql.'.txt';
    }



    # Setters
    public function save()
    {
        return File::put($this->getFilePath(), $this->content);
    }

    public function setContent(Array $content)
    {
        $this->content = serialize($content);
    }

    public function setSql($sql)
    {
        $this->sql = sha1($sql);
    }

    public function setZone($zone)
    {
        $this->zone = $zone;
    }

}