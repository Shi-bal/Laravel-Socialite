<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">
        <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css"/>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>Login</title>
    </head>

<body>


    <div class="flex flex-col w-full md:w-1/2 xl:w-2/5 mx-auto p-8 md:p-10 mt-20 rounded-2xl shadow-xl">
        <div class="flex justify-center pb-4">
            <img class="invert" src="{{ asset('logos/uptrendnobg.png') }}" width="50" alt="Logo">
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="flex flex-col">
            @csrf

            <!-- Email Input -->
            <div class="pb-2">
                <label for="email" class="block mb-2 text-sm font-medium text-[#000000]">Email</label>
                <div class="relative text-gray-400">
                    <span class="absolute inset-y-0 left-0 flex items-center p-1 pl-3">
                        <i class="ph-bold ph-envelope-simple"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="pl-12 mb-2 bg-gray-50 text-gray-600 border border-gray-300 rounded-lg block w-full p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-400" placeholder="Email Address" autocomplete="username">
                </div>
            </div>

            <!-- Password Input -->
            <div class="pb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-[#000000]">Password</label>
                <div class="relative text-gray-400">
                    <span class="absolute inset-y-0 left-0 flex items-center p-1 pl-3">
                        <i class="ph-bold ph-key"></i>
                    </span>
                    <input type="password" id="password" name="password" required class="pl-12 mb-2 bg-gray-50 text-gray-600 border border-gray-300 rounded-lg block w-full p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-400" placeholder="••••••••••" autocomplete="current-password">
                </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center mb-4">
                <input type="checkbox" id="remember_me" name="remember" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-600">Remember me</label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full text-white bg-[#000000] rounded-lg text-sm px-5 py-2.5 text-center mb-6 focus:ring-4 focus:ring-primary-300">Login</button>

            <!-- Fregister -->
            @if (Route::has('password.request'))
                <div class="flex justify-end">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                        don't have account yet?
                    </a>
                </div>
            @endif
        </form>

        <!-- OR Divider -->
        <div class="relative flex py-8 items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink mx-4 font-medium text-gray-500">OR</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Social Login Links -->
        <div class="flex gap-2 justify-center">
            <a href="{{ route('google-auth') }}" class="flex items-center gap-2 bg-black p-2 rounded-md text-white text-sm py-2.5 px-4">
                <i class="ph-bold ph-google-logo"></i>
                <span class="rounded-lg text-center focus:ring-4 focus:ring-primary-300">Google</span>
            </a>
        </div>

    </div>
</body>
</html>
