<?php

namespace App\Http\Controllers;

use App\Constants\InvoiceStatus;
use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Http\PersistantsLowLevel\UserPll;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sites = SitePll::get_sites_closed();
        
        $log[] = 'Ingresó a dashboard.index';
        $this->write_file($log);

        return view('dashboards.dashboard', compact('sites'));
    }

    public function show_site(Request $request)
    {
        $invoices_counts = InvoicePll::get_especific_site_invoices_grouped($request->site_id);

        $approved_pending = [];
        $pending_expirated = [];

        foreach ($invoices_counts as $invoice) {
            // Aprobadas y Pendientes
            if (in_array($invoice->status, [InvoiceStatus::PAYED->value, InvoiceStatus::NOT_PAYED->value])) {
                $approved_pending[] = [
                    'status' => $invoice->status,
                    'total' => $invoice->total
                ];
            }
    
            // Pendientes y Expiradas
            if (in_array($invoice->status, [InvoiceStatus::NOT_PAYED->value, InvoiceStatus::EXPIRATED->value])) {
                $pending_expirated[] = [
                    'status' => $invoice->status,
                    'total' => $invoice->total
                ];
            }
        }

        return view('dashboards.graphics', compact('approved_pending', 'pending_expirated'));
    }

    protected function write_file(array $info)
    {
        $current_date_time = Carbon::now('America/Bogota')->format('Y-m-d H:i:s');
        $content = '';

        foreach ($info as $key => $value) {
            $content .= '    '.$value.' en la fecha '.$current_date_time;
        }

        Storage::disk('public_logs')->append('log.txt', $content);
    }
}
