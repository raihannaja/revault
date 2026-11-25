<!DOCTYPE html>
{{-- VIEW (layout): kerangka HTML yang dipakai semua halaman. Blade: @yield mengisi bagian yang berbeda. --}}
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'Program Management') · Revault</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">
    {{-- Tema yang sama dengan website Revault (Tailwind CDN + warna forest/sun/ink/mist) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            DEFAULT: '#1f4f46',
                            dark: '#143830',
                            soft: '#e4eeea'
                        },
                        sun: '#e8a33d',
                        ink: '#14231f',
                        mist: '#f3f6f4'
                    },
                    fontFamily: {
                        display: ['"Bricolage Grotesque"', 'sans-serif'],
                        sans: ['"DM Sans"', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-mist font-sans text-ink antialiased min-h-screen flex flex-col">

    <header class="bg-forest text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('program.index') }}" class="flex items-center gap-3">
                <img src="{{ asset('Assets/logo.png') }}" alt="Logo Revault"
                    class="h-9 w-9 rounded-lg bg-white object-contain">
                <span>
                    <span class="block font-display text-xl font-bold leading-none">Revault</span>
                    <span class="block text-xs text-white/70 mt-1">Give Things Another Life</span>
                </span>
            </a>
            <nav class="flex items-center gap-5 text-sm">
                <span class="hidden sm:inline text-white/80">Program Management</span>
                {{-- Tautan balik ke website Revault. Alamat ini untuk Live Server (port 5500);
                jika Anda membukanya dengan cara lain, ganti alamatnya. --}}
                <a href="http://127.0.0.1:5500/index.html"
                    class="rounded-lg bg-white/10 px-3 py-1.5 font-medium hover:bg-white/20 transition">&larr; Website
                    Revault</a>
            </nav>
        </div>
    </header>

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        @yield('isi')
    </main>

    <footer class="border-t border-ink/10 py-6 text-center text-sm text-ink/60">
        Revault · Pertemuan 9 · Laravel + Database + CRUD
    </footer>

    @stack('script')
</body>

</html>
