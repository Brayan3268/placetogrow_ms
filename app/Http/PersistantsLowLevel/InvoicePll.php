<?php

namespace App\Http\PersistantsLowLevel;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Invoice;
use App\Models\User;
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

    public static function save_invoice(StoreInvoiceRequest $request)
    {
        //dd($request);
        $invoice = new Invoice();
        $invoice->reference = $request->reference;
        $invoice->amount = $request->amount;
        $invoice->currency = $request->currency;
        $invoice->status = 'not_payed';
        $invoice->site()->associate($request->site_id);
        $invoice->user()->associate($request->user_id);
        $invoice->date_created = date('ymdHis');
        $invoice->date_expiration = $request->date_expiration;
        $invoice->save();

        Cache::flush();
    }

    public static function update_user_with_password(User $user, StoreUserRequest $request)
    {
        $user->update([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'document_type' => $request['document_type'],
            'document' => $request['document'],
            'password' => bcrypt($request['password']),
            'phone' => $request['phone'],
        ]);

        $user->syncRoles([$request['role']]);

        return $user;
    }

    public static function delete_user(User $user)
    {
        $user->delete();
    }

    public static function get_role_names(User $user)
    {
        return $user->getRoleNames();
    }

    public static function get_user_auth()
    {
        $user = User::find(auth()->user()->id);
        $user = UserPll::get_role_names($user);

        return $user;
    }

    public static function forget_cache(string $name_cache)
    {
        Cache::forget($name_cache);
    }

    public static function get_users_enum_field_values(string $field)
    {
        return DB::select("SHOW COLUMNS FROM users WHERE Field = '".$field."'")[0]->Type;
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
