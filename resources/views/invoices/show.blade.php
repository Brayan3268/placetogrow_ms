<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.view_invoice') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.view_invoice') }}</title>
    </x-slot>

    @section('content')

    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.view_invoice') }}</h1>
        <form action="{{ route('invoices.edit', $invoice->id) }}" method="POST" class="max-w-lg mx-auto mt-5">
            @csrf
            @method('GET')

            <div class="mb-4">
                <label for="reference" class="block text-sm font-bold mb-2">{{ __('messages.reference') }}:</label>
                <input type="text" id="reference" name="reference" value="{{ old('reference', $invoice->reference) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('reference') border-red-500 @enderror" required disabled>
                @error('reference')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if ($invoice->description !== null)
                <div class="mb-4">
                    <label for="description" class="block text-sm font-bold mb-2">{{ __('messages.description') }}:</label>
                    <input type="text" id="description" name="description" value="{{ old('description', $invoice->description) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror" required disabled>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="mb-4">
                <label for="amount" class="block text-sm font-bold mb-2">{{ __('messages.amount') }}:</label>
                <input type="text" id="amount" name="amount" value="{{ old('amount', $invoice->amount) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('amount') border-red-500 @enderror" required disabled>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.currency_type') }}:</label>
                <select id="currency" name="currency" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('currency') border-red-500 @enderror" requiered disabled>
                    <option value="" disabled selected>{{ old('currency', $invoice->currency) }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-bold mb-2">{{ __('messages.status') }}:</label>
                <input type="text" id="status" name="status" value="{{ old('status', $invoice->status) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror" required disabled>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @can(['super_users.show'])
                <div class="mb-6">
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.user_document') }}:</label>
                    <select id="user_id" name="user_id" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('user_id') border-red-500 @enderror" requiered disabled>
                        <option value="" disabled selected>{{ old('currency', $invoice->user->document) }}</option>
                    </select>
                </div>
            @endcan

            <div class="mb-6">
                <label for="site_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.site') }}:</label>
                <select id="site_id" name="site_id" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('currency') border-red-500 @enderror" requiered disabled>
                    <option value="" disabled selected>{{ old('currency', $invoice->site->slug) }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="date_created" class="block text-sm font-bold mb-2">{{ __('messages.invoice_created') }}:</label>
                <input type="text" id="date_created" name="date_created" value="{{ old('date_created', $invoice->date_created) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('date_created') border-red-500 @enderror" required disabled>
                @error('date_created')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            
            <div class="mb-4">
                <label for="date_expiration" class="block text-sm font-bold mb-2">{{ __('messages.invoice_expirated') }}:</label>
                <input type="text" id="date_expiration" name="date_expiration" value="{{ old('date_expiration', $invoice->date_expiration) }}" class="form-input block w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 @error('date_expiration') border-red-500 @enderror" required disabled>
                @error('date_expiration')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            @can('site.manage')
                <button type="submit" class="my-button"><i class="fas fa-edit"></i></button>
            @endcan
        </form>
    </div>

    @endsection

</x-app-layout>