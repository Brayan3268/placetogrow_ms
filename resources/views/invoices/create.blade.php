<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.create_invoice') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_invoice') }}</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.create_invoice') }}</h1>
    
        <form action="{{ route('invoices.store') }}" method="POST" class="max-w-lg mx-auto mt-5" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="mb-6">
                <label for="reference" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.reference') }}:</label>
                <input type="text" id="reference" name="reference" value="{{ old('reference'/*, $user->reference*/) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('reference') border-red-500 @enderror" required>
                @error('reference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.amount') }}:</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('amount') border-red-500 @enderror" required>
                @error('amount')
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
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.users') }}:</label>
                <select id="user_id" name="user_id" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('user_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>{{ __('messages.select_user') }}</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}: {{ $user->document }}</option>
                    @endforeach
                </select>
            </div>

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
                <label for="date_surcharge" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.date_surcharge') }}:</label>
                <input type="datetime-local" id="date_surcharge" name="date_surcharge" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('date_surcharge') border-red-500 @enderror" required>
                @error('date_surcharge')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="amount_surcharge" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.amount_surcharge') }}:</label>
                <input type="number" id="amount_surcharge" name="amount_surcharge" value="{{ old('amount_surcharge') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('amount_surcharge') border-red-500 @enderror" required>
                @error('amount_surcharge')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="date_expiration" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.date_expiration') }}:</label>
                <input type="datetime-local" id="date_expiration" name="date_expiration" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('date_expiration') border-red-500 @enderror" required>
                @error('date_expiration')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="my-button">{{ __('messages.create_invoice') }}</button>
        </form>
    </div>
    @endsection
</x-app-layout>