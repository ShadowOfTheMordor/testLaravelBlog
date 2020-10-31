<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App;

class LocalizationController extends Controller
{
    public function index($locale)
    {
        App::setLocale($locale);
        Session::put("app.locale", $locale);
        return redirect()->back();
    }
}
