@extends('layouts.app')

@section('content')
<main id="isi">
  <div class="hero-grid">
    <section class="hero" aria-labelledby="jdl">
      <div class="htop">
        <p class="label">Pusat Edukasi & Perlindungan Transaksi Digital</p>
        <svg class="emblem" aria-hidden="true" style="display:none;"><use href="#emb"/></svg>
      </div>
      <h1 id="jdl">Latihan mengenali risiko transaksi digital, tanpa memakai uang atau data nyata.</h1>
      <p class="lead">Tingkatkan intuisi dan kewaspadaan Anda menghadapi berbagai modus kejahatan siber finansial: kiriman file APK kurir palsu, tautan phising berkedok undian, panggilan telepon manipulatif, hingga manipulasi kode QRIS.</p>
      
      <div class="btns">
        <a class="btn pri" href="{{ route('simulasi.index') }}">
          Mulai Simulasi Latihan
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a class="btn sec" href="{{ route('cek.index') }}">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
          Periksa Pesan / Tautan
        </a>
      </div>
    </section>

    <!-- Panel Informasi & Statistik Literasi -->
    <div style="background:var(--card); border:1.5px solid var(--ink); border-radius:8px; padding:22px; box-shadow:0 4px 0 var(--gold);">
      <p class="label" style="display:flex; justify-content:space-between; align-items:center;">
        <span>Statistik Modul</span>
        <span class="tag" style="font-size:10px;">EDUKASI AKTIF</span>
      </p>
      <h3 style="font-size:18px; margin:4px 0 16px;">Siap Melindungi Transaksi Anda:</h3>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:14px;">
        <div style="background:var(--bg); padding:12px; border-radius:6px; border:1px solid var(--line);">
          <span style="font-size:26px; font-weight:800; display:block; color:var(--navy);">{{ $stats['total_skenario'] }}</span>
          <span style="color:var(--mute); font-size:12.5px;">Skenario Latihan Nyata</span>
        </div>
        <div style="background:var(--bg); padding:12px; border-radius:6px; border:1px solid var(--line);">
          <span style="font-size:26px; font-weight:800; display:block; color:var(--navy);">{{ $stats['total_pola'] }}</span>
          <span style="color:var(--mute); font-size:12.5px;">Pola Ancaman Terdata</span>
        </div>
        <div style="background:var(--bg); padding:12px; border-radius:6px; border:1px solid var(--line);">
          <span style="font-size:26px; font-weight:800; display:block; color:var(--navy);">{{ $stats['total_merchant'] }}</span>
          <span style="color:var(--mute); font-size:12.5px;">Database Merchant Resmi</span>
        </div>
        <div style="background:var(--bg); padding:12px; border-radius:6px; border:1px solid var(--line);">
          <span style="font-size:26px; font-weight:800; display:block; color:var(--rust);">{{ $stats['total_laporan'] }}</span>
          <span style="color:var(--mute); font-size:12.5px;">Laporan Terverifikasi</span>
        </div>
      </div>

      <div style="margin-top:16px; text-align:center;">
        <a href="{{ route('sos.daftar-laporan') }}" style="font-size:13.5px; font-weight:700; color:var(--rust); text-decoration:underline;">Lihat Arsip Laporan Warga →</a>
      </div>
    </div>
  </div>

  <section class="sect" aria-labelledby="modul-jdl" style="margin-top:40px;">
    <p class="label">Pilihan Layanan</p>
    <h2 id="modul-jdl">Fitur Utama Platform HAWAS</h2>
    <p class="sub">Gunakan fitur berikut untuk melindungi diri Anda dan keluarga saat bertransaksi online:</p>

    <ol class="cards grid-2">
      <li>
        <a class="card" href="{{ route('simulasi.index') }}">
          <span class="ic" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M11 18.5h2"/></svg>
          </span>
          <div>
            <div class="n">Modul 1</div>
            <h3>Simulasi Transaksi Berisiko</h3>
            <p>Uji kepekaan menghadapi 3 skenario nyata: malware APK kurir, panggilan darurat palsu, dan transfer rekening mencurigakan.</p>
          </div>
        </a>
      </li>

      <li>
        <a class="card" href="{{ route('cek.index') }}">
          <span class="ic" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l5 5"/></svg>
          </span>
          <div>
            <div class="n">Modul 2</div>
            <h3>Detektor Pesan & Tautan Phishing</h3>
            <p>Periksa teks SMS, WhatsApp, atau tautan pendek yang mencurigakan sebelum Anda terlanjur mengklik.</p>
          </div>
        </a>
      </li>

      <li>
        <a class="card" href="{{ route('qris.index') }}">
          <span class="ic" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="3.5" y="3.5" width="7" height="7"/><rect x="13.5" y="3.5" width="7" height="7"/><rect x="3.5" y="13.5" width="7" height="7"/><path d="M14 14h2.5v2.5H14zM18 18h2.5v2.5H18z"/></svg>
          </span>
          <div>
            <div class="n">Modul 3</div>
            <h3>Validasi Keaslian QRIS</h3>
            <p>Pindai barcode QRIS pedagang untuk memastikan nama toko tujuan sesuai dan mencegah salah transfer ke rekening penipu.</p>
          </div>
        </a>
      </li>

      <li>
        <a class="card" href="{{ route('belajar.index') }}">
          <span class="ic" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M4 5h6a2 2 0 0 1 2 2v13a2 2 0 0 0-2-2H4zM20 5h-6a2 2 0 0 0-2 2v13a2 2 0 0 1 2-2h6z"/></svg>
          </span>
          <div>
            <div class="n">Modul 4</div>
            <h3>3 Prinsip Kebiasaan Aman & Kuis</h3>
            <p>Pelajari panduan praktis menjaga kode OTP, kerahasiaan PIN ATM, dan uji pemahaman lewat kuis interaktif.</p>
          </div>
        </a>
      </li>

      <li style="grid-column: 1 / -1;">
        <a class="card" href="{{ route('sos.index') }}" style="border-color: var(--rust); background: rgba(168, 67, 43, 0.06);">
          <span class="ic" aria-hidden="true" style="border-color: var(--rust); color: var(--rust);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l9 16H3z"/><path d="M12 10v4M12 17v.5"/></svg>
          </span>
          <div>
            <div class="n" style="color:var(--rust)">Layanan Bantuan Cepat</div>
            <h3 style="color:var(--rust)">Tanggap Darurat (SOS) & Buat Laporan Pengaduan</h3>
            <p>Daftar nomor kontak resmi pemblokiran rekening bank, kontak aduan regulator, dan formulir pencatatan kronologi penipuan.</p>
          </div>
        </a>
      </li>
    </ol>
  </section>
</main>
@endsection
