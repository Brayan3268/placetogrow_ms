<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.view_site') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <style>
        .card {
            width: 300px; /* Ancho deseado de la tarjeta */
            margin: 0 auto; /* Centrar horizontalmente */
            background-color: #f0f0f0; /* Color de fondo */
            padding: 10px; /* Espacio interno */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra */
        }

        .card-header {
            background-color: #f2750f; /* Color de fondo del encabezado */
            color: white; /* Color del texto */
            padding: 8px; /* Espacio interno */
            border-top-left-radius: 8px; /* Bordes redondeados */
            border-top-right-radius: 8px; /* Bordes redondeados */
        }

        .card-body {
            padding: 10px; /* Espacio interno */
        }

        .card-footer {
            background-color: #f0f0f0; /* Color de fondo del pie de página */
            padding: 8px; /* Espacio interno */
            border-bottom-left-radius: 8px; /* Bordes redondeados */
            border-bottom-right-radius: 8px; /* Bordes redondeados */
        }

        .list-group-item {
            border: none; /* Sin borde en los elementos de la lista */
            padding: 6px 10px; /* Espacio interno */
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.view_site') }}</title>
    </x-slot>

    @section('content')
        <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.site_information') }}</h1>
            <form action="{{ route('sites.edit', $site->id) }}" method="POST" class="max-w-lg mx-auto mt-5">
                @csrf
                @method('GET')

                <div class="mb-6 mt-6">
                    @if ($site->image)
                        <img src= {{ URL::asset($site->image) }} class="img-responsive" alt="Perfil de {{ $site->name }}"
                        height="200" width="200">
                    @else
                        <p>{{ __('messages.not_logo') }}</p>
                    @endif
                </div>

                <div class="mb-6">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.name') }}:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $site->name) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('name') border-red-500 @enderror" requiered disabled>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @can('site.manage')
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.slug') }}:</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $site->slug) }}" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('slug') border-red-500 @enderror" requiered disabled>
                        @error('slug')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="expiration_time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.expiration_time') }}:</label>
                        <input type="number" id="expiration_time" name="expiration_time" placeholder="Enter a expiration time greater than 10" value="{{ old('expiration_time', $site->expiration_time) }}" min="10" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('expiration_time') border-red-500 @enderror" requiered disabled>
                        @error('expiration_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endcan

                <div class="mb-6">
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.category') }}:</label>
                    <select id="category" name="category" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('Category') border-red-500 @enderror" requiered disabled>
                        <option value="" disabled selected>{{ old('category', $site->category->name) }}</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.currency') }}:</label>
                    <select id="currency" name="currency" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('role') border-red-500 @enderror" requiered disabled>
                        <option value="" disabled selected>{{ old('category', $site->currency_type) }}</option>
                    </select>
                </div>

                @can('site.manage')
                    <div class="mb-6">
                        <label for="site_type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.site_type') }}:</label>
                        <select id="site_type" name="site_type" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('role') border-red-500 @enderror" requiered disabled>
                            <option value="" disabled selected>{{ old('category', $site->site_type) }}</option>
                        </select>
                    </div>

                    <button type="submit" class="my-button"><i class="fas fa-edit"></i></button>
                @endcan
            </form>

            @can('site.pay')
                <div class="border-t border-gray-900 my-4"></div>
                <br>
                @if ($site->site_type == "OPEN")
                    <form action="{{ route('sites.form_site', $site) }}" method="POST" class="max-w-lg mx-auto mt-5">
                        @csrf
                        @method('GET')
                                <button type="submit" class="my-button"><i class="fas fa-dollar"></i></button>
                    </form>
                @endif
            @endcan
            @if ($site->site_type == "CLOSE" && !$pay_exist)
                @php
                    $columns = 5;
                @endphp
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.reference') }}</th>
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.status') }}</th>
                            @can('site.pay')
                                <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.amount') }}</th>
                                <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.date_expiration') }}</th>
                            @endcan
                            @can('invoices.see_admins_users')
                                <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.users') }}</th>
                            @endcan
                            <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="invoices">
                        @forelse($invoices as $invoice)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $invoice->reference }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $invoice->status }}</td>
                                @can('site.pay')
                                    <td class="border border-gray-200 px-4 py-2">{{ $invoice->amount }}</td>
                                    <td class="border border-gray-200 px-4 py-2">{{ $invoice->date_expiration }}</td>
                                @endcan
                                @can('invoices.see_admins_users')
                                    <td class="border border-gray-200 px-4 py-2 text-orange-500 hover:text-purple-800 mr-2">
                                        <a href="{{ route('show.user', ['id' => $invoice->user->id]) }}">{{ $invoice->user->document }}</a>
                                    </td>
                                @endcan
                                <td class="border border-gray-200 px-4 py-2 text-right">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-purple-800 mr-2"><i class="fas fa-eye"></i></a>
                                    @can('site.pay')
                                        <a href="{{ route('sites.form_site_invoices', $invoice->id) }}" class="text-orange-500 hover:text-purple-800 mr-2"><i class="fas fa-dollar"></i></a>
                                    @endcan
                                    @can('invoices.see_admins_users')
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-yellow-600 hover:text-purple-800 mr-2"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-purple-800"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $columns }}" class="border border-gray-200 px-4 py-2 text-center">{{ __('messages.no_invoices_available') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                @if ($pay_exist)
                <div id="alert-additional-content-1" class="items-center p-4 mb-4 text-orange-800 border border-orange-300 rounded-lg bg-orange-50 dark:bg-gray-800 dark:text-orange-400 dark:border-orange-800" role="alert">
                    <div class="flex items-center">
                      <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                      </svg>
                      <span class="sr-only">{{ __('messages.information') }}</span>
                      <h3 class="text-lg font-medium">{{ __('messages.session_in_progress') }}</h3>
                    </div>
                    <div class="mt-2 mb-4 text-sm">
                        <ul>
                            <li>{{ __('messages.pay_reference') }}: {{ $pay->reference }}</li>
                            <li>{{ __('messages.pay_amount') }}: {{ $pay->amount }}</li>
                            <li>{{ __('messages.pay_currency') }}: {{ $pay->currency }}</li>
                            <li>{{ __('messages.pay_status') }}: {{ $pay->status }}</li>
                            <li>{{ __('messages.pay_url_session') }}: {{ $pay->url_session }}</li>
                        </ul>
                    </div>
                    <div class="flex">
                      <a href="{{ route('sites.finish_session', ['value' => $pay->id]) }}" class="text-white bg-orange-800 hover:bg-orange-900 focus:ring-4 focus:outline-none focus:ring-orange-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                        <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                          <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                        </svg>
                        {{ __('messages.go_to_session') }}
                      <a href="{{ route('sites.lose_session', ['value' => $pay->id]) }}"  data-dismiss-target="#alert-additional-content-1" aria-label="Close">
                        {{ __('messages.lose_session') }}
                      </button>
                    </div>
                  </div>
                @endif
            @endif
            @if ($site->site_type == "SUSCRIPTION")
                @can('suscription.manage')
                    <h1 class="text-2xl font-bold mb-2">{{ __('messages.all_plans') }}</h1>
                @endcan
                @can('suscriptions_users.index')
                    <h1 class="text-2xl font-bold mb-2">{{ __('messages.plans_can_get') }}</h1>
                @endcan
                <div class="container mx-auto">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($suscription_plans as $suscription_plan)
                            @php
                                $items = explode('.', $suscription_plan->description);
                            @endphp
                            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ $suscription_plan->name }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach ($items as $item)
                                                <li class="list-group-item">{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="border-t border-gray-900 my-4"></div>
                                    <div class="card-footer">
                                        @can('suscriptions.user_get_suscription')
                                            <form action="{{ route('user_suscriptions.store', $suscription_plan->id) }}" method="POST" class="text-orange-600 hover:purple-yellow-800 mr-2" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="suscription_id" value="{{ $suscription_plan->id }}" />
                                                <button type="submit" class="text-orange-600 hover:purple-yellow-800 mr-2">
                                                    <i class="fa-solid fa-circle-plus"></i>
                                                </button>
                                            </form>    
                                        @endcan
                                        @can('suscription.destroy')
                                            <form action="{{ route('suscriptions.destroy', $suscription_plan->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-orange-500 hover:text-purple-800 mr-2"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @can('suscriptions_users.index')
                    <br>
                    <div class="border-t border-gray-900 my-4"></div>
                    <br>
                    <h1 class="text-2xl font-bold mb-2">{{ __('messages.my_plans') }}</h1>
                    <div class="container mx-auto">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user_plans_get_suscribe as $user_plan_get_suscribe)
                                @php
                                    $items = explode('.', $user_plan_get_suscribe->suscription->description);
                                @endphp
                                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/4 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ $user_plan_get_suscribe->suscription->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group">
                                                @foreach ($items as $item)
                                                    <li class="list-group-item">{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="border-t border-gray-900 my-4"></div>
                                        <div class="card-footer">
                                            @can('suscriptions.user_get_suscription')
                                                <form action="{{ route('user_suscriptions.destroyy', ['reference' => $user_plan_get_suscribe->reference, 'user_id' => $user_plan_get_suscribe->user_id]) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-orange-500 hover:text-purple-800"><i class="fa-solid fa-circle-minus"></i></button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endcan
            @endif
        </div>
    </div>
    <br>
</div>
@endsection
</x-app-layout>