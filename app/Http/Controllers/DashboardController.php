<?php

namespace App\Http\Controllers;

use App\Constants\InvoiceStatus;
use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', User::class);

        $sites = SitePll::get_sites_closed();

        $log[] = 'Ingresó a dashboard.index';
        $this->write_file($log);

        return view('dashboards.dashboard', compact('sites'));
    }

    public function show_site(Request $request)
    {
        $this->authorize('view_graphics', User::class);

        $invoices_counts = InvoicePll::get_especific_site_invoices_grouped($request->site_id);
        $site = SitePll::get_specific_site($request->site_id);

        $payed_not_payed = [];
        $not_payed_expirated = [];
        $payed_expirated = [];

        foreach ($invoices_counts as $invoice) {
            if (in_array($invoice->status, [InvoiceStatus::PAYED->value, InvoiceStatus::NOT_PAYED->value])) {
                $payed_not_payed[] = [
                    'status' => $invoice->status,
                    'total' => $invoice->total,
                ];
            }

            if (in_array($invoice->status, [InvoiceStatus::NOT_PAYED->value, InvoiceStatus::EXPIRATED->value])) {
                $not_payed_expirated[] = [
                    'status' => $invoice->status,
                    'total' => $invoice->total,
                ];
            }

            if (in_array($invoice->status, [InvoiceStatus::PAYED->value, InvoiceStatus::EXPIRATED->value])) {
                $payed_expirated[] = [
                    'status' => $invoice->status,
                    'total' => $invoice->total,
                ];
            }
        }

        return view('dashboards.graphics', compact('payed_not_payed', 'not_payed_expirated', 'payed_expirated', 'site'));
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
