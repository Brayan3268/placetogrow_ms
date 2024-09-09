<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.suscription') }}
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
            
            <div class="container mx-auto mt-5 flex flex-col space-y-4 items-center">
                <h1 class="text-2xl font-bold mb-4">{{ __('messages.suscription') }}</h1>
            </div>
            @can('suscription.create')
                <div class="container mx-auto mt-5 flex flex-col space-y-4 items-center">
                    <a href="{{ route('suscriptions.create') }}" class="my-button">{{ __('messages.create_suscription') }}</a>
                </div>
                <br><br><br>
            @endcan

            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">{{ __('messages.all_suscriptions') }}</h1>

                <div class="flex flex-wrap -mx-2">
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_plan_name_all_plans_table" placeholder="{{ __('messages.search_by_name_suscription') }}" class="border w-full p-2">
                    </div>
                    
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_slug_site_all_plans_table" placeholder="{{ __('messages.search_by_slug_site') }}" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_currency_all_plans_table" placeholder="{{ __('messages.search_by_currency') }}" class="border w-full p-2">
                    </div>

                    <div class="w-1/3 px-2 mb-4">
                        <input type="number" id="search_amount_all_plans_table" placeholder="{{ __('messages.search_by_amount') }}" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_plan_time_all_plans_table" placeholder="{{ __('messages.search_by_plan_time') }}" class="border w-full p-2">
                    </div>
                </div>

                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.plan_name') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.slug_site') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.amount_suscription') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.expiration_plan') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="all_plans_table">
                        @foreach($suscriptions as $suscription)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $suscription->name }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $suscription->site->slug }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $suscription->currency_type }} {{ $suscription->amount }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $suscription->expiration_time }} {{ __('messages.days') }} </td>
                                <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('suscriptions.show', $suscription->id) }}" class="text-blue-600 hover:text-purple-800 mr-2"><i class="fas fa-eye"></i></a>
                                    @can('suscription.manage')
                                        <a href="{{ route('suscriptions.edit', $suscription->id) }}" method="POST" class="text-yellow-600 hover:purple-yellow-800 mr-2"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('suscriptions.destroy', $suscription->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-purple-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    @can('suscriptions.user_get_suscription')
                                        <form action="{{ route('user_suscriptions.store', $suscription->id) }}" method="POST" class="text-orange-600 hover:purple-yellow-800 mr-2" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="suscription_id" value="{{ $suscription->id }}" />
                                            <button type="submit" class="text-orange-600 hover:purple-yellow-800 mr-2">
                                                <i class="fa-solid fa-circle-plus"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br><br>
            </div>

            <div class="flex flex-col space-y-2">

                @can('suscription.manage')
                    <h1 class="text-2xl font-bold mb-2">{{ __('messages.users_plans') }}</h1>
                @endcan

                @can('suscriptions_users.index')
                    <h1 class="text-2xl font-bold mb-2">{{ __('messages.my_plans') }}</h1>
                @endcan

                <div class="flex flex-wrap -mx-2">
                    @can('suscription.manage')
                        <div class="w-1/3 px-2 mb-4">
                            <input type="text" id="search_user" placeholder="{{ __('messages.search_by_user') }}" class="border w-full p-2">
                        </div>
                    @endcan

                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_plan_name_user_plans_table" placeholder="{{ __('messages.search_by_name_suscription') }}" class="border w-full p-2">
                    </div>
                    
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_slug_site_user_plans_table" placeholder="{{ __('messages.search_by_slug_site') }}" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_currency_user_plans_table" placeholder="{{ __('messages.search_by_currency') }}" class="border w-full p-2">
                    </div>

                    <div class="w-1/3 px-2 mb-4">
                        <input type="number" id="search_amount_user_plans_table" placeholder="{{ __('messages.search_by_amount') }}" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_plan_time_user_plans_table" placeholder="{{ __('messages.search_by_plan_time') }}" class="border w-full p-2">
                    </div>
                </div>
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            @can('suscription.manage')
                                <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.user_document') }}</th>
                            @endcan
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.plan_name') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.slug_site') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.amount_suscription') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.expiration_plan') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="user_plans_table">
                        @foreach($user_suscriptions as $user_suscription)
                            <tr>
                                @can('suscription.manage')
                                    <td class="border border-gray-200 px-4 py-2 text-orange-500 hover:text-purple-800 mr-2">
                                        <a href="{{ route('show.user', ['id' => $user_suscription->user->id]) }}">{{ $user_suscription->user->document }}</a>
                                    </td>
                                @endcan
                                <td class="border border-gray-200 px-4 py-2">{{ $user_suscription->suscription->name }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $user_suscription->suscription->site->slug }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $user_suscription->suscription->currency_type }} {{ $user_suscription->suscription->amount }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $user_suscription->suscription->expiration_time }} {{ __('messages.days') }} </td>
                                <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('suscriptions.show', $user_suscription->suscription->id) }}" class="text-blue-600 hover:text-purple-800 mr-2"><i class="fas fa-eye"></i></a>
                                    
                                    <form action="{{ route('user_suscriptions.destroyy', ['reference' => $user_suscription->reference, 'user_id' => $user_suscription->user_id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-purple-800"><i class="fa-solid fa-circle-minus"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br><br>
            </div>
        </div>

        <script>
            document.getElementById('search_plan_name_all_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#all_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[0].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_slug_site_all_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#all_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_currency_all_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#all_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[2].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_amount_all_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#all_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[2].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_plan_time_all_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#all_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[3].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_user').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#user_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[0].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_plan_name_user_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#user_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_slug_site_user_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#user_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[2].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_currency_user_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#user_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[3].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_amount_user_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#user_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[3].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_plan_time_user_plans_table').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#user_plans_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[4].textContent.toLowerCase();
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