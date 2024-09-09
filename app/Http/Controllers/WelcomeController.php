<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        Cache::flush();

        return view('welcome');
    }
}
