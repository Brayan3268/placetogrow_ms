<?php

namespace App\Http\Controllers;

use App\Http\PersistantsLowLevel\CategoryPll;
use App\Http\PersistantsLowLevel\SitePll;
use App\Models\Site;
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
        $current_options = $datos['current_options'];
        $site_type_options = $datos['site_type_options'];
        $document_types = $datos['document_types'];

        return view('sites.create', compact('categories', 'current_options', 'site_type_options', 'document_types'));
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
            SitePll::forget_cache('sites.index');

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

        return view('sites.show', compact('site'));
    }

    public function edit(string $id): View
    {
        $site = SitePll::get_specific_site($id);

        $datos = $this->get_enums();
        $categories = $datos['categories'];
        $current_options = $datos['current_options'];
        $site_type_options = $datos['site_type_options'];
        $document_types = $datos['document_types'];

        return view('sites.edit', compact('site', 'categories', 'current_options', 'site_type_options', 'document_types'));
    }

    public function update(Request $request, Site $site): RedirectResponse
    {
        $data = [
            'slug' => $request['slug'],
            'name' => $request['name'],
            'document_type' => $request['document_type'],
            'document' => $request['document'],
            'category_id' => $request['category'],
            'expiration_time' => $request['expiration_time'],
            'current_type' => $request['current'],
            'site_type' => $request['site_type'],
        ];

        if ($request->hasFile('image')) {
            if (Storage::exists(str_replace('storage', 'public', $site->image))) {
                Storage::delete(str_replace('storage', 'public', $site->image));
            }

            $image = $request->file('image');
            $image_name = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/site_images/', $image_name);

            $data['image'] = 'storage/site_images/'.$image_name;
        }

        SitePll::update_site($site, $data);

        SitePll::forget_cache('site.'.$site->id);
        SitePll::forget_cache('sites.index');

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

        SitePll::forget_cache('site.'.$site->id);
        SitePll::forget_cache('sites.index');

        return redirect()->route('sites.index')
            ->with('status', 'Site deleted successfully')
            ->with('class', 'bg-green-500');
    }

    public function get_enums(): array
    {
        $categories = CategoryPll::get_cache('categories');
        if (is_null($categories)) {
            $categories = CategoryPll::get_all_categories();

            $enumCurrentValues = SitePll::get_sites_enum_field_values('current_type');
            preg_match('/^enum\((.*)\)$/', $enumCurrentValues, $matches);
            $current_options = explode(',', $matches[1]);
            $current_options = array_map(fn ($value) => trim($value, "'"), $current_options);

            $enumSiteTypeValues = SitePll::get_sites_enum_field_values('site_type');
            preg_match('/^enum\((.*)\)$/', $enumSiteTypeValues, $matches);
            $site_type_options = explode(',', $matches[1]);
            $site_type_options = array_map(fn ($value) => trim($value, "'"), $site_type_options);

            $enumDocumentTypeValues = SitePll::get_sites_enum_field_values('document_type');
            preg_match('/^enum\((.*)\)$/', $enumDocumentTypeValues, $matches);
            $document_types = explode(',', $matches[1]);
            $document_types = array_map(fn ($value) => trim($value, "'"), $document_types);

            SitePll::save_cache('categories', $categories);
            SitePll::save_cache('current_options', $current_options);
            SitePll::save_cache('site_type_options', $site_type_options);
            SitePll::save_cache('document_types', $document_types);
        } else {
            $current_options = SitePll::get_cache('current_options');
            $site_type_options = SitePll::get_cache('site_type_options');
            $document_types = SitePll::get_cache('document_types');
        }

        return [
            'categories' => $categories,
            'current_options' => $current_options,
            'site_type_options' => $site_type_options,
            'document_types' => $document_types,
        ];
    }
}
