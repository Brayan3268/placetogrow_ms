<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View user') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">View User</h1>
        <form action="{{ route('users.edit', $user->id) }}" method="POST" class="max-w-lg mx-auto mt-5">
            @csrf
            @method('GET')

            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" required disabled>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-bold mb-2">Last name:</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('last_name') border-red-500 @enderror" required disabled>
                @error('last_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror" required disabled>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                <input type="number" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" min="10" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('phone') border-red-500 @enderror" requiered disabled>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="document" class="block text-gray-700 text-sm font-bold mb-2">Document:</label>
                <input type="number" id="document" name="document" value="{{ old('document', $user->document) }}" min="10" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('document') border-red-500 @enderror" requiered disabled>
                @error('document')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="document_type" class="block text-gray-700 text-sm font-bold mb-2">Document type:</label>
                <select id="document_type" name="document_type" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('document_type') border-red-500 @enderror" requiered disabled>
                    <option value="" disabled selected>{{ old('document_type', $user->document_type) }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-bold mb-2">Role:</label>
                <select id="role" name="role" class="form-select block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('role') border-red-500 @enderror" required disabled>
                    <option value="" disabled selected>{{ old('role', $role_name[0]) }}</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="my-button"><i class="fas fa-edit"></i></button>
        </form>
    </div>
    @endsection

</x-app-layout>