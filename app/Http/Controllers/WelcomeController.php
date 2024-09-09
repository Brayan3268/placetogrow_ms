<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        Cache::flush();

        return view('welcome');
    }

    protected function write_file(array $info){}
}
