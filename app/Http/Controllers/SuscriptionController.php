<?php

namespace App\Http\Controllers;

use App\Constants\Permissions;
use App\Http\PersistantsLowLevel\SitePll;
use App\Http\PersistantsLowLevel\SuscriptionPll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use App\Http\Requests\StoreSuscriptionRequest;
use App\Models\Suscription;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SuscriptionController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Suscription::class);

        $user_suscriptions = [];

        $suscriptions = SuscriptionPll::get_all_suscription();

        $user = UserPll::get_specific_user(Auth::user()->id);
        if ($user->hasPermissionTo(Permissions::SUSCRIPTIONS_SPECIFIC_USER)) {
            $user_suscriptions = UserSuscriptionPll::get_specific_user_suscriptions(Auth::user()->id);

            foreach ($user_suscriptions as $plan) {
                foreach ($suscriptions as $key => $value) {
                    if ($plan->id == $value->id) {
                        unset($suscriptions[$key]);
                    }
                }
            }
        } else {
            $user_suscriptions = UserSuscriptionPll::get_all_user_suscriptions();
        }

        return view('suscriptions.index', compact('suscriptions', 'user_suscriptions'));
    }

    public function create()
    {
        $this->authorize('create', Suscription::class);

        $datos = $this->get_enums();
        $currency_type = $datos['currency_type'];
        $frecuency_collection = $datos['frecuency_collection'];
        $sites = SitePll::get_sites_suscription();

        return view('suscriptions.create', compact('currency_type', 'frecuency_collection', 'sites'));
    }

    public function store(StoreSuscriptionRequest $request)
    {
        $this->authorize('update', Suscription::class);

        SuscriptionPll::save_suscription($request);

        return redirect()->route('suscriptions.index')
            ->with('status', 'Suscription plan created successfully!')
            ->with('class', 'bg-green-500');
    }

    public function show(Suscription $suscription)
    {
        $this->authorize('view', Suscription::class);

        $suscription = SuscriptionPll::get_especific_suscription($suscription->id);

        return view('suscriptions.show', compact('suscription'));
    }

    public function edit(Suscription $suscription)
    {
        $this->authorize('edit', Suscription::class);

        $suscription = SuscriptionPll::get_especific_suscription(intval($suscription->id));

        $datos = $this->get_enums();
        $currency_type = $datos['currency_type'];
        $frecuency_collection = $datos['frecuency_collection'];
        $sites = SitePll::get_sites_suscription();

        return view('suscriptions.edit', compact('suscription', 'currency_type', 'frecuency_collection', 'sites'));
    }

    public function update(Request $request, Suscription $suscription)
    {
        $this->authorize('update', Suscription::class);

        SuscriptionPll::update_suscription($request, $suscription);

        return redirect()->route('suscriptions.index')
            ->with('status', 'Suscription updated successfully')
            ->with('class', 'bg-green-500');
    }

    public function destroy(Suscription $suscription)
    {
        $this->authorize('delete', Suscription::class);

        SuscriptionPll::delete_suscription($suscription);

        return redirect()->route('suscriptions.index')
            ->with('status', 'Suscription deleted successfully')
            ->with('class', 'bg-green-500');
    }

    public function get_enums(): array
    {
        $enumCurrencyValues = SuscriptionPll::get_suscription_enum_field_values('currency_type');
        preg_match('/^enum\((.*)\)$/', $enumCurrencyValues, $matches);
        $currency_options = explode(',', $matches[1]);
        $currency_options = array_map(fn ($value) => trim($value, "'"), $currency_options);

        $enumFrecuencyCollectionValues = SuscriptionPll::get_suscription_enum_field_values('frecuency_collection');
        preg_match('/^enum\((.*)\)$/', $enumFrecuencyCollectionValues, $matches);
        $frecuency_collection_options = explode(',', $matches[1]);
        $frecuency_collection_options = array_map(fn ($value) => trim($value, "'"), $frecuency_collection_options);

        SuscriptionPll::save_cache('currency_type', $currency_options);
        SuscriptionPll::save_cache('frecuency_collection', $frecuency_collection_options);
        $currency_options = SuscriptionPll::get_cache('currency_type');
        $frecuency_collection_options = SuscriptionPll::get_cache('frecuency_collection');

        return
        [
            'currency_type' => $currency_options,
            'frecuency_collection' => $frecuency_collection_options,
        ];
    }
}
