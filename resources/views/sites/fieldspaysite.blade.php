<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Pay Site Configuration') }}
        </h2>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pay Site Configuration</title>
    </x-slot>

    @section('content')
    <body class="bg-gray-100 p-6">

        <div class="container mx-auto mt-5 flex-col space-y-4 items-center">
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">This is the fields that the site can add to the pay information</h1>
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
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">This is the field that you are adding</h1>

            <div id="form-container" class="hidden form-container">
                <form id="dynamic-form" method="POST" action="{{ route('sites.add_field') }}">
                    @csrf

                    <input type="hidden" name="site_id" value="{{ $site_id }}" />

                    <!-- Section for Constant Name -->
                    <div class="form-section">
                        <input type="text" id="name_field" name="name_field" readonly class="bg-gray-200 border border-gray-300 p-2 rounded-md" />
                    </div>

                    <div class="form-section">
                        <input type="text" id="name_field_useer_see" name="name_field_useer_see" readonly class="bg-gray-200 border border-gray-300 p-2 rounded-md" />
                    </div>

                    <!-- Section for Type Selection -->
                    <div class="form-section">
                        <label for="select_type">Field Type</label>
                        <select id="select_type" name="field_type">
                            <option value="">Select type</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="radio">Radio</option>
                        </select>
                        <span id="select_type-error" class="error"></span>
                    </div>

                    <!-- Section for Optional Selection -->
                    <div class="form-section">
                        <label for="select_is_optional">Is Optional?</label>
                        <select id="select_is_optional" name="is_optional">
                            <option value="">Select Option</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <span id="select_is_optional-error" class="error"></span>
                    </div>

                    <!-- Section for User Visibility Selection -->
                    <div class="form-section">
                        <label for="select_is_user_see">User Can See?</label>
                        <select id="select_is_user_see" name="is_user_see">
                            <option value="">Select Option</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <span id="select_is_user_see-error" class="error"></span>
                    </div>
                
                    <!-- Submit Button -->
                    <button type="submit">Submit</button>
                </form>
            </div>

            <div class="border-t border-gray-900 my-4"></div>
            <br>
            <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">This is the fields that the site requests to the user</h1>
            
            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th scope="col" class="border border-gray-200 px-4 py-2">Name</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Name user see</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Type</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Is optional</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Is user see</th>
                        <th scope="col" class="border border-gray-200 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody id="sites_fields">
                    @foreach($sites_fields as $sites_fields)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->name }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->name_user_see }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->type }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->is_optional ? 'Yes' : 'No' }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $sites_fields->is_user_see ? 'Yes' : 'No' }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-right">                                    
                                <form action="{{ route('sites.field_destroy', $sites_fields->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get the container of items
                const itemContainer = document.getElementById('filtered_constants_opt');
    
                // Add a click event listener to the container
                itemContainer.addEventListener('click', function(event) {
                    // Check if the clicked element is an 'add-btn'
                    if (event.target.classList.contains('add-btn')) {
                        // Prevent the default action of the link
                        event.preventDefault();
    
                        // Get the parent item
                        const item = event.target.closest('.item');
    
                        // Get the constant and description from data attributes
                        const constant = item.getAttribute('data-constant');
                        const description = item.getAttribute('data-description');
    
                        // Update the label text
                        document.getElementById('name_field').value = `${constant}`;
                        document.getElementById('name_field_useer_see').value = `${description}`;
                        
    
                        // Show the form container and elements
                        const formContainer = document.getElementById('form-container');
                        formContainer.classList.remove('hidden');
                        document.getElementById('select_type').classList.remove('hidden');
                        document.getElementById('select_is_optional').classList.remove('hidden');
                        document.getElementById('select_is_user_see').classList.remove('hidden');
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

                    // Optionally set default values for selects
                    document.getElementById('select_type').value = '';
                    document.getElementById('select_is_optional').value = '';
                    document.getElementById('select_is_user_see').value = '';
                }
            });

            // Form validation and submission
            const form = document.getElementById('dynamic-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                let isValid = true;

                // Clear previous errors
                document.querySelectorAll('.error').forEach(span => span.textContent = '');

                // Validate field type
                const fieldType = document.getElementById('select_type').value;
                if (fieldType === '') {
                    document.getElementById('select_type-error').textContent = 'Field Type is required.';
                    isValid = false;
                }

                // Validate is_optional
                const isOptional = document.getElementById('select_is_optional').value;
                if (isOptional === '') {
                    document.getElementById('select_is_optional-error').textContent = 'Optional selection is required.';
                    isValid = false;
                }

                // Validate is_user_see
                const isUserSee = document.getElementById('select_is_user_see').value;
                if (isUserSee === '') {
                    document.getElementById('select_is_user_see-error').textContent = 'User visibility selection is required.';
                    isValid = false;
                }

                if (isValid) {
                    form.submit(); // Submit the form if all fields are valid
                }
            });
        });
        </script>
    @endsection
</x-app-layout>