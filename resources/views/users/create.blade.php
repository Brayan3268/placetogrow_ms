<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create user') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_user') }}</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.create_user') }}</h1>
    
        <form action="{{ route('users.store') }}" method="POST" class="max-w-lg mx-auto mt-5">
            @csrf
    
            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.name') }}:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.last_name') }}:</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('last_name') border-red-500 @enderror" required>
                @error('last_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
    
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.email') }}:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.phone') }}:</label>
                <input type="number" id="phone" name="phone" value="{{ old('phone') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('phone') border-red-500 @enderror" required>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="document" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.document') }}:</label>
                <input type="number" id="document" name="document" value="{{ old('document') }}" min="10" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('document') border-red-500 @enderror" required>
                @error('document')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="document_type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.document_type') }}:</label>
                <select id="document_type" name="document_type" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('document_type') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.select_document_type') }}</option>
                    @foreach ($document_types as $document_type)
                        <option value="{{ $document_type }}">{{ $document_type }}</option>
                    @endforeach
                </select>
            </div>
    
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.password') }}:</label>
                <input type="password" id="password" name="password" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.role') }}:</label>
                <select id="role" name="role" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('role') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.select_role') }}</option>
                    @can(['super_user.options'])
                        <option value="super_admin">{{ __('messages.super_admin') }}</option>
                    @endcan
                    <option value="admin">{{ __('messages.admin') }}</option>
                    <option value="guest">{{ __('messages.guest') }}</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
    
            <button type="submit" class="my-button">{{ __('messages.create_user') }}</button>
        </form>
    </div>
    @endsection
</x-app-layout>