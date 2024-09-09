<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 mt-3">
                <form action="{{ route('profile.show') }}" method="POST" class="max-w-lg mx-auto mt-5">
                    @csrf
                    @method('GET')
                    <button type="submit" class="my-button">My profile</i></button>
                </form>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" class="max-w-lg mx-auto mt-5 ml-5">
                    @csrf

                    <button type="submit" class="my-button" onclick="event.preventDefault();
                                        this.closest('form').submit();">Log out</i></button>
                </form>
            </div>
        </div>
    </div>
</nav>
