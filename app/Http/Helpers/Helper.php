<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of func_helper
 *
 * @author rsanchez
 */
//Redeclaro la funcion head del helper  \vendor\laravel\framework\src\Illuminate\Support\helpers.php
//Ya no se puede sobreescribir y laravel ya la incorpora
if (!function_exists('head')) {
	function head($array)
	{
		if (is_array($array)) {
			return reset($array);
		}
		else if ($array instanceof \Illuminate\Support\Collection) {
			return $array->first();
		}
		else if ($array instanceof \Illuminate\Database\Eloquent\Collection) {
			return $array->first();
		}
		else if ($array instanceof \Illuminate\Support\LazyCollection) {
			return $array->first();
		}
		else if ($array instanceof \Illuminate\Support\LazyCollection) {
			return $array->first();
		}
		else if (is_object($array)) {
			return head((array)$array);
		}
		else {
			return null;
		}
	}
}

if (!function_exists('isMultilanguage')) {
    function isMultilanguage()
    {
        return !empty(array_diff_key(config('app.locales'), [config('app.locale') => 1]));
    }
}


if (!function_exists('array_key_first')) {
    function array_key_first(array $arr)
    {
        foreach ($arr as $key => $unused) {
            return $key;
        }
        return null;
    }
}

if (!function_exists('str_slug')) {
    function str_slug($string)
    {
        return Str::slug($string);
    }
}

if(!function_exists('array_except')){
    function array_except($array, $keys)
    {
        return Arr::except($array, $keys);
    }
}

if (!function_exists('trim_explode')) {
	function trim_explode($delimiter, $string)
	{
		return array_map('trim', explode($delimiter, $string));
	}
}

if(!function_exists('public_default_path')){
	function public_default_path($path = "")
	{
		$version = Config::get('app.default_theme');
		return $path ? "default/$version/$path" : "default/$version";
	}
}

if(!function_exists('is_collection')){
	function is_collection($var)
	{
		return $var instanceof \Illuminate\Support\Collection || $var instanceof \Illuminate\Database\Eloquent\Collection;
	}
}

