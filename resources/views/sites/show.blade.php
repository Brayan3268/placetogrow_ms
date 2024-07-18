<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Site') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Site</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">View Site</h1>
        <form action="{{ route('sites.edit', $site->id) }}" method="POST" class="max-w-lg mx-auto mt-5">
            @csrf
            @method('GET')

            <div class="mb-6 mt-6">
                @if ($site->image)
                    <img src= {{ URL::asset($site->image) }} class="img-responsive" alt="Perfil de {{ $site->name }}"
                    height="200" width="200">
                @else
                    <p>Not added a image for this site</p>
                @endif
            </div>

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $site->name) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('name') border-red-500 @enderror" requiered disabled>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Slug:</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $site->slug) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('slug') border-red-500 @enderror" requiered disabled>
                @error('slug')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="expiration_time" class="block text-gray-700 text-sm font-bold mb-2">Expiration time in minutes:</label>
                <input type="number" id="expiration_time" name="expiration_time" placeholder="Enter a expiration time greater than 10" value="{{ old('expiration_time', $site->expiration_time) }}" min="10" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('expiration_time') border-red-500 @enderror" requiered disabled>
                @error('expiration_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="document" class="block text-gray-700 text-sm font-bold mb-2">Document:</label>
                <input type="number" id="document" name="document" value="{{ old('document', $site->document) }}" min="10" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('document') border-red-500 @enderror" requiered disabled>
                @error('document')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="document_type" class="block text-gray-700 text-sm font-bold mb-2">Document type:</label>
                <select id="document_type" name="document_type" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('document_type') border-red-500 @enderror" requiered disabled>
                    <option value="" disabled selected>{{ old('document_type', $site->document_type) }}</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                <select id="category" name="category" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('Category') border-red-500 @enderror" requiered disabled>
                    <option value="" disabled selected>{{ old('category', $site->category->name) }}</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="current" class="block text-gray-700 text-sm font-bold mb-2">Current:</label>
                <select id="current" name="current" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('role') border-red-500 @enderror" requiered disabled>
                    <option value="" disabled selected>{{ old('category', $site->current_type) }}</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="site_type" class="block text-gray-700 text-sm font-bold mb-2">Site type:</label>
                <select id="site_type" name="site_type" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('role') border-red-500 @enderror" requiered disabled>
                    <option value="" disabled selected>{{ old('category', $site->site_type) }}</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="return_url" class="block text-gray-700 text-sm font-bold mb-2">Return url:</label>
                <input type="text" id="return_url" name="return_url" value="{{ old('return_url', $site->return_url) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('return_url') border-red-500 @enderror" requiered disabled>
                @error('return_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @can('site.manage')
                <button type="submit" class="my-button"><i class="fas fa-edit"></i></button>
            @endcan

            @can('site.pay')
                <button type="submit" class="my-button"><i class="fas fa-message-dollar"></i></button>
            @endcan
        </form>
    </div>
    @endsection

</x-app-layout>