<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Microsites') }}
        </h2>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </x-slot>

    @section('content')
        <div class="container mx-auto mt-5 flex flex-col space-y-4">

            @if (session('status'))
            <div class="container mx-auto mt-5 flex flex-col space-y-4 items-center">
                <div class="alert {{ session('class') }} text-white p-4 rounded w-1/2 mb-4 text-center">
                    {{ session('status') }}
                </div>
            </div>
            @endif
            @can('site.manage')
            <div class="container mx-auto mt-5 flex flex-col space-y-4 items-center">

                <h1 class="text-2xl font-bold mb-4">{{ __('messages.create_new_site') }}</h1>
                <a href="{{ route('sites.create') }}" class="my-button">{{ __('messages.create_new_site') }}</a>
            </div>
            <br>
            <br>
            <br>
            @endcan
            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">{{ __('messages.microsites_donations') }}</h1>

            <div class="w-1/2 mb-4">
                <input type="text" id="search_names_open_sites" placeholder="{{ __('messages.search_by_slug') }}" class="border w-full p-2">
            </div>

            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.name') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.slug') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.categories') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="open_sites_table">
                    @foreach($open_sites as $open_site)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $open_site->name }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $open_site->slug }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $open_site->category->name }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">                                    
                                <a href="{{ route('sites.show', $open_site->id) }}" class="text-blue-600 hover:text-purple-800 mr-2"><i class="fas fa-eye"></i></a>
                                @can('site.manage')
                                <a href="{{ route('sites.edit', $open_site->id) }}" method="POST" class="text-yellow-600 hover:text-purple-800 mr-2"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('sites.destroy', $open_site->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                </form>
                                <a href="{{ route('sites.manage_config', $open_site->id) }}" class="text-orange-600 hover:text-purple-800 ml-2"><i class="fas fa-bars"></i></a>
                                @endcan
                                <a href="{{ route('payment.pays_site', $open_site->id) }}" class="text-orange-500 hover:text-purple-800 mr-2"><i class="fas fa-search-dollar"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                <br>
                <br>
            </div>

            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">{{ __('messages.microsites_invoices') }}</h1>
                <div class="w-1/2 mb-4">
                    <input type="text" id="search_name_close_sites" placeholder="{{ __('messages.search_by_slug') }}" class="border w-full p-2">
                </div>
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.name') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.slug') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.categories') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="close_sites_table">
                        @foreach($close_sites as $close_site)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $close_site->name }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $close_site->slug }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $close_site->category->name }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">
                                <a href="{{ route('sites.show', $close_site->id) }}" class="text-blue-600 hover:text-purple-800 mr-2"><i class="fas fa-eye"></i></a>
                                @can('site.manage')
                                    <a href="{{ route('sites.edit', $close_site->id) }}" class="text-yellow-600 hover:text-purple-800 mr-2"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('sites.destroy', $close_site->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <a href="{{ route('sites.manage_config', $close_site->id) }}" method="POST" class="text-orange-600 hover:text-purple-800 ml-2"><i class="fas fa-bars"></i></a>
                                @endcan
                                <a href="{{ route('payment.pays_site', $close_site->id) }}" class="text-orange-500 hover:text-purple-800 mr-2"><i class="fas fa-search-dollar"></i></a>
                                
                                @can('site.manage')
                                    <form action="{{ route('sites.import_invoices', $close_site->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" accept=".xlsx" tex>
                                        <button class="text-orange-500 hover:text-purple-800 mr-2" type="submit"><i class="fa-solid fa-file-import"></i></button>
                                    </form>
                                @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <br>
            </div>

            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">{{ __('messages.microsites_suscription') }}</h1>
                <div class="w-1/2 mb-4">
                    <input type="text" id="search_name_suscription_sites" placeholder="{{ __('messages.search_by_slug') }}" class="border w-full p-2">
                </div>
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.name') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.slug') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.categories') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="suscription_sites_table">
                        @foreach($suscription_sites as $suscription_site)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $suscription_site->name }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $suscription_site->slug }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $suscription_site->category->name }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('sites.show', $suscription_site->id) }}" class="text-blue-600 hover:text-purple-800 mr-2"><i class="fas fa-eye"></i></a>
                                    @can('site.manage')
                                    <a href="{{ route('sites.edit', $suscription_site->id) }}" class="text-yellow-600 hover:text-purple-800 mr-2"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('sites.destroy', $suscription_site->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <a href="{{ route('sites.manage_config', $suscription_site->id) }}" method="POST" class="text-orange-600 hover:text-purple-800 ml-2"><i class="fas fa-bars"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <br>
            </div>
        </div>

        <script>
            document.getElementById('search_names_open_sites').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#open_sites_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_name_close_sites').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#close_sites_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_name_suscription_sites').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#suscription_sites_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>