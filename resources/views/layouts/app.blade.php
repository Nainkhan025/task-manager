<!DOCTYPE html>
<html lang="en" class="h-full bg-[#fafafa]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager')</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS & SortableJS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        /* Custom scrollbar & details reset */
        details summary::-webkit-details-marker {
            display: none;
        }
        details summary {
            list-style: none;
        }
    </style>
</head>
<body class="bg-[#fafafa] text-zinc-900 antialiased min-h-screen selection:bg-zinc-200 selection:text-zinc-900 flex flex-col md:flex-row">
    @yield('content')

    {{-- Reusable Confirmation Modal --}}
    @include('partials.confirm-dialog')

    {{-- Toast Notification System --}}
    @include('partials.toasts')
</body>
</html>

