<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Payment') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment</title>
    </x-slot>

    @section('content')
    <div class="container mx-auto mt-5 flex-col space-y-4 items-center">
        <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">View my pay</h1>
        <div class="flex flex-col max-w-lg mx-auto mt-4 items-center">

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3">Reference: {{ $payment->reference }}</label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3">Description: {{ $payment->description }}</label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold w-full px-4 py-3">Amount: {{ $payment->amount }}</label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold w-full px-4 py-3">Currency: {{ $payment->currency }}</label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold w-full px-4 py-3">Status: {{ $payment->status }}</label>
            </div>

            <div class="mb-4">
                <label class="block font-bold w-full px-4 py-3">Site:<a href="{{ route('sites.show', $payment->site->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $payment->site->slug }}</a></label> 
            </div>  

            @can('pays_info.show')
                <div class="mb-4">
                    <label class="block font-bold w-full px-4 py-3">User document:<a href="{{ route('show.user', ['id' => $payment->user->id]) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $payment->user->document }}</a></label> 
                </div>
            @endcan

            @if ($invoice_status != "")
                <div class="mb-4">
                    <label class="block font-bold w-full px-4 py-3">Invoice reference:<a href="{{ route('invoices.show', $invoice->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $invoice->reference }}</a></label> 
                </div>  

                <div class="mb-4">
                    <label class="block mb-2 font-bold w-full px-4 py-3">Invoice status: {{ $invoice_status }}</label>
                </div>
            @endif

        </div>
    </div>
    @endsection

</x-app-layout>