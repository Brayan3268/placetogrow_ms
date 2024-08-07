<?php

namespace App\Http\Controllers;

use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = ($this->validate_role()) ? InvoicePll::get_all_invoices() : InvoicePll::get_especific_user_invoices(Auth::user()->id);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $datos = $this->get_enums();
        $currency_type = $datos['currency'];
        $users = UserPll::get_users_guest();
        $sites = SitePll::get_sites_closed();

        return view('invoices.create', compact('currency_type', 'users', 'sites'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        if ($this->validate_role()) {
            InvoicePll::save_invoice($request);

            return redirect()->route('invoices.index')
                ->with('status', 'Invoice created successfully!')
                ->with('class', 'bg-green-500');
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function show(string $id)
    {
        $invoice = InvoicePll::get_especific_invoice(intval($id));

        return view('invoices.show', compact('invoice'));
    }

    public function edit(string $id)
    {
        if ($this->validate_role()) {
            $invoice = InvoicePll::get_especific_invoice(intval($id));

            $datos = $this->get_enums();
            $currency = $datos['currency'];
            $users = UserPll::get_users_guest();
            $sites = SitePll::get_sites_closed();

            $date_expiration = $invoice->date_expiration ? Carbon::parse($invoice->date_expiration)->format('Y-m-d\TH:i') : '';

            return view('invoices.edit', compact('invoice', 'currency', 'users', 'sites', 'date_expiration'));
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        if ($this->validate_role()) {
            $invoice = InvoicePll::update_all_invoice($invoice, $request);

            return redirect()->route('invoices.index')
                ->with('status', 'User updated successfully')
                ->with('class', 'bg-green-500');
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    public function destroy(Invoice $invoice)
    {
        if ($this->validate_role()) {
            InvoicePll::delete_invoice($invoice);

            return redirect()->route('invoices.index')
                ->with('status', 'invoice deleted successfully')
                ->with('class', 'bg-green-500');
        }

        return redirect()->route('dashboard')
            ->with('status', 'User not authorized for this route')
            ->with('class', 'bg-red-500');
    }

    private function validate_role(): bool
    {
        $role_name = UserPll::get_user_auth();

        return ($role_name[0] === 'super_admin' || $role_name[0] === 'admin') ? true : false;
    }

    public function get_enums(): array
    {
        //$categories = CategoryPll::get_cache('categories');
        //if (is_null($categories)) {
        //$categories = CategoryPll::get_all_categories();

        $enumCurrencyValues = InvoicePll::get_invoices_enum_field_values('currency');
        preg_match('/^enum\((.*)\)$/', $enumCurrencyValues, $matches);
        $currency_options = explode(',', $matches[1]);
        $currency_options = array_map(fn ($value) => trim($value, "'"), $currency_options);

        InvoicePll::save_cache('currency', $currency_options);
        //} else {
        $currency_options = InvoicePll::get_cache('currency');
        //$site_type_options = SitePll::get_cache('site_type_options');
        //}

        return ['currency' => $currency_options];
    }
}
