<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mobile Order')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-text antialiased">
    <div class="flex min-h-screen flex-col">
        <header class="bg-accent text-white">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-4">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide text-white">Mobile Order</a>
                <nav class="flex items-center gap-3 text-sm font-medium">
                    <a href="{{ route('mobile-order') }}" class="rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white">受注登録</a>
                    <a href="{{ route('orders') }}" class="rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white">受注一覧</a>
                    <a href="{{ route('products') }}" class="rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white">商品マスタ</a>
                    <a href="{{ route('customers') }}" class="rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white">顧客マスタ</a>
                    <a href="{{ route('settings') }}" class="rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white">設定</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto flex w-full max-w-6xl flex-1 flex-col px-6 py-10">
            <h1 class="text-2xl font-semibold text-accent">@yield('page-title')</h1>
            <div class="mt-6 flex-1">@yield('content')</div>
        </main>

        <footer class="bg-black/5">
            <div class="mx-auto w-full max-w-6xl px-6 py-4 text-sm text-black/60">
                &copy; {{ date('Y') }} Mobile Order Mock
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
