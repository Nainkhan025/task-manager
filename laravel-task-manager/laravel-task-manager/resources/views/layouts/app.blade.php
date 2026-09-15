<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">
    @if (session('status'))
        <div class="max-w-6xl mx-auto mt-4 px-4">
            <div class="bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-6xl mx-auto mt-4 px-4">
            <div class="bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="max-w-6xl mx-auto p-4">
        @yield('content')
    </main>
</body>
</html>
