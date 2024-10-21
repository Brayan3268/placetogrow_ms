

<div class="container mx-auto mt-5 flex-col space-y-4 items-center">
    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Hola <strong>{{ $user->name }}</strong>.  </label><br><br>
    </div>

    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Le informamos que la sesión de pago para el sitio <strong>{{ $payment->site->name }} - {{ $payment->site->slug}} </strong>  ha sido eliminada. </label><br><br>
    </div>

    <div class="mb-4">
        <label class="block mb-2 w-full font-bold px-4 py-3"> Puedes ingresar al sitio mediante el siguiente enlace para realizar un nuevo intento de pago. </label><br>
    </div>
    <div class="flex flex-col max-w-lg mx-auto mt-4 items-center">
        <div class="mb-4">
            <label class="block font-bold w-full px-4 py-3">{{ __('messages.site') }}:<a href="{{ route('sites.show', $payment->site->id) }}" class="px-1 text-orange-500 hover:text-purple-800">{{ $payment->site->slug }}</a></label> 
        </div>
    </div>
</div>