<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function home()
    {
    	return view('home');
    }

    public function about()
    {
    	return view('about');
    }

    public function export()
    {
    	return view('export');
    }

}
