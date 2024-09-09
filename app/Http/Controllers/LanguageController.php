<?php

namespace App\Http\Controllers;

use App\Http\Requests\LangRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class LanguageController extends Controller
{
    public function setLocale(LangRequest $request)
    {
        $locale = $request->input('locale');

        App::setLocale($locale);

        Session::put('locale', $locale);

        $log[] = 'Cambió el idioma a ' . $locale;
        $this->write_file($log);

        return redirect()->back();
    }

    protected function write_file(array $info)
    {
        $current_date_time = Carbon::now('America/Bogota')->format('Y-m-d H:i:s');
        $content = '';

        foreach ($info as $key => $value){
            $content .= '    ' . $value . ' en la fecha ' . $current_date_time;
        }

        Storage::disk('public_logs')->append('log.txt', $content);
    }
}
