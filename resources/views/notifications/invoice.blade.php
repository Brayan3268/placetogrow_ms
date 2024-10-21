<div class="container mx-auto mt-5 flex-col space-y-4 items-center">
    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Hola <strong>{{ $user->name }}</strong>.  </label><br><br>
    </div>

    @switch($notice)
        @case('created')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que se ha creado una factura a su nombre para el sitio <strong>{{ $invoice->site->name }} - {{ $invoice->site->slug}} </strong>  con la siguiente información. </label><br><br>
            </div>
            
            <div class="mb-4">
              <label class="block mb-2 w-full font-bold px-4 py-3"> Puedes ingresar al sitio mediante el siguiente enlace para realizar el pago de tu factura. </label><br>
            </div>
            @break
        @case('surcharge')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que se ha aplicado recargo a una factura a su nombre para el sitio <strong>{{ $invoice->site->name }} - {{ $invoice->site->slug}} </strong>  con la siguiente información. </label><br><br>
            </div>
            
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Puedes ingresar al sitio mediante el siguiente enlace para realizar el pago de tu factura. </label><br>
            </div>
            @break
        @case('expirated')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que se ha vencido una factura a su nombre para el sitio <strong>{{ $invoice->site->name }} - {{ $invoice->site->slug}} </strong>  con la siguiente información. </label><br><br>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Puedes ingresar a la factura mediante el siguiente enlace para obtener más detalles. </label><br>
            </div>
            @break
    @endswitch

    <div class="flex flex-col max-w-lg mx-auto mt-4 items-center">
        @switch($notice)
            @case('created')
                <div class="mb-4">
                    <label class="block font-bold w-full px-4 py-3">{{ __('messages.site') }}:<a href="{{ route('sites.show', $invoice->site->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $invoice->site->slug }}</a></label> 
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: {{ $invoice->reference }}</label>
                </div>
                @break
            @case('surcharge')
                <div class="mb-4">
                    <label class="block font-bold w-full px-4 py-3">{{ __('messages.site') }}:<a href="{{ route('sites.show', $invoice->site->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $invoice->site->slug }}</a></label> 
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: {{ $invoice->reference }}</label>
                </div>
                @break
            @case('expirated')
                <div class="mb-4">
                    <label class="block font-bold w-full px-4 py-3">{{ __('messages.invoice') }}: <a href="{{ route('invoices.show', $invoice->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $invoice->reference }}</a></label> 
                </div>
                @break
        @endswitch
         
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
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date_expiration') }}: {{ $invoice->date_expiration }}</label>
        </div>

        @switch($notice)
            @case('created')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.amount') }}: {{ $invoice->currency }} {{ $invoice->amount }} </label>
                </div>
                @break
            @case('surcharge')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.new_amount') }}: {{ $invoice->currency }} {{ $invoice->amount }} </label>
                </div>
                @break
            @case('expirated')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.amount') }}: {{ $invoice->currency }} {{ $invoice->amount }} </label>
                </div>
                @break
        @endswitch
    </div>
</div>









