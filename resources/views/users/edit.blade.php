<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create user') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    </x-slot>

    @section('content')
    <div class="max-w-lg mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Edit User</h1>
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-bold mb-2">Role:</label>
                <select id="role" name="role" class="form-select block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('role') border-red-500 @enderror" required>
                    <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guest" {{ $user->role === 'guest' ? 'selected' : '' }}>Guest</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update User</button>
        </form>
    </div>
    @endsection

</x-app-layout>