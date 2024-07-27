<?php

namespace App\Http\Controllers;

use App\Constants\FieldsOptionalies;
use App\Http\PersistantsLowLevel\CategoryPll;
use App\Http\PersistantsLowLevel\FieldpaysitePll;
use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Models\Site;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        $sites = SitePll::get_all_sites();

        $classifiedSites = [
            'OPEN' => [],
            'CLOSE' => [],
            'SUSCRIPTION' => [],
        ];

        foreach ($sites as $site) {
            $classifiedSites[$site->site_type][] = $site;
        }

        $open_sites = $classifiedSites['OPEN'];
        $close_sites = $classifiedSites['CLOSE'];
        $suscription_sites = $classifiedSites['SUSCRIPTION'];

        return view('sites.index', compact(['open_sites', 'close_sites', 'suscription_sites']));
    }

    public function create(): View
    {
        $datos = $this->get_enums();
        $categories = $datos['categories'];
        $currency_options = $datos['currency_options'];
        $site_type_options = $datos['site_type_options'];

        return view('sites.create', compact('categories', 'currency_options', 'site_type_options'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $image_name = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/site_images/', $image_name);

            SitePll::save_site($request, $image_name);

            return redirect()->route('sites.index')
                ->with('status', 'Site created successfully!')
                ->with('class', 'bg-green-500');
        }

        return redirect()->route('sites.index')
            ->with('status', 'Site created unsuccessfully!')
            ->with('class', 'bg-red-500');
    }

    public function show(string $id): View
    {
        $site = SitePll::get_specific_site($id);

        if ($site->site_type == 'CLOSE') {
            $invoices = ($this->validate_role()) ? InvoicePll::get_especific_site_invoices($site->id) : InvoicePll::get_especific_site_user_invoices($site->id);
        }

        try {
            return view('sites.show', compact('site', 'invoices'));
        } catch (Exception $e) {
            return view('sites.show', compact('site'));
        }
    }

    public function edit(string $id): View
    {
        $site = SitePll::get_specific_site($id);

        $datos = $this->get_enums();
        $categories = $datos['categories'];
        $currency_options = $datos['currency_options'];
        $site_type_options = $datos['site_type_options'];

        return view('sites.edit', compact('site', 'categories', 'currency_options', 'site_type_options'));
    }

    public function update(Request $request, Site $site): RedirectResponse
    {
        SitePll::update_site($site, $request);

        return redirect()->route('sites.index')
            ->with('status', 'Site updated successfully')
            ->with('class', 'bg-green-500');
    }

    public function destroy(Site $site): RedirectResponse
    {
        $site->image = str_replace('storage', 'public', $site->image);

        if (Storage::exists(str_replace('storage', 'public', $site->image))) {
            Storage::delete(str_replace('storage', 'public', $site->image));
        }

        SitePll::delete_site($site);

        return redirect()->route('sites.index')
            ->with('status', 'Site deleted successfully')
            ->with('class', 'bg-green-500');
    }

    public function maganage_sites_config_pay(Site $site): View
    {
        $site_id = $site->id;

        $constants_opt = FieldsOptionalies::getAll();
        $sites_fields = FieldpaysitePll::get_fields_site($site->id);

        $filtered_constants_opt = [];

        foreach ($constants_opt as $constant => $description) {
            $exists_in_db = false;

            foreach ($sites_fields as $site) {
                if ($site->name === $constant) {
                    $exists_in_db = true;
                    break;
                }
            }

            if (! $exists_in_db) {
                $filtered_constants_opt[$constant] = $description;
            }

        }

        return view('sites.fieldspaysite', compact('filtered_constants_opt', 'sites_fields', 'site_id'));
    }

    public function add_field(Request $request)
    {
        $request->validate([
            'name_field' => 'required|string',
            'name_field_useer_see' => 'required|string',
            'field_type' => 'required|string',
            'is_optional' => 'required|boolean',
            'values' => 'nullable|string',
            'site_id' => 'required|integer',
        ]);

        FieldpaysitePll::add_field_site($request);

        return redirect()->route('sites.manage_config', ['site' => $request->site_id])
            ->with('success', 'Redirection successful!');
    }

    public function field_destroy(int $field_pay_site_id): RedirectResponse
    {
        $site_id = FieldpaysitePll::delete_field_pay($field_pay_site_id);

        return redirect()->route('sites.manage_config', ['site' => $site_id])
            ->with('success', 'Redirection successful!');
    }

    public function form_site(Site $site): View
    {
        $sites_fields = FieldpaysitePll::get_fields_site($site->id);

        return view('sites.form_site', compact('site', 'sites_fields'));
    }

    public function get_enums(): array
    {
        $categories = CategoryPll::get_cache('categories');
        if (is_null($categories)) {
            $categories = CategoryPll::get_all_categories();

            $enumCurrencyValues = SitePll::get_sites_enum_field_values('currency_type');
            preg_match('/^enum\((.*)\)$/', $enumCurrencyValues, $matches);
            $currency_options = explode(',', $matches[1]);
            $currency_options = array_map(fn ($value) => trim($value, "'"), $currency_options);

            $enumSiteTypeValues = SitePll::get_sites_enum_field_values('site_type');
            preg_match('/^enum\((.*)\)$/', $enumSiteTypeValues, $matches);
            $site_type_options = explode(',', $matches[1]);
            $site_type_options = array_map(fn ($value) => trim($value, "'"), $site_type_options);

            SitePll::save_cache('categories', $categories);
            SitePll::save_cache('currency_options', $currency_options);
            SitePll::save_cache('site_type_options', $site_type_options);
        } else {
            $currency_options = SitePll::get_cache('currency_options');
            $site_type_options = SitePll::get_cache('site_type_options');
        }

        return [
            'categories' => $categories,
            'currency_options' => $currency_options,
            'site_type_options' => $site_type_options,
        ];
    }

    private function validate_role(): bool
    {
        $role_name = UserPll::get_user_auth();

        return ($role_name[0] === 'super_admin' || $role_name[0] === 'admin') ? true : false;
    }
}
