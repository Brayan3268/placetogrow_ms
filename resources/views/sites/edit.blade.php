<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.edit_site') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ __('messages.edit_site') }}</title>

    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.edit_site') }}</h1>
        <form action="{{ route('sites.update', $site->id) }}" method="POST" class="max-w-lg mx-auto mt-5" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-6 mt-6">
                @if ($site->image)
                    <img src= {{ URL::asset($site->image) }} class="img-responsive" alt="Perfil de {{ $site->name }}"
                    height="200" width="200">
                @else
                    <p>{{ __('messages.not_logo') }}</p>
                @endif
            </div>

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.name') }}:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $site->name) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.slug') }}:</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $site->slug) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('slug') border-red-500 @enderror" required>
                @error('slug')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="expiration_time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.expiration_time') }}:</label>
                <input type="number" id="expiration_time" name="expiration_time" placeholder="Enter a expiration time greater than 10" value="{{ old('expiration_time', $site->expiration_time) }}" min="10" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('expiration_time') border-red-500 @enderror" required>
                @error('expiration_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="category" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.category') }}:</label>
                <select id="category" name="category" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('Category') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.currently') }}: {{ $site->category->name }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.currency') }}:</label>
                <select id="currency" name="currency" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('role') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.currently') }}: {{ $site->currency_type }}</option>
                    @foreach ($currency_options as $currency_option)
                        <option value="{{ $currency_option }}">{{ $currency_option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="site_type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.site_type') }}:</label>
                <select id="site_type" name="site_type" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('role') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.currently') }}: {{ $site->site_type }}</option>
                    @foreach ($site_type_options as $site_type_option)
                        <option value="{{ $site_type_option }}">{{ $site_type_option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <div class="form-group" enctype="multipart/form-data">
                    <label for="image">{{ __('messages.select_logo') }}:</label>
                    <input type="file"  class="form-control-file" id="image" name="image" >
                </div>
                <label for="image">{{ __('messages.hint_logo') }}</label>
            </div>

            <button type="submit" class="my-button">{{ __('messages.update_site') }}</button>
        </form>
    </div>
    @endsection

</x-app-layout>