<?php

use Illuminate\Support\Facades\Config;

return \App\libs\TradLib::getTranslations(Config::get('app.locale'));
