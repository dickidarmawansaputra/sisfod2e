<?php

namespace App\Http\Controllers;

use Dotenv\Dotenv;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
	public function index()
	{
		return view('config.index');
	}

    public function config(Request $request)
    {
    	return getenv('APP_ENV', 0);
    	$dotenv = Dotenv::create(__DIR__ . '/../', '.env')->load();
    	dd($dotenv);
    }
}
