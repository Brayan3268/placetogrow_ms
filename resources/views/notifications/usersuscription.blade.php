<div class="container mx-auto mt-5 flex-col space-y-4 items-center">
    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Hola <strong>{{ $user->name }}</strong>.  </label><br><br>
    </div>

    @switch($notice)
        @case('suscription')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que su suscripción hacia el sitio <strong>{{ $site->name }} - {{ $site->slug}} </strong> ha obtenido el siguiente estado: </label><br>
                <label class="block mb-2 w-full font-bold px-4 py-3"> <h3>{{ $user_suscription->status }}</h3> </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Esta es la información de tu suscripción: </label>
            </div>
            @break
        @case('unsuscription')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que su dessuscripción hacia el sitio <strong>{{ $site->name }} - {{ $site->slug}} </strong> ha obtenido el siguiente estado: </label><br>
                <label class="block mb-2 w-full font-bold px-4 py-3"> <h3>{{ $user_suscription->status }}</h3> </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Esta es la información del plan al que te dessuscribiste: </label>
            </div>
            @break
        @case('notice_next_payment')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que su suscripción hacia el sitio <strong>{{ $site->name }} - {{ $site->slug}} </strong> tiene el siguiente estado: </label><br>
                <label class="block mb-2 w-full font-bold px-4 py-3"> <h3>{{ $user_suscription->status }}</h3> </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Y faltan {{ $user_suscription->days_until_next_payment }} días para realizar el siguiente cobro !Recuerda tener saldo en tu tarjeta! </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Esta es la información de tu suscripción: </label>
            </div>
            @break
        @case('notice_expiration_suscription')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que su suscripción hacia el sitio <strong>{{ $site->name }} - {{ $site->slug}} </strong> tiene el siguiente estado: </label><br>
                <label class="block mb-2 w-full font-bold px-4 py-3"> <h3>{{ $user_suscription->status }}</h3> </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Y faltan {{ $user_suscription->expiration_time }} días para que se termine tu suscripción !Renuevala con nosotros ingresando al sitio! </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Esta es la información de tu suscripción: </label>
            </div>
        @case('notice_deleted_suscription')
            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que su suscripción hacia el sitio <strong>{{ $site->name }} - {{ $site->slug}} </strong> tiene el siguiente estado: </label><br>
                <label class="block mb-2 w-full font-bold px-4 py-3"> <h3>{{ $user_suscription->status }}</h3> </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Su suscripción ha expirado !Renuevala con nosotros ingresando al sitio! </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 w-full font-bold px-4 py-3"> Esta es la información de la suscripción eliminada: </label>
            </div>
            @break
    @endswitch

    <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.view_my_suscription') }}</h1>
    <div class="flex flex-col max-w-lg mx-auto mt-4 items-center">
        <div class="mb-4">
            <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date') }}: {{ $user_suscription->created_at }}</label>
        </div>

        @switch($notice)
            @case('suscription')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: <a href="{{ route('user_suscriptions.show', $user_suscription->reference) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $user_suscription->reference }}</a></label>
                </div>
             
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.expiration_plan') }}: {{ $user_suscription->expiration_time }} {{ __('messages.days') }}</label>
                </div>
            
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.frecueny_collection') }}: {{ $user_suscription->suscription->frecuency_collection }}</label>
                </div>
                @break
            @case('unsuscription')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: <a href="{{ route('suscriptions.show', $user_suscription->suscription->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $user_suscription->reference }}</a></label>
                </div>
                @break
            @case('notice_next_payment')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: <a href="{{ route('user_suscriptions.show', $user_suscription->reference) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $user_suscription->reference }}</a></label>
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.frecueny_collection') }}: {{ $user_suscription->suscription->frecuency_collection }}</label>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.days_until_next_payment') }}: {{ $user_suscription->days_until_next_payment }} {{ __('messages.days') }}</label>
                </div>
                @break
            @case('notice_expiration_suscription')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: <a href="{{ route('user_suscriptions.show', $user_suscription->reference) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $user_suscription->reference }}</a></label>
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.expiration_plan') }}: {{ $user_suscription->expiration_time }} {{ __('messages.days') }} </label>
                </div>
                @break

            @case('notice_deleted_suscription')
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.date_deleted') }}: {{ $user_suscription->updated_at }}</label>
                </div>
                
                <div class="mb-4">
                    <label class="block mb-2 w-full font-bold px-4 py-3">{{ __('messages.reference') }}: <a href="{{ route('sites.show', $site->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $site->slug }}</a></label>
                </div>
                @break
        @endswitch
    </div>
</div>