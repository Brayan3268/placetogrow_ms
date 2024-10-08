<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.fields_pays_sites') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.fields_pays_sites') }}</title>
    </x-slot>

    @section('content')
    <body class="bg-gray-100 p-6">

        <div class="container mx-auto mt-5 flex-col space-y-4 items-center">
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.fields_explanation') }}</h1>
            <br>
    
            <div class="container mx-auto">
                <div class="flex flex-wrap gap-2" id="filtered_constants_opt">
                    @foreach ($filtered_constants_opt as $constant => $description)
                        <div class="item p-2 bg-white border border-gray-300 rounded-md shadow-sm flex items-center justify-between flex-1 min-w-[200px]" data-constant="{{ $constant }}" data-description="{{ $description }}">
                            <div class="flex-1 flex flex-col">
                                <h3 class="text-sm font-semibold truncate">{{ $constant }}</h3>
                                <p class="text-gray-600 text-xs truncate">{{ $description }}</p>
                            </div>
                            <a href="#" class="my-button-sm add-btn">+</a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-900 my-4"></div>
            <br>
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.field_adding') }}</h1>

            <div id="form-container" class="hidden form-container">
                <form id="dynamic-form" method="POST" action="{{ route('sites.add_field') }}" class="max-w-lg mx-auto mt-10">
                    @csrf

                    <input type="hidden" name="site_id" value="{{ $site_id }}" />

                    <div class="mb-6">
                        <label for="name_field" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.name_field') }}:</label>
                        <input type="text" id="name_field" name="name_field" readonly class="bg-gray-200 form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('name_field') border-red-500 @enderror" />
                        @error('name_field')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="name_field_user_see" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.name_for_user_see') }}:</label>
                        <input type="text" id="name_field_user_see" name="name_field_user_see" readonly class="bg-gray-200 form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('name_field_user_see') border-red-500 @enderror" />
                        @error('name_field_user_see')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="select_type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.field_type') }}:</label>
                        <select id="select_type" name="field_type" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('field_type') border-red-500 @enderror" required>
                            <option value="" disabled selected>{{ __('messages.select_an_option') }}</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="select">Select</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="select_is_optional" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.is_optional') }}</label>
                        <select id="select_is_optional" name="is_optional" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('is_optional') border-red-500 @enderror" required>
                            <option value="" disabled selected>{{ __('messages.select_an_option') }}</option>
                            <option value="1">{{ __('messages.yes') }}</option>
                            <option value="0">{{ __('messages.no') }}</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="select_is_modify" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.is_modify') }}</label>
                        <select id="select_is_modify" name="is_modify" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('is_modify') border-red-500 @enderror" required>
                            <option value="" disabled selected>{{ __('messages.select_an_option') }}</option>
                            <option value="1">{{ __('messages.yes') }}</option>
                            <option value="0">{{ __('messages.no') }}</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="select_type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.values_for_this_field') }}:</label>
                        <input type="text" id="values" name="values" class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('values') border-red-500 @enderror">
                        @error('values')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="my-button">{{ __('messages.add_field') }}</button>
                </form>
            </div>

            <div class="border-t border-gray-900 my-4"></div>
            <br>
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.fields_added') }}</h1>
            
            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.name') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.name_for_user_see') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.type') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.is_optional') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.is_modify') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.values') }}</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="sites_fields">
                    @foreach($sites_fields as $sites_fields)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->name }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->name_user_see }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->type }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->is_optional ? 'Yes' : 'No' }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->is_modify ? 'Yes' : 'No' }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->values }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">  
                                @if ($sites_fields->is_mandatory == false)
                                    <form action="{{ route('sites.field_destroy', $sites_fields->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-purple-800"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const itemContainer = document.getElementById('filtered_constants_opt');
    
                itemContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('add-btn')) {
                        event.preventDefault();
    
                        const item = event.target.closest('.item');
    
                        const constant = item.getAttribute('data-constant');
                        const description = item.getAttribute('data-description');
    
                        document.getElementById('name_field').value = `${constant}`;
                        document.getElementById('name_field_user_see').value = `${description}`;
    
                        const formContainer = document.getElementById('form-container');
                        formContainer.classList.remove('hidden');
                        document.getElementById('select_type').classList.remove('hidden');
                        document.getElementById('select_is_optional').classList.remove('hidden');
                        document.getElementById('select_is_modify').classList.remove('hidden');
                        document.getElementById('values').classList.remove('hidden');
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const itemContainer = document.getElementById('filtered_constants_opt');

                itemContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('add-btn')) {
                        event.preventDefault();

                        const item = event.target.closest('.item');
                        const constant = item.getAttribute('data-constant');
                        const description = item.getAttribute('data-description');

                        const formContainer = document.getElementById('form-container');
                        formContainer.classList.remove('hidden');

                        document.getElementById('select_type').value = '';
                        document.getElementById('select_is_optional').value = '';
                        document.getElementById('select_is_modify').value = '';
                        document.getElementById('values').value = '';
                    }
                });

                const form = document.getElementById('dynamic-form');
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    let isValid = true;

                    document.querySelectorAll('.error').forEach(span => span.textContent = '');

                    const fieldType = document.getElementById('select_type').value;
                    if (fieldType === '') {
                        document.getElementById('select_type-error').textContent = 'Field Type is required.';
                        isValid = false;
                    }

                    const isOptional = document.getElementById('select_is_optional').value;
                    if (isOptional === '') {
                        document.getElementById('select_is_optional-error').textContent = 'Optional selection is required.';
                        isValid = false;
                    }

                    if (isValid) {
                        form.submit();
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>