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
    <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
        <label for="">Referencia: {{ $payment->reference }}</label>
        <br>
        <label for="">description: {{ $payment->description }}</label>
        <br>
        <label for="">amount: {{ $payment->amount }}</label>
        <br>
        <label for="">currency: {{ $payment->currency }}</label>
        <br>
        <label for="">gateway: {{ $payment->gateway }}</label>
        <br>
        <label for="">status: {{ $payment->status }}</label>
        <br>
        <label for="">site_id: {{ $payment->site_id }}</label>
        <br>
        <label for="">user_id: {{ $payment->user_id }}</label>
    
    </div>
    @endsection

</x-app-layout>