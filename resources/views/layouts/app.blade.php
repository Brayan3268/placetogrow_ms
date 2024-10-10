<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://cdn.tailwindcss.com"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .menu-icon {
                color: orange;
            }
            .menu-icon:hover {
                color: purple;
            }
        </style>
        @yield('header')

        @stack('css')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @auth
                @if(!is_null(Auth::user()->name))
                    @include('layouts.navigation')
                @endif
            @else
                <p>Please log in.</p>
            @endauth

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page content goes here -->
            <main>
                <!-- Sidebar -->
                <div class="flex min-h-screen">
                <div id="sidebar" class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full transition duration-200 ease-in-out md:relative md:translate-x-0">
                    <a href="#" class="text-white flex items-center space-x-2 px-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18" />
                        </svg>
                        <span class="text-2xl font-extrabold">{{ __('messages.menu') }}</span>
                    </a>    
                    <nav>
                        <a href="{{ route('dashboard.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700 hover:text-white">
                            <svg class="inline-block w-6 h-6 mr-2 text-orange-500 transition duration-200 group-hover:text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 12a5 5 0 110-10 5 5 0 010 10zM7 10h6v1H7v-1z" />
                            </svg>
                            {{ __('messages.dashboard') }}
                        </a>
                        @can(['users_menu.show'])
                        <a href="{{ route('users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700 hover:text-white">
                            <svg class="inline-block w-6 h-6 mr-2 text-orange-500 transition duration-200 group-hover:text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 12a5 5 0 110-10 5 5 0 010 10zM7 10h6v1H7v-1z" />
                            </svg>
                            {{ __('messages.users') }}
                        </a>
                        @endcan
                        <a href="{{ route('sites.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700 hover:text-white">
                            <svg class="inline-block w-6 h-6 mr-2 text-orange-500 transition duration-200 group-hover:text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 12a5 5 0 110-10 5 5 0 010 10zM7 10h6v1H7v-1z" />
                            </svg>
                            {{ __('messages.microsites') }}
                        </a>

                        <a href="{{ route('invoices.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700 hover:text-white">
                            <svg class="inline-block w-6 h-6 mr-2 text-orange-500 transition duration-200 group-hover:text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 12a5 5 0 110-10 5 5 0 010 10zM7 10h6v1H7v-1z" />
                            </svg>
                            {{ __('messages.invoices') }}
                            
                        </a>

                        <a href="{{ route('payment.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700 hover:text-white">
                            <svg class="inline-block w-6 h-6 mr-2 text-orange-500 transition duration-200 group-hover:text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 12a5 5 0 110-10 5 5 0 010 10zM7 10h6v1H7v-1z" />
                            </svg>
                            {{ __('messages.pays') }}
                        </a>

                        <a href="{{ route('suscriptions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-700 hover:text-white">
                            <svg class="inline-block w-6 h-6 mr-2 text-orange-500 transition duration-200 group-hover:text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 12a5 5 0 110-10 5 5 0 010 10zM7 10h6v1H7v-1z" />
                            </svg>
                            {{ __('messages.suscription') }}
                        </a>
                    </nav>
                </div>

                @yield('content')
 
                <!-- Content -->
                <button id="close-sidebar" class="text-white absolute top-0 right-0 mt-2 mr-3 md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <button id="menu-toggle" class="bg-gray-800 text-white p-2 rounded-md focus:outline-none md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </main>
        </div>
    </body>    
</html>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('-translate-x-full');
        console.log("hola")
    });
</script>