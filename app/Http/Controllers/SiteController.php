<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(): View
    {
        $sites = Cache::get('sites.index');
        if (is_null($sites)) {
            $sites = Site::whereIn('site_type', ['open', 'close', 'suscription'])
                ->with('category:id,name')
                ->select('name', 'category_id', 'site_type', 'id')
                ->get();

            Cache::put('sites.index', $sites);
        }

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

            $imageName = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/site_images/', $imageName);

            $site = new Site();

            $site->slug = $request->slug;
            $site->name = $request->name;
            $site->document_type = $request->document_type;
            $site->document = $request->document;
            $site->category_id = $request->category;
            $site->expiration_time = $request->expiration_time;
            $site->current_type = $request->current;
            $site->site_type = $request->site_type;
            $site->image = 'storage/site_images/'.$imageName;
            $site->save();

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
        $site = Cache::get('site.'.$id);
        if (is_null($site)) {
            $site = Site::find($id);

            Cache::put('site.'.$id, $site, $minutes = 1000);
        }

        return view('sites.show', compact('site'));
    }

    public function edit(string $id): View
    {
        $site = Cache::get('site.'.$id);
        if (is_null($site)) {
            $site = Site::find($id);

            Cache::put('site.'.$id, $site, $minutes = 1000);
        }

        $datos = $this->get_enums();
        $categories = $datos['categories'];
        $current_options = $datos['current_options'];
        $site_type_options = $datos['site_type_options'];
        $document_types = $datos['document_types'];

        return view('sites.edit', compact('site', 'categories', 'current_options', 'site_type_options', 'document_types'));
    }

    public function update(Request $request, Site $site): RedirectResponse
    {
        if ($request->hasFile('image')) {
            if (Storage::exists(str_replace('storage', 'public', $site->image))) {
                Storage::delete(str_replace('storage', 'public', $site->image));
            }

            $image = $request->file('image');
            $imageName = $image->getClientOriginalName().time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/site_images/', $imageName);

            $site->update([
                'slug' => $request['slug'],
                'name' => $request['name'],
                'document_type' => $request['document_type'],
                'document' => $request['document'],
                'category_id' => $request['category'],
                'expiration_time' => $request['expiration_time'],
                'current_type' => $request['current'],
                'site_type' => $request['site_type'],
                'image' => 'storage/site_images/'.$imageName,
            ]);

        } else {
            $site->update([
                'slug' => $request['slug'],
                'name' => $request['name'],
                'document_type' => $request['document_type'],
                'document' => $request['document'],
                'category_id' => $request['category'],
                'expiration_time' => $request['expiration_time'],
                'current_type' => $request['current'],
                'site_type' => $request['site_type'],
            ]);
        }

        Cache::forget('site.'.$site->id);
        Cache::forget('sites.index');

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

        $site->delete();

        Cache::forget('site.'.$site->id);
        Cache::forget('users.index');

        return redirect()->route('sites.index')
            ->with('status', 'Site deleted successfully')
            ->with('class', 'bg-green-500');
    }

    public function get_enums(): array
    {

        $categories = Cache::get('categories');
        if (is_null($categories)) {
            $categories = Category::all();

            $enumCurrentValues = DB::select("SHOW COLUMNS FROM sites WHERE Field = 'current_type'")[0]->Type;
            preg_match('/^enum\((.*)\)$/', $enumCurrentValues, $matches);
            $current_options = explode(',', $matches[1]);
            $current_options = array_map(fn ($value) => trim($value, "'"), $current_options);

            $enumSiteTypeValues = DB::select("SHOW COLUMNS FROM sites WHERE Field = 'site_type'")[0]->Type;
            preg_match('/^enum\((.*)\)$/', $enumSiteTypeValues, $matches);
            $site_type_options = explode(',', $matches[1]);
            $site_type_options = array_map(fn ($value) => trim($value, "'"), $site_type_options);

            $enumDocumentTypeValues = DB::select("SHOW COLUMNS FROM sites WHERE Field = 'document_type'")[0]->Type;
            preg_match('/^enum\((.*)\)$/', $enumDocumentTypeValues, $matches);
            $document_types = explode(',', $matches[1]);
            $document_types = array_map(fn ($value) => trim($value, "'"), $document_types);

            Cache::put('categories', $categories);
            Cache::put('current_options', $current_options);
            Cache::put('site_type_options', $site_type_options);
            Cache::put('document_types', $document_types);
        } else {
            $current_options = Cache::get('current_options');
            $site_type_options = Cache::get('site_type_options');
            $document_types = Cache::get('document_types');
        }

        return [
            'categories' => $categories,
            'current_options' => $current_options,
            'site_type_options' => $site_type_options,
            'document_types' => $document_types,
        ];
    }
}
