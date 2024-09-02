<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.view_suscription') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.view_suscription') }}</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.view_suscription') }}</h1>
        <form action="{{ route('suscriptions.edit', $suscription->id) }}" method="POST" class="max-w-lg mx-auto mt-5">
            @csrf
            @method('GET')

            <div class="mb-4">
                <label for="site_id" class="block text-sm font-bold mb-2">{{ __('messages.site') }}:</label>
                <select id="site_id" name="site_id" class="form-select block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('site_id') border-red-500 @enderror" disabled>
                    <option value="" disabled selected>{{ old('site_id', $suscription->site->slug) }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">{{ __('messages.name') }}:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $suscription->name) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" disabled>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.description') }}:</label>
                <textarea id="description" name="description" value="{{ old('description') }}" rows="4" cols="50" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('description') border-red-500 @enderror" disabled>{{ $suscription->description }} </textarea>
            </div>

            <div class="mb-6">
                <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.currency') }}:</label>
                <select id="currency" name="currency" class="form-select block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('currency') border-red-500 @enderror" disabled>
                    <option value="" disabled selected>{{ old('currency', $suscription->currency_type) }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-sm font-bold mb-2">{{ __('messages.amount') }}:</label>
                <input type="text" id="amount" name="amount" value="{{ old('amount', $suscription->amount) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('amount') border-red-500 @enderror" disabled>
            </div>

            <div class="mb-6">
                <label for="expiration_time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.expiration_plan_days') }}:</label>
                <input type="number" id="expiration_time" name="expiration_time" value="{{ old('expiration_time', $suscription->expiration_time) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('expiration_time') border-red-500 @enderror" disabled>
            </div>

            <div class="mb-6">
                <label for="frecuency_collection" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.frecuency_collection') }}:</label>
                <select id="frecuency_collection" name="frecuency_collection" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('frecuency_collection') border-red-500 @enderror" disabled>
                    <option value="" disabled selected>{{ old('site_id', $suscription->frecuency_collection) }}</option>
                </select>
            </div>

            <button type="submit" class="my-button"><i class="fas fa-edit"></i></button>
        </form>
    </div>
    @endsection

</x-app-layout>