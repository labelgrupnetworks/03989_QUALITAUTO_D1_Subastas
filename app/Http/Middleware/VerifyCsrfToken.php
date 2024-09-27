<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "*apilabel/*",
        "*webservice/*",
         "*api/*",
        "api-ajax/*",
        "gateway/*",
        //"/login_post_ajax",
        "*/valoracion-articulos-adv*",
        "apirest/*",
        "admin/*",
        "*/login/subastas*",
        "*/login/subalia*",
        "*/register_subalia",
		"/articleCart/returnpayup2",
		"/articleCart/returnPay",
		"/carlandia/confirmPayment",
		"phpsock*",
		"*response_redsys*",
		"/lleidanet/response_ocr"
    ];
}
