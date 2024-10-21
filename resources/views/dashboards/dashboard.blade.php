<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    @section('content')
        @can(['users.index'])
            <div class="container mx-auto mt-5  flex-col space-y-4 items-center">
                <h1 class="text-2xl font-bold mb-4 flex flex-col items-center">{{ __('messages.selec_site') }}</h1>
            
                <form action="{{ route('dashboard.show_site') }}" method="GET" class="max-w-lg mx-auto mt-5" enctype="multipart/form-data">
                    @csrf
                    @method('GET')

                    <div class="mb-6">
                        <label for="site_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.sites') }}:</label>
                        <select id="site_id" name="site_id" class="form-select block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:shadow-outline-blue @error('site_id') border-red-500 @enderror" required>
                            <option value="" disabled selected>{{ __('messages.select_site') }}</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->slug }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="my-button">{{ __('messages.choose_site') }}</button>
                </form>
            </div>        

            @if (session('status'))
                <div class="container mx-auto mt-5 flex flex-col space-y-4 items-center">
                    <div class="alert {{ session('class') }} text-white p-4 rounded w-1/2 mb-4 text-center">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
        @endcan
    @endsection
</x-app-layout>
