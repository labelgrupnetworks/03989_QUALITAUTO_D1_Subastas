<?php

use App\libs\TradLib;
use Illuminate\Support\Facades\Config;

return TradLib::getTranslations(Config::get('app.locale'));
