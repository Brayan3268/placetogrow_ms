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
            @can('suscription.manage')
            <form action="{{ route('suscriptions.edit', $suscription->id) }}" method="POST" class="max-w-lg mx-auto mt-5" enctype="multipart/form-data">
                @csrf
                @method('GET')
            @endcan

            @can('suscriptions.user_get_suscription')
                <form action="{{ route('user_suscriptions.store', $user_suscription->suscription->id) }}" method="POST" class="max-w-lg mx-auto mt-5" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                <input type="hidden" name="suscription_id" value="{{ $user_suscription->suscription->id }}" />
            @endcan
                <div class="mb-4">
                    <label for="site_id2" class="block text-sm font-bold mb-2">{{ __('messages.site') }}:</label>
                    <select id="site_id2" name="site_id" class="form-select block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('site_id2') border-red-500 @enderror" disabled>
                        <option value="{{ $site->id }}" selected>{{ old('site_id2', $site->slug) }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-bold mb-2">{{ __('messages.name') }}:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user_suscription->suscription->name) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" disabled>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.description') }}:</label>
                    <textarea id="description" name="description" value="{{ old('description') }}" rows="4" cols="50" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('description') border-red-500 @enderror" disabled>{{ $user_suscription->suscription->description }} </textarea>
                </div>

                <div class="mb-6">
                    <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.currency') }}:</label>
                    <select id="currency" name="currency" class="form-select block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('currency') border-red-500 @enderror" disabled>
                        <option value="" disabled selected>{{ old('currency', $user_suscription->suscription->currency_type) }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-sm font-bold mb-2">{{ __('messages.amount') }}:</label>
                    <input type="text" id="amount" name="amount" value="{{ old('amount', $user_suscription->suscription->amount) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('amount') border-red-500 @enderror" disabled>
                </div>

                <div class="mb-6">
                    <label for="expiration_time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.expiration_plan_days') }}:</label>
                    <input type="number" id="expiration_time" name="expiration_time" value="{{ old('expiration_time', $user_suscription->expiration_time) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('expiration_time') border-red-500 @enderror" disabled>
                </div>

                <div class="mb-6">
                    <label for="frecuency_collection" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.frecuency_collection') }}:</label>
                    <select id="frecuency_collection" name="frecuency_collection" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('frecuency_collection') border-red-500 @enderror" disabled>
                        <option value="" disabled selected>{{ old('frecuency_collection', $user_suscription->suscription->frecuency_collection) }}</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="days_until_next_payment" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.days_until_next_payment') }}:</label>
                    <input type="number" id="days_until_next_payment" name="days_until_next_payment" value="{{ old('days_until_next_payment', $user_suscription->days_until_next_payment) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('days_until_next_payment') border-red-500 @enderror" disabled>
                </div>

                <button type="submit" class="my-button">
                    @can('suscription.manage')
                        <i class="fas fa-edit"></i>
                    @endcan
                    @can('suscriptions.user_get_suscription')
                        <i class="fa-solid fa-circle-plus"></i>
                    @endcan
                </button>
            </form>
        </div>
    @endsection
</x-app-layout>