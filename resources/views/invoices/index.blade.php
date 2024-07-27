<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices') }}
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

                <h1 class="text-2xl font-bold mb-4">Create a new Invoice</h1>
                <a href="{{ route('invoices.create') }}" class="my-button">Create New Invoice</a>
            </div>
            <br>
            <br>
            <br>
            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">Invoices</h1>

            <div class="flex flex-wrap -mx-2">
                <div class="w-1/3 px-2 mb-4">
                    <input type="text" id="search_reference" placeholder="Search by reference" class="border w-full p-2">
                </div>
            
                <div class="w-1/3 px-2 mb-4">
                    <input type="text" id="search_status" placeholder="Search by status" class="border w-full p-2">
                </div>
                
                @can('invoices.see_admins_users')
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_user" placeholder="Search by user" class="border w-full p-2">
                    </div>
                @endcan
            
                <div class="w-1/3 px-2 mb-4">
                    <input type="text" id="search_site" placeholder="Search by site" class="border w-full p-2">
                </div>
            </div>

            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th scope="col" class="border border-gray-200 px-4 py-2">Reference</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Status</th>
                        @can('invoices.see_admins_users')
                            <th scope="col" class="border border-gray-200 px-4 py-2">User</th>
                        @endcan
                        <th scope="col" class="border border-gray-200 px-4 py-2">Site</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="invoices">
                    @foreach($invoices as $invoice)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $invoice->reference }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $invoice->status }}</td>
                            @can('invoices.see_admins_users')
                                <td class="border border-gray-200 px-4 py-2 text-orange-500 hover:text-purple-800 mr-2">
                                    <a href="{{ route('show.user', ['id' => $invoice->user->id]) }}">{{ $invoice->user->document }}</a>
                                </td>
                            @endcan
                            <td class="border border-gray-200 px-4 py-2 text-orange-500 hover:text-purple-800 mr-2">
                                <a href="{{ route('show.site', ['id' => $invoice->site->id]) }}">{{ $invoice->site->slug }}</a>
                            </td>
                            <td class="border border-gray-200 px-4 py-2 text-right">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-purple-800 mr-2"><i class="fas fa-eye"></i></a>
                                @can('pay_invoices.see_user')
                                    <a href="{{ route('show.site', $invoice->site->id) }}" class="text-orange-500 hover:text-purple-800 mr-2"><i class="fas fa-dollar"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <script>
            document.getElementById('search_reference').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#invoices tr');
    
                rows.forEach(row => {
                    let email = row.cells[0].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_status').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#invoices tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_user').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#invoices tr');
    
                rows.forEach(row => {
                    let email = row.cells[2].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            document.getElementById('search_site').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#invoices tr');
    
                rows.forEach(row => {
                    let email = row.cells[3].textContent.toLowerCase();
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