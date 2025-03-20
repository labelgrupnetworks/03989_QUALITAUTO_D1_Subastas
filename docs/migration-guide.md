## Modificaciones necesarias en vistas para migrar de la versión 5.3 a la 9.0 de Laravel

1. Las referencias a \App\Models\Banners deben ser cambiadas a \App\Services\Content\BannerService
   Esto afecta a:

-   Gutinvest
-   Papaya

2. Modificar referencias a Tools::slider() por (\App\Services\Content\BannerService)->getOldBannerWithSliderBlade($key, $html);

-   Bogota
-   Gutinvest

3. Del archivo blocs.blade.php, cambiar la referencia de BlocSector a BlocSectorService y sus métodos correspondientes.

-   Gutinvest
