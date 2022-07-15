<p align="center"><img src="https://www.labelgrup.com/wp-content/uploads/2016/12/Labelgrup-1.png" alt="Labelgrup" title="Gestión y mantenimiento  de sistemas informáticos" width=500></p>

# Iniciar proyecto

- [Requerimientos](#requirements)
- [Extensiones necesarias](#extensions)
- [Descargar e iniciar proyecto](#download)
- [Añadir proyectos individuales a git](#addgit)
- [Añadir remoto a todos los proyectos](#allprojects)
- [Ejemplos de comandos git](#examples)

<a name="requirements"></a>
## Requerimientos
- [PHP v.8.1.7](https://windows.php.net/download/): [windows](https://windows.php.net/downloads/releases/archives/php-8.1.7-nts-Win32-vs16-x64.zip) | [linux](https://windows.php.net/downloads/releases/archives/php-8.1.7-src.zip)
- [Laragon](https://laragon.org/) o [Wamp](https://www.wampserver.com/en/)
- [Extensión OCI 8](https://pecl.php.net/package/oci8): [windows](https://windows.php.net/downloads/pecl/releases/oci8/3.2.1/php_oci8-3.2.1-8.1-ts-vs16-x64.zip) | [linux](https://pecl.php.net/get/oci8-3.2.1.tgz)
- [Git](https://git-scm.com/)

<a name="extensions"></a>
## Extensiones necesarias
- curl
- fileinfo
- gd
- mbstring
- exif
- oci8_12c

<a name="download"></a>
## Descargar e iniciar proyecto
1. `git clone https://github.com/labelgrupnetworks/D1_Subastas_Web.git subastas`
##### Desde dentro del directorio recien creado
3. Añadir .env variables
2. `composer update`
3. `npm install`
4. Añadir .htaccess



<a name="addgit"></a>
## Añadir proyectos individuales a git
 
- `git remote add origin https://github.com/labelgrupnetworks/D1_Subastas_Web.git`
- `git remote add demo https://github.com/labelgrupnetworks/00147_LabelGrup_Demo_Subastas.git`
- `git remote add almoneda https://github.com/labelgrupnetworks/03352_ALMONEDA_D1_Subastas.git`
- `git remote add bogota https://github.com/labelgrupnetworks/03226_BOGOTA_D1_Subastas.git`
- `git remote add silicua https://github.com/labelgrupnetworks/02244_SILICUA_D1_Subastas.git`
- `git remote add soporteconcursal https://github.com/labelgrupnetworks/02946_SOPORTECONCURSAL_D1_Subastas.git`

<a name="allprojects"></a>
## Añadir remoto a todos los proyectos
- `git remote add allprojects https://github.com/labelgrupnetworks/D1_Subastas_Web.git` 
- `git remote set-url --add --push allprojects https://github.com/labelgrupnetworks/03352_ALMONEDA_D1_Subastas.git`
- `git remote set-url --add --push allprojects https://github.com/labelgrupnetworks/03226_BOGOTA_D1_Subastas.git`
- `git remote set-url --add --push allprojects https://github.com/labelgrupnetworks/00147_LabelGrup_Demo_Subastas.git`
- `git remote set-url --add --push allprojects https://github.com/labelgrupnetworks/02244_SILICUA_D1_Subastas.git`
- `git remote set-url --add --push allprojects https://github.com/labelgrupnetworks/02946_SOPORTECONCURSAL_D1_Subastas.git`
- `git remote set-url --add --push allprojects https://github.com/labelgrupnetworks/D1_Subastas_Web.git`

<a name="examples"></a>
## Ejemplos de comandos

Descargar código maestro
```git
git pull origin main
```

Subir codigo a cliente.
```git
git push demo main
```

Subir codigo de rama main local a rama develop remota
```git
git push demo main:develop
```

Subir rama develop a todos los cliente
```git
git push allprojects develop
```
