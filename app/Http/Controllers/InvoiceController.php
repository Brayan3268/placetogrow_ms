<?php

namespace App\Http\Controllers;

use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Invoice::class);

        $user = UserPll::get_specific_user(Auth::user()->id);
        $invoices = ($user->hasAnyRole('super_admin', 'admin')) ? InvoicePll::get_all_invoices() : InvoicePll::get_especific_user_invoices($user->id);

        $log[] = 'Ingresó a invoice.index';
        $this->write_file($log);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $this->authorize('create', Invoice::class);

        $datos = $this->get_enums();
        $currency_type = $datos['currency'];
        $users = UserPll::get_users_guest();
        $sites = SitePll::get_sites_closed();

        $log[] = 'Ingresó a invoice.create';
        $this->write_file($log);

        return view('invoices.create', compact('currency_type', 'users', 'sites'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('update', Invoice::class);

        InvoicePll::save_invoice($request);

        $log[] = 'Creó una factura';
        $this->write_file($log);

        return redirect()->route('invoices.index')
            ->with('status', 'Invoice created successfully!')
            ->with('class', 'bg-green-500');
    }

    public function show(string $id)
    {
        $this->authorize('view', Invoice::class);

        $invoice = InvoicePll::get_especific_invoice(intval($id));

        $log[] = 'Consultó la información de una facturá';
        $this->write_file($log);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(string $id)
    {
        $this->authorize('edit', Invoice::class);

        $invoice = InvoicePll::get_especific_invoice(intval($id));

        $datos = $this->get_enums();
        $currency = $datos['currency'];
        $users = UserPll::get_users_guest();
        $sites = SitePll::get_sites_closed();

        $date_expiration = $invoice->date_expiration ? Carbon::parse($invoice->date_expiration)->format('Y-m-d\TH:i') : '';

        $log[] = 'Ingresó a invoice.edit';
        $this->write_file($log);

        return view('invoices.edit', compact('invoice', 'currency', 'users', 'sites', 'date_expiration'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', Invoice::class);

        $invoice = InvoicePll::update_all_invoice($invoice, $request);

        $log[] = 'Editó la información de una factura';
        $this->write_file($log);

        return redirect()->route('invoices.index')
            ->with('status', 'User updated successfully')
            ->with('class', 'bg-green-500');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', Invoice::class);

        InvoicePll::delete_invoice($invoice);

        $log[] = 'Eliminó una factura';
        $this->write_file($log);

        return redirect()->route('invoices.index')
            ->with('status', 'invoice deleted successfully')
            ->with('class', 'bg-green-500');
    }

    public function get_enums(): array
    {
        $enumCurrencyValues = InvoicePll::get_invoices_enum_field_values('currency');
        preg_match('/^enum\((.*)\)$/', $enumCurrencyValues, $matches);
        $currency_options = explode(',', $matches[1]);
        $currency_options = array_map(fn ($value) => trim($value, "'"), $currency_options);

        InvoicePll::save_cache('currency', $currency_options);
        $currency_options = InvoicePll::get_cache('currency');

        return ['currency' => $currency_options];
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
