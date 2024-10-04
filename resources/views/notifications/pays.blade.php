

<div class="container mx-auto mt-5 flex-col space-y-4 items-center">
    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Hola <strong>{{ $user->name }}</strong>.  </label><br><br>
    </div>

    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que su pago hacia el sitio <strong>{{ $payment->site->name }} - {{ $payment->site->slug}} </strong>  ha obtenido el siguiente estado: </label><br>
        <label class="block mb-2 w-full font-bold px-4 py-3"> <h3>{{ $payment->status }}</h3> </label>
    </div>

    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Esta es la información de tu pago: </label>
    </div>

    <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.view_my_pay') }}</h1>
    <div class="flex flex-col max-w-lg mx-auto mt-4 items-center">

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date') }}: {{ $payment->created_at }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.origin_payment') }}: {{ $payment->origin_payment }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: <a href="{{ route('payment.show', $payment) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $payment->reference }}</a></label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.description') }}: {{ $payment->description }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-bold w-full px-4 py-3">{{ __('messages.currency') }}: {{ $payment->currency }}</label>
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-bold w-full px-4 py-3">{{ __('messages.valor cobrado') }}: {{ $payment->amount }}</label>
        </div>

        <div class="mb-4">
            <label class="block font-bold w-full px-4 py-3">{{ __('messages.site') }}:<a href="{{ route('sites.show', $payment->site->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $payment->site->slug }}</a></label> 
        </div>  

        @can('pays_info.show')
            <div class="mb-4">
                <label class="block font-bold w-full px-4 py-3">{{ __('messages.user_document') }}:<a href="{{ route('show.user', ['id' => $payment->user->id]) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $payment->user->document }}</a></label> 
            </div>
        @endcan

        @if ($invoice_status != "")
            <div class="mb-4">
                <label class="block font-bold w-full px-4 py-3">{{ __('messages.invoice_reference') }}:<a href="{{ route('invoices.show', $invoice->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $invoice->reference }}</a></label> 
            </div>  

            <div class="mb-4">
                <label class="block mb-2 font-bold w-full px-4 py-3">{{ __('messages.invoice_status') }}: {{ $invoice_status }}</label>
            </div>
        @endif

        @if ($suscription_status != "")
            <div class="mb-2">
                <label class="block font-bold w-full px-4 py-3">{{ __('messages.suscription_reference') }}:<br>
                <a href="{{ route('user_suscriptions.show', ['user_suscription' => $user_suscription->reference]) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $user_suscription->reference }}</a></label> 
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold w-full px-4 py-3">{{ __('messages.suscription_status') }}: {{ $suscription_status }}</label>
            </div>
        @endif

    </div>
</div>