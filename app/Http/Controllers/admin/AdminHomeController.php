<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use View;
use Session;
use Redirect;

class AdminHomeController extends Controller
{

    public function index()
    {	
        
        return View::make('admin::pages.home');
      // return  Redirect::to('admin/auc-index');
       
    }

}
