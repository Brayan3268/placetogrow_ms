<?php

namespace App\Http\PersistantsLowLevel;

use App\Constants\InvoiceStatus;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InvoicePll extends PersistantLowLevel
{
    public static function get_all_invoices()
    {
        $invoices = Cache::get('invoices.index');
        if (is_null($invoices)) {
            $invoices = Invoice::with('user', 'site')
                ->get();

            Cache::put('invoices.index', $invoices);
        }

        return $invoices;
    }

    public static function get_especific_invoice(int $id)
    {
        return Invoice::find($id);
    }

    public static function get_especific_invoice_with_pay_id(int $id)
    {
        $invoice = Invoice::where('payment_id', $id)->first();

        return ($invoice) ? $invoice : '';
    }

    public static function get_especific_user_invoices(int $user_id)
    {
        $invoices = Cache::get('invoices.user'.$user_id);
        if (is_null($invoices)) {
            $invoices = Invoice::with('site')->where('user_id', $user_id)->get();

            Cache::put('invoices.user'.$user_id, $invoices);
        }

        return $invoices;
    }

    public static function get_invoices_enum_field_values(string $field)
    {
        return DB::select("SHOW COLUMNS FROM invoices WHERE Field = '".$field."'")[0]->Type;
    }

    public static function get_especific_site_invoices(int $site_id)
    {
        $invoices = Cache::get('invoices.site'.$site_id);
        if (is_null($invoices)) {
            $invoices = Invoice::with('user')
                ->where('site_id', $site_id)
                ->where('status', InvoiceStatus::NOT_PAYED->value)
                ->get();

            Cache::put('invoices.site'.$site_id, $invoices);
        }

        return $invoices;
    }

    public static function get_especific_site_user_invoices(int $site_id)
    {
        $user_id = Auth::user()->id;

        $invoices = Cache::get('invoices.site_user'.$site_id.'_'.$user_id);
        if (is_null($invoices)) {
            $invoices = Invoice::with('user', 'site')
                ->where('site_id', $site_id)
                ->where('user_id', $user_id)
                ->where('status', InvoiceStatus::NOT_PAYED->value)
                ->get();

            Cache::put('invoices.site_user'.$site_id.'_'.$user_id, $invoices);
        }

        return $invoices;
    }

    public static function update_invoice(int $invoice_id, string $status, int $payment_id)
    {
        $invoice = InvoicePll::get_especific_invoice($invoice_id);
        $invoice->update([
            'reference' => $invoice->reference,
            'amount' => $invoice->amount,
            'currency' => $invoice->currency,
            'status' => $status,
            'site_id' => $invoice->site_id,
            'user_id' => $invoice->user_id,
            'payment_id' => $payment_id,
            'date_created' => $invoice->date_created,
            'date_expiration' => $invoice->date_expiration,
        ]);

        Cache::flush();

        return $invoice;
    }

    public static function update_all_invoice(Invoice $invoice, UpdateInvoiceRequest $request)
    {
        $invoice->update([
            'reference' => $request->reference,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => $invoice->status,
            'site_id' => $request->site_id,
            'user_id' => $request->user_id,
            'payment_id' => $invoice->payment_id,
            'date_created' => $invoice->date_created,
            'date_expiration' => $request->date_expiration,
        ]);

        Cache::flush();

        return $invoice;
    }

    public static function save_invoice(StoreInvoiceRequest $request)
    {
        $invoice = new Invoice;
        $invoice->reference = $request->reference;
        $invoice->amount = $request->amount;
        $invoice->currency = $request->currency;
        $invoice->status = 'not_payed';
        $invoice->site()->associate($request->site_id);
        $invoice->user()->associate($request->user_id);
        $invoice->date_created = date('ymdHis');
        $invoice->date_expiration = $request->date_expiration;
        $invoice->payment_id = $request->payment_id;
        $invoice->save();

        Cache::flush();
    }

    public static function save_invoices_imported(array $invoices, int $site_id)
    {
        foreach ($invoices as $invoice_file) {
            $user = User::where('document', $invoice_file['user_id'])->first();
            //dd($user->id);
            $invoice = new Invoice;
            $invoice->reference = $invoice_file['reference'];
            $invoice->amount = $invoice_file['amount'];
            $invoice->currency = $invoice_file['currency'];
            $invoice->status = 'not_payed';
            $invoice->site()->associate($site_id);
            $invoice->user()->associate($user->id);
            $invoice->date_created = $invoice_file['date_created'];
            $invoice->date_expiration = $invoice_file['date_expiration'];

            $invoice->save();
        }

        Cache::flush();
    }

    public static function delete_invoice(Invoice $invoice)
    {
        $invoice->delete();

        Cache::flush();
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }

    public static function save_cache(string $name, $data)
    {
        Cache::put($name, $data);
    }

    public static function get_cache(string $name)
    {
        return Cache::get($name);
    }
}
