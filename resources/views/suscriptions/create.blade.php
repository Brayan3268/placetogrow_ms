<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.create_suscription') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_suscription') }}</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.create_suscription') }}</h1>
    
        <form action="{{ route('suscriptions.store') }}" method="POST" class="max-w-lg mx-auto mt-5">
            @csrf
    
            <div class="mb-6">
                <label for="site_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.sites') }}:</label>
                <select id="site_id" name="site_id" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('site_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.select_site') }}</option>
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->slug }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.name') }}:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.description') }}:</label>
                <textarea id="description" name="description" value="{{ old('description') }}" rows="4" cols="50" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('description') border-red-500 @enderror" required></textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.currency') }}:</label>
                <select id="currency" name="currency" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('currency') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.select_currency') }}</option>
                    @foreach ($currency_type as $currency)
                        <option value="{{ $currency }}">{{ $currency }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.amount') }}:</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('amount') border-red-500 @enderror" required>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="expiration_time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.expiration_plan_days') }}:</label>
                <input type="number" id="expiration_time" name="expiration_time" placeholder="{{ __('messages.hint_expiration_plan') }}" value="{{ old('expiration_time') }}" min="7" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('expiration_time') border-red-500 @enderror" required>
                @error('expiration_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="frecuency_collection" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.frecuency_collection') }}:</label>
                <select id="frecuency_collection" name="frecuency_collection" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('frecuency_collection') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.select_frecuency_collection') }}</option>
                    @foreach ($frecuency_collection as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="number_trys" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.number_trys') }}:</label>
                <input type="number" id="number_trys" name="number_trys" placeholder="{{ __('messages.hint_number_trys') }}" value="{{ old('number_trys') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('number_trys') border-red-500 @enderror" required>
                @error('number_trys')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="how_often_days" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.how_often_days') }}:</label>
                <input type="number" id="how_often_days" name="how_often_days" placeholder="{{ __('messages.hint_how_often_days') }}" value="{{ old('how_often_days') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('how_often_days') border-red-500 @enderror" required>
                @error('how_often_days')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="my-button">{{ __('messages.create_suscription') }}</button>
        </form>
    </div>
    @endsection
</x-app-layout>