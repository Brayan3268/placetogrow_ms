<div class="container mx-auto mt-5 flex-col space-y-4 items-center">
    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Hola <strong>{{ $user->name }}</strong>.  </label><br><br>
    </div>

    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que se ha creado una factura a su nombre para el sitio <strong>{{ $invoice->site->name }} - {{ $invoice->site->slug}} </strong>  con la siguiente información. </label><br><br>
    </div>

    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Puedes ingresar al sitio mediante el siguiente enlace para realizar el pago de tu factura. </label><br>
    </div>
    <div class="flex flex-col max-w-lg mx-auto mt-4 items-center">
        <div class="mb-4">
            <label class="block font-bold w-full px-4 py-3">{{ __('messages.site') }}:<a href="{{ route('sites.show', $invoice->site->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $invoice->site->slug }}</a></label> 
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date') }}: {{ $invoice->reference }}</label>
        </div>
         
        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date') }}: {{ $invoice->date_created }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date_surcharge') }}: {{ $invoice->date_surcharge }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.amount_surcharge') }}: {{ $invoice->amount_surcharge }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date') }}: {{ $invoice->date_expiration }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date') }}: {{ $invoice->currency }} {{ $invoice->amount }} </label>
        </div>
    </div>
</div>