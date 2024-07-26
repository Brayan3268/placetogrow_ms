<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
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
                <h1 class="text-2xl font-bold mb-2">Pays</h1>

                <br>
                <div class="border-t border-gray-900 my-4"></div>
                <br>

                <h2 class="text-2xl font-bold mb-2">Filters</h2>

                <div class="flex flex-wrap -mx-2">
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_reference" placeholder="Search by reference" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_amount" placeholder="Search by amount" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_currency" placeholder="Search by currency" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_status" placeholder="Search by status" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_user" placeholder="Search by user" class="border w-full p-2">
                    </div>
                
                    <div class="w-1/3 px-2 mb-4">
                        <input type="text" id="search_site" placeholder="Search by site" class="border w-full p-2">
                    </div>
                </div>

                <br>
                <div class="border-t border-gray-900 my-4"></div>
                <br>
            
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th scope="col" class="border border-gray-200 px-4 py-2">Reference</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Amount</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Currency</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Status</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">User</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Site</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pays_user">
                        @foreach($pays as $pay)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->reference }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->amount }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->currency }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->status }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->user->document }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $pay->site->slug }}</td>
                                <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('payment.show', $pay->reference) }}" class="text-blue-600 hover:text-purple-800 mr-2">
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
            document.getElementById('search_reference').addEventListener('input', function() {
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
            document.getElementById('search_amount').addEventListener('input', function() {
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
            document.getElementById('search_currency').addEventListener('input', function() {
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
            document.getElementById('search_user').addEventListener('input', function() {
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
        </script>
    @endsection
</x-app-layout>