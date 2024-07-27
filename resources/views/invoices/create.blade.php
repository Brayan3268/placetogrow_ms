<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create user') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">Create Invoice</h1>
    
        <form action="{{ route('invoices.store') }}" method="POST" class="max-w-lg mx-auto mt-5" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="mb-6">
                <label for="reference" class="block text-gray-700 text-sm font-bold mb-2">Reference:</label>
                <input type="text" id="reference" name="reference" value="{{ old('reference'/*, $user->reference*/) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('reference') border-red-500 @enderror" required>
                @error('reference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount') }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('amount') border-red-500 @enderror" required>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Currency type:</label>
                <select id="currency" name="currency" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('currency') border-red-500 @enderror" required>
                    <option value="" disabled selected>Select a Currency type</option>
                    @foreach ($currency_type as $currency)
                        <option value="{{ $currency }}">{{ $currency }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Users:</label>
                <select id="user_id" name="user_id" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('user_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>Select a user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name}}: {{ $user->document }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="site_id" class="block text-gray-700 text-sm font-bold mb-2">Sites</label>
                <select id="site_id" name="site_id" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('site_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>Select a site</option>
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->slug }}</option>
                    @endforeach
                </select>
            </div>
    
            <div class="mb-6">
                <label for="date_expiration" class="block text-gray-700 text-sm font-bold mb-2">Date expiration:</label>
                <input type="datetime-local" id="date_expiration" name="date_expiration" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('date_expiration') border-red-500 @enderror" required>
                @error('date_expiration')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="my-button">Create Invoice</button>
        </form>
    </div>
    @endsection
</x-app-layout>