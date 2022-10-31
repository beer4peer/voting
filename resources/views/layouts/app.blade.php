<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

        <title>{{ $title ?? 'B4P - Voting' }}</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <livewire:styles />
    </head>
    <body class="font-sans bg-gray-background text-gray-900 text-sm">

        <header class="max-w-6xl mx-auto px-2 flex flex-row justify-between py-4">
            <a href="/" class="flex flex-row"><img src="{{ asset('img/logo.jpg') }}" alt="logo" class="h-12"></a>
            <div class="flex items-center mt-2 md:mt-0">
                @if (Route::has('login'))
                    <div class="py-4">
                        @auth
                            <div class="flex items-center space-x-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log out') }}
                                    </a>
                                </form>

                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                        @endauth
                    </div>
                @endif
                @auth
                    <div class="w-4"></div>
                    <a href="#">
                        <img src="{{ auth()->user()->getAvatar() }}" alt="avatar" class="w-10 h-10 rounded-full">
                    </a>
                @endauth
            </div>
        </header>

        <main class="max-w-6xl mx-auto flex flex-col md:flex-row">

            <div class="w-full px-2 ">
                <livewire:status-filters />

                <div class="mt-8">
                    {{ $slot }}
                </div>
            </div>
        </main>

        @if (session('success_message'))
            <x-notification-success
                :redirect="true"
                message-to-display="{{ (session('success_message')) }}"
            />
        @endif

        @if (session('error_message'))
            <x-notification-success
                type="error"
                :redirect="true"
                message-to-display="{{ (session('error_message')) }}"
            />
        @endif

        <livewire:scripts />
        <footer class="fixed bottom-0 left-0 z-20 p-4 w-full bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© {{ date('Y') }} <a href="https://theitdept.au/" class="hover:underline">The It Dept</a>. All Rights Reserved.
        </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <a href="https://github.com/beer4peer/voting.beer4peer.au" class="hover:underline">This code is open source on <i class="fa-brands fa-github"></i> GitHub</a>
                </li>
            </ul>
        </footer>
    </body>
</html>
