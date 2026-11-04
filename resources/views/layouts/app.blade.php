<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'HAWAS — Literasi Keamanan Transaksi Digital' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('assets/css/hawas.css') }}?v={{ file_exists(public_path('assets/css/hawas.css')) ? filemtime(public_path('assets/css/hawas.css')) : '2.0' }}">
</head>
<body>

<svg class="defs" aria-hidden="true" focusable="false">
  <defs>
    <g id="half" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
      <path d="M60 13C69 13 70 5 78 6.5C86 8 84 17 77.5 16C73 15 74 10.5 77 11.5"/>
      <path d="M64 13C72 20 82 22 91 18C98 14.5 96 8.5 101 8.5C107 8.5 108 15 104 15C101 15 101 12 103 12.3"/>
      <path d="M60 4L65 13L60 22L55 13Z"/>
      <circle cx="60" cy="13" r="1.4"/>
    </g>
    <g id="m"><use href="#half"/><use href="#half" transform="translate(120 0) scale(-1 1)"/></g>
    <pattern id="pg" width="120" height="26" patternUnits="userSpaceOnUse"><use href="#m" stroke="#D9AE45"/></pattern>
    <pattern id="pn" width="120" height="26" patternUnits="userSpaceOnUse"><use href="#m" style="stroke:var(--orn)"/></pattern>

    <!-- Ornamen Tradisional Lengkap untuk Lambang HAWAS -->
    <g id="eh" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M50 66C78 74 76 100 56 104"/><path d="M50 154C78 146 76 120 56 116"/>
      <path d="M70 40C84 40 86 56 76 56C70 56 70 49 74 49"/><path d="M70 180C84 180 86 164 76 164C70 164 70 171 74 171"/>
      <path d="M88 88C98 100 98 120 88 132"/><path d="M50 22L56 42L50 62L44 42Z"/>
    </g>
    <symbol id="emb" viewBox="0 0 100 220">
      <path d="M50 4L96 50V170L50 216L4 170V50Z" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linejoin="round"/>
      <g stroke="currentColor">
        <use href="#eh"/>
        <use href="#eh" transform="translate(100 0) scale(-1 1)"/>
        <circle cx="50" cy="110" r="11" fill="none" stroke-width="2.8"/>
        <circle cx="50" cy="110" r="4" fill="currentColor"/>
        <path d="M50 178L56 198L50 214L44 198Z" fill="none" stroke-width="2.2"/>
      </g>
    </symbol>
    <symbol id="logo" viewBox="0 0 100 220"><use href="#emb"/></symbol>
  </defs>
</svg>

<header>
  <div class="top">
    <a class="brand" href="{{ route('home') }}">
      <svg width="22" height="46" viewBox="0 0 100 220" aria-hidden="true" style="color:var(--gold)"><use href="#logo"/></svg>
      <div>
        <b>HAWAS</b>
        <small>Hati-Hati Awasi Transaksi Digital</small>
      </div>
    </a>
    <nav class="dnav" aria-label="Navigasi Utama">
      <a href="{{ route('home') }}" {{ ($activePage ?? '') === 'home' ? 'aria-current=page' : '' }}>Beranda</a>
      <a href="{{ route('simulasi.index') }}" {{ ($activePage ?? '') === 'simulasi' ? 'aria-current=page' : '' }}>Simulasi</a>
      <a href="{{ route('cek.index') }}" {{ ($activePage ?? '') === 'cek' ? 'aria-current=page' : '' }}>Cek</a>
      <a href="{{ route('qris.index') }}" {{ ($activePage ?? '') === 'qris' ? 'aria-current=page' : '' }}>QRIS</a>
      <a href="{{ route('belajar.index') }}" {{ ($activePage ?? '') === 'belajar' ? 'aria-current=page' : '' }}>Belajar</a>
      <a href="{{ route('sos.index') }}" class="sos" {{ ($activePage ?? '') === 'sos' ? 'aria-current=page' : '' }}>SOS</a>
    </nav>
    <div class="header-actions">
      <button class="theme-toggle" type="button" aria-label="Ganti Tema" title="Ganti Mode Gelap/Terang">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
      </button>
    </div>
  </div>
  <svg class="band" aria-hidden="true" height="26"><rect width="100%" height="26" fill="url(#pg)"/></svg>
</header>

@yield('content')

<footer>
  <p><b>HAWAS (Hati-Hati Awasi Transaksi)</b> &middot; Platform Literasi Keamanan Transaksi Digital Indonesia</p>
  <p style="margin: 4px 0 0; opacity: 0.85;">Edukasi pencegahan penipuan perbankan, dompet digital, dan rekayasa sosial.</p>
</footer>

<nav class="bnav" aria-label="Navigasi Bawah">
  <a href="{{ route('home') }}" {{ ($activePage ?? '') === 'home' ? 'aria-current=page' : '' }}>
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M4 11l8-7 8 7v9H4z"/></svg>
    Beranda
  </a>
  <a href="{{ route('simulasi.index') }}" {{ ($activePage ?? '') === 'simulasi' ? 'aria-current=page' : '' }}>
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M11 18.5h2"/></svg>
    Simulasi
  </a>
  <a href="{{ route('cek.index') }}" {{ ($activePage ?? '') === 'cek' ? 'aria-current=page' : '' }}>
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l5 5"/></svg>
    Cek
  </a>
  <a href="{{ route('qris.index') }}" {{ ($activePage ?? '') === 'qris' ? 'aria-current=page' : '' }}>
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="3.5" y="3.5" width="7" height="7"/><rect x="13.5" y="3.5" width="7" height="7"/><rect x="3.5" y="13.5" width="7" height="7"/><path d="M14 14h2.5v2.5H14zM18 18h2.5v2.5H18z"/></svg>
    QRIS
  </a>
  <a href="{{ route('belajar.index') }}" {{ ($activePage ?? '') === 'belajar' ? 'aria-current=page' : '' }}>
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M4 5h6a2 2 0 0 1 2 2v13a2 2 0 0 0-2-2H4zM20 5h-6a2 2 0 0 0-2 2v13a2 2 0 0 1 2-2h6z"/></svg>
    Belajar
  </a>
  <a href="{{ route('sos.index') }}" class="sos" {{ ($activePage ?? '') === 'sos' ? 'aria-current=page' : '' }}>
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l9 16H3z"/><path d="M12 10v4M12 17v.5"/></svg>
    SOS
  </a>
</nav>

<script src="{{ asset('assets/js/hawas.js') }}"></script>
@yield('scripts')
</body>
</html>

