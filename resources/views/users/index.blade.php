<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
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

                <h1 class="text-2xl font-bold mb-4">Create a new user</h1>
                <a href="{{ route('users.create') }}" class="my-button">Create New User</a>
            </div>
            <br>
            <br>
            <br>
            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">Super Admins users</h1>

            <div class="w-1/2 mb-4">
                <input type="text" id="search_email_super_admin_users" placeholder="Search by email" class="border w-full p-2">
            </div>

            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th scope="col" class="border border-gray-200 px-4 py-2">Name</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Email</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="super_admin_users_table">
                    @foreach($super_admin_users as $super_admin_user)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $super_admin_user->name }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $super_admin_user->email }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">
                                @can(['super_user.options'])
                                <a href="{{ route('users.show', $super_admin_user->id) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $super_admin_user->id) }}" method="POST" class="text-yellow-600 hover:text-yellow-800 mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.destroy', $super_admin_user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                <h1 class="text-2xl font-bold mb-2">Admins users</h1>
                <div class="w-1/2 mb-4">
                    <input type="text" id="search_email_admin_users" placeholder="Search by email" class="border w-full p-2">
                </div>
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th scope="col" class="border border-gray-200 px-4 py-2">Name</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Email</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin_users_table">
                        @foreach($admin_users as $admin_user)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $admin_user->name }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $admin_user->email }}</td>
                                <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('users.show', $admin_user->id) }}" class="text-blue-600 hover:text-blue-800 mr-2"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('users.edit', $admin_user->id) }}" class="text-yellow-600 hover:text-yellow-800 mr-2"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('users.destroy', $admin_user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <br>
            </div>

            <div class="flex flex-col space-y-2">
                <h1 class="text-2xl font-bold mb-2">Guest users</h1>
                <div class="w-1/2 mb-4">
                    <input type="text" id="search_email_guest_users" placeholder="Search by email" class="border w-full p-2">
                </div>
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th scope="col" class="border border-gray-200 px-4 py-2">Name</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Email</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="guest_users_table">
                        @foreach($guest_users as $guest_user)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $guest_user->name }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $guest_user->email }}</td>
                                <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('users.show', $guest_user->id) }}" class="text-blue-600 hover:text-blue-800 mr-2"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('users.edit', $guest_user->id) }}" class="text-yellow-600 hover:text-yellow-800 mr-2"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('users.destroy', $guest_user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                    </form>
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
            document.getElementById('search_email_super_admin_users').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#super_admin_users_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_email_admin_users').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#admin_users_table tr');
    
                rows.forEach(row => {
                    let email = row.cells[1].textContent.toLowerCase();
                    if (email.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('search_email_guest_users').addEventListener('input', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#guest_users_table tr');
    
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