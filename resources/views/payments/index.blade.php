<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.payments') }}
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

            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">{{ __('messages.payments') }}</h1>

                <br>
                <div class="border-t border-gray-900 my-4"></div>
                <br>

                <h2 class="text-2xl font-bold mb-2">{{ __('messages.filters') }}</h2>

                <div class="flex flex-wrap -mx-2">
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_date" placeholder="{{ __('messages.search_by_date') }}" class="border w-full p-2">
                    </div>

                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_reference" placeholder="{{ __('messages.search_by_reference') }}" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_amount" placeholder="{{ __('messages.search_by_amount') }}" class="border w-full p-2">
                    </div>

                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_status" placeholder="{{ __('messages.search_by_status') }}" class="border w-full p-2">
                    </div>
                    
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_origin_pay" placeholder="{{ __('messages.search_by_origin_payment') }}" class="border w-full p-2">
                    </div>

                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_site" placeholder="{{ __('messages.search_by_site') }}" class="border w-full p-2">
                    </div>

                    @can('payments.see_admins_users')
                        <div class="w-1/3 px-2 mb-4">
                            <input type="text" id="search_user" placeholder="{{ __('messages.search_by_user') }}" class="border w-full p-2">
                        </div>
                    @endcan
                </div>

                <br>
                <div class="border-t border-gray-900 my-4"></div>
                <br>
            
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.date') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.reference') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.amount_short') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.status') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.origin_payment') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.site') }}</th>
                            @can('payments.see_admins_users')
                                <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.user') }}</th>
                            @endcan
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="pays_user">
                        @foreach($pays as $pay)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->created_at }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->reference }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->currency }} {{ $pay->amount }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->status }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->origin_payment }}</td>
                                <td class="border border-gray-200 px-4 py-2 text-orange-500 hover:text-purple-800 mr-2">
                                    <a href="{{ route('show.site', ['id' => $pay->site->id]) }}">{{ $pay->site->slug }}</a>
                                </td>
                                @can('payments.see_admins_users')
                                    <td class="border border-gray-200 px-4 py-2 text-orange-500 hover:text-purple-800 mr-2">
                                        <a href="{{ route('show.user', ['id' => $pay->user->id]) }}">{{ $pay->user->document }}</a>
                                    </td>
                                @endcan
                                <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('payment.show', $pay->id) }}" class="text-blue-600 hover:text-purple-800 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
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
            document.getElementById('search_date').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#pays_user tr');
    
                rows.forEach(row => {
                    let email = row.cells[0].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_reference').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#pays_user tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_amount').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#pays_user tr');
    
                rows.forEach(row => {
                    let email = row.cells[2].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_status').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#pays_user tr');
    
                rows.forEach(row => {
                    let email = row.cells[3].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_origin_pay').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#pays_user tr');
    
                rows.forEach(row => {
                    let email = row.cells[4].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_site').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#pays_user tr');
    
                rows.forEach(row => {
                    let email = row.cells[5].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_user').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#pays_user tr');
    
                rows.forEach(row => {
                    let email = row.cells[6].textContent.toLowerCase();
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