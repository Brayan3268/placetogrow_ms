<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Form Site') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Form Site</title>
    </x-slot>

    @section('content')
        <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">Complete the form for pay</h1>

            @foreach($sites_fields as $input)
            <div class="form-group">
                <label for="{{ $input['name'] }}">{{ $input['name_user_see'] }}</label>
                @if($input['type'] === 'text')
                    <input type="text" id="{{ $input['name'] }}" name="{{ $input['name'] }}"  @if(!$input['is_optional']) required @endif>
                @elseif($input['type'] === 'number')
                    <input type="number" min="1" id="{{ $input['id'] }}" name="{{ $input['id'] }}"  @if($input['is_optional']) required @endif>
                    @elseif($input['type'] === 'select')
                    <select id="{{ $input['name'] }}" name="{{ $input['name'] }}" >
                        <option value="">Seleccione una opción</option>
                        <option value="opcion1">locale_es</option>
                        <option value="opcion2">Opción 2</option>
                        <option value="opcion3">Opción 3</option>
                    </select>
                @endif
            </div>
        @endforeach
        </div>
    @endsection

</x-app-layout>