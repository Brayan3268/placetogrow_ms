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

        <form id="payForm" method="POST" class="max-w-lg mx-auto mt-10" action="{{ route('payment.store', $site) }}">
          @csrf

          <input type="hidden" name="site_id" value="{{ $site->id }}" />

          @foreach($sites_fields as $input)
            {{ $input['name'] }}
            <div class="mb-6">
              <label for="{{ $input['name'] }}" class="block text-gray-700 text-sm font-bold mb-2">{{ $input['name_user_see'] }}</label>
              @if($input['type'] === 'text')
                <input type="text" id="{{ $input['name'] }}" name="{{ $input['name'] }}" @if($input['is_optional'] == 0) required @endif class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('{{ $input->name }}') border-red-500 @enderror">
              @elseif($input['type'] === 'number')
                <input type="number" min="1" id="{{ $input['name'] }}" name="{{ $input['name'] }}" @if($input['is_optional'] == 0) required @endif class="form-input block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('{{ $input->name }}') border-red-500 @enderror">
              @elseif($input['type'] === 'select')
                @php
                  $options = explode(',', $input->values);
                @endphp
                <select id="{{ $input['name'] }}" name="{{ $input['name'] }}" @if($input['is_optional'] == 0) required @endif class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error(' {{ $input->name }} ') border-red-500 @enderror">
                  <option value="">Select an option</option>
                  @foreach($options as $option)
                    <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          @endforeach
          <button type="submit" id="submitForm" class="my-button">Go to pay</button>
        </form>
      </div>
    @endsection
</x-app-layout>