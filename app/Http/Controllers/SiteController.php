<?php

namespace App\Http\Controllers;

use App\Constants\FieldsOptionalies;
use App\Constants\Permissions;
use App\Http\PersistantsLowLevel\CategoryPll;
use App\Http\PersistantsLowLevel\FieldpaysitePll;
use App\Http\PersistantsLowLevel\InvoicePll;
use App\Http\PersistantsLowLevel\PaymentPll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Http\PersistantsLowLevel\SuscriptionPll;
use App\Http\PersistantsLowLevel\UserPll;
use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use App\Http\Requests\StoreFieldRequest;
use App\Imports\InvoicesImport;
use App\Models\Site;
use App\Notifications\LoseSessionNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SiteController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Site::class);

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

        $log[] = 'Ingresó a sites.index';
        $this->write_file($log);

        return view('sites.index', compact(['open_sites', 'close_sites', 'suscription_sites']));
    }

    public function create(): View
    {
        $this->authorize('create', Site::class);

        $datos = $this->get_enums();
        $categories = $datos['categories'];
        $currency_options = $datos['currency_options'];
        $site_type_options = $datos['site_type_options'];

        $log[] = 'Ingresó a sites.create';
        $this->write_file($log);

        return view('sites.create', compact('categories', 'currency_options', 'site_type_options'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('update', Site::class);

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

        $log[] = 'Creó un sitio';
        $this->write_file($log);

        return redirect()->route('sites.index')
            ->with('status', 'Site created unsuccessfully!')
            ->with('class', 'bg-red-500');
    }

    public function show(string $id): View
    {
        $this->authorize('view', Site::class);

        $log[] = 'Ingresó a sites.show';

        $pay_exist = false;
        $pay = '';
        if (PaymentPll::validate_is_pending_rejected_pays(intval($id))) {
            $pay = PaymentPll::get_pays_not_approved_payments(intval($id));
            $pay_exist = true;
            $log[] = 'Consultó la información de un pago pendiente';
        }

        $invoices = collect();
        $site = SitePll::get_specific_site($id);
        $user = UserPll::get_specific_user(Auth::user()->id);

        if ($site->site_type == 'CLOSE') {
            $invoices = ($user->hasPermissionTo(Permissions::INVOICES_CREATE) && $user->hasPermissionTo(Permissions::SITES_MANAGE)) ?
                InvoicePll::get_especific_site_invoices($site->id) :
                InvoicePll::get_especific_site_user_invoices($site->id);
            $log[] = 'Consultó las facturas pendientes del sitio '.$site->id;
        }

        $suscription_plans = collect();
        $user_plans_get_suscribe = [];
        if ($site->site_type == 'SUSCRIPTION') {
            Cache::flush();

            $suscription_plans = SuscriptionPll::get_site_suscription(intval($id));

            if ($user->hasPermissionTo(Permissions::USER_GET_SUSCRIPTION)) {
                $user_plans = UserSuscriptionPll::get_specific_user_suscriptions($user->id);
                foreach ($user_plans as $key => $value) {
                    foreach ($suscription_plans as $key_all => $value_all) {
                        if ($value->suscription_id == $value_all->id) {
                            array_push($user_plans_get_suscribe, $value);
                            unset($suscription_plans[$key_all]);
                        }
                    }
                }
            }
            $log[] = 'Consultó las suscripciones para el sitio '.$site->id;
        }

        $this->write_file($log);

        try {
            return view('sites.show', compact('site', 'invoices', 'pay_exist', 'pay', 'suscription_plans', 'user_plans_get_suscribe'));
        } catch (Exception $e) {
            return view('sites.show', compact('site', 'invoices', 'pay_exist', 'pay', 'suscription_plans', 'user_plans_get_suscribe'));
        }
    }

    public function edit(string $id): View
    {
        $this->authorize('update', Site::class);

        $site = SitePll::get_specific_site($id);

        $datos = $this->get_enums();
        $categories = $datos['categories'];
        $currency_options = $datos['currency_options'];
        $site_type_options = $datos['site_type_options'];

        $log[] = 'Ingresó a sites.edit';
        $this->write_file($log);

        return view('sites.edit', compact('site', 'categories', 'currency_options', 'site_type_options'));
    }

    public function update(Request $request, Site $site): RedirectResponse
    {
        $this->authorize('update', Site::class);

        SitePll::update_site($site, $request);

        $log[] = 'Editó la información de un sitio';
        $this->write_file($log);

        return redirect()->route('sites.index')
            ->with('status', 'Site updated successfully')
            ->with('class', 'bg-green-500');
    }

    public function destroy(Site $site): RedirectResponse
    {
        $this->authorize('delete', Site::class);

        $site->image = str_replace('storage', 'public', $site->image);

        if (Storage::exists(str_replace('storage', 'public', $site->image))) {
            Storage::delete(str_replace('storage', 'public', $site->image));
        }

        SitePll::delete_site($site);

        $log[] = 'Eliminó un sitio';
        $this->write_file($log);

        return redirect()->route('sites.index')
            ->with('status', 'Site deleted successfully')
            ->with('class', 'bg-green-500');
    }

    public function maganage_sites_config_pay(Site $site): View
    {
        $this->authorize('manage_sites_config_pay', Site::class);

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

        $log[] = 'Entró a la configuración de los campos requeridos del sitio '.$site->id;
        $this->write_file($log);

        return view('sites.fieldspaysite', compact('filtered_constants_opt', 'sites_fields', 'site_id'));
    }

    public function add_field(StoreFieldRequest $request)
    {
        $this->authorize('manage_sites_config_pay', Site::class);

        FieldpaysitePll::add_field_site($request);

        $log[] = 'Agregó un campo para el formulario del sitio';
        $this->write_file($log);

        return redirect()->route('sites.manage_config', ['site' => $request->site_id])
            ->with('success', 'Redirection successful!');
    }

    public function field_destroy(int $field_pay_site_id): RedirectResponse
    {
        $this->authorize('manage_sites_config_pay', Site::class);

        $site_id = FieldpaysitePll::delete_field_pay($field_pay_site_id);

        $log[] = 'Eliminó un campo para el formulario del sitio';
        $this->write_file($log);

        return redirect()->route('sites.manage_config', ['site' => $site_id])
            ->with('success', 'Redirection successful!');
    }

    public function form_site(Site $site): View
    {
        $this->authorize('form_sites_pay', Site::class);

        $sites_fields = FieldpaysitePll::get_fields_site($site->id);
        foreach ($sites_fields as $site_field) {
            $site_field->value_invoice = ' ';
        }

        $invoice_id = 0;

        $log[] = 'Ingresó al formulario del sitio para crear una sesion';
        $this->write_file($log);

        return view('sites.form_site', compact('site', 'sites_fields', 'invoice_id'));
    }

    public function form_site_invoices(int $invoice_id): View
    {
        $this->authorize('form_sites_pay', Site::class);

        $invoice = InvoicePll::get_especific_invoice($invoice_id);
        $invoice_id = $invoice->id;

        $sites_fields = FieldpaysitePll::get_fields_site($invoice->site_id);

        foreach ($sites_fields as $site_field) {
            $site_field->value_invoice = ' ';

            if ($site_field->name == 'total') {
                $site_field->value_invoice = $invoice->amount;
            }

            if ($site_field->name == 'currency') {
                $site_field->value_invoice = $invoice->currency;
            }
        }

        $site = SitePll::get_specific_site($invoice->site_id);

        $log[] = 'Ingresó al formulario del sitio para crear una sesion pagando una factura';
        $this->write_file($log);

        return view('sites.form_site', compact('site', 'sites_fields', 'invoice_id'));
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

    public function finish_session(string $payment_id): RedirectResponse
    {
        $this->authorize('form_sites_pay', Site::class);

        $payment = PaymentPll::get_especific_pay(intval($payment_id));

        $log[] = 'Ingresó una sesion de pago creada previamente';
        $this->write_file($log);

        return redirect()->away($payment->url_session);
    }

    public function lose_session(int $payment_id): View
    {
        $this->authorize('form_sites_pay', Site::class);

        $payment = PaymentPll::lose_session($payment_id);

        Notification::send([Auth::user()], new LoseSessionNotification(
            $payment,
        ));
        $log[] = 'Envió un correo con la información de la eliminación de la session';

        $log[] = 'Eliminó una sesion de pago';
        $this->write_file($log);

        return $this->show($payment->site->id);
    }

    public function import_invoices(Request $request, string $site_id): View
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $import = new InvoicesImport($site_id);

        Excel::import($import, $request->file('file'));

        $log[] = 'Importó facturas al sitio '.$site_id;
        $this->write_file($log);

        return $this->show($site_id);
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
