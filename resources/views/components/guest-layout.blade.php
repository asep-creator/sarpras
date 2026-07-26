@props(['title' => null])
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $title ?? config('app.name', 'App') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{background:linear-gradient(180deg,#0f172a 0%,#071033 60%)} </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ asset('images/logo_sarpras.png') }}" alt="Logo" class="mx-auto h-28 w-28 sm:h-40 sm:w-40 object-cover rounded-md bg-white" />
                </a>
                @if($title)
                    <h2 class="mt-4 text-center text-2xl font-extrabold text-white">{{ $title }}</h2>
                @endif
            </div>

            <div class="rounded-2xl bg-white/95 shadow-lg px-6 py-6">
                {{ $slot }}
            </div>
            <p class="text-center text-xs text-white/70">&copy; {{ date('Y') }} {{ config('app.name', 'SaranaPrasarana') }}</p>
        </div>
    </div>
</body>
</html>
