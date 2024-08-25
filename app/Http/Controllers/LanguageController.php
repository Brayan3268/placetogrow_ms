<?php

namespace App\Http\Controllers;

use App\Http\Requests\LangRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function setLocale(LangRequest $request)
    {
        $locale = $request->input('locale');

        App::setLocale($locale);

        Session::put('locale', $locale);

        return redirect()->back();
    }
}
