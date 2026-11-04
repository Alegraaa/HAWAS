@extends('layouts.app')

@section('content')
<main id="isi">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
    <div>
      <h1 style="font-size:24px; margin:0 0 4px;">Riwayat Pemeriksaan Pesan</h1>
      <p style="margin:0; color:var(--mute); font-size:14.5px;">Daftar riwayat pesan dan tautan mencurigakan yang pernah Anda periksa sebelumnya.</p>
    </div>
    <a href="{{ route('cek.index') }}" class="btn pri s">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      <span>Kembali ke Halaman Cek</span>
    </a>
  </div>

  @if($history->isEmpty())
    <div class="empty">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:8px; opacity:0.6;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      <p style="margin:0;">Belum ada riwayat pengecekan pesan yang tersimpan.</p>
    </div>
  @else
    <div style="display:grid; gap:12px;">
      @foreach($history as $h)
        <div class="history-card" style="flex-direction:column; align-items:flex-start;">
          <div style="display:flex; justify-content:space-between; align-items:center; width:100%; gap:10px;">
            <span style="font-size:12px; color:var(--mute); font-weight:700;">WAKTU: {{ $h->created_at->format('d/m/Y H:i') }} WIB</span>
            <span class="tag {{ $h->tingkat_bahaya === 'BAHAYA TINGGI' ? 'sim' : ($h->tingkat_bahaya === 'AMAN' ? 'safe' : '') }}">
              {{ $h->tingkat_bahaya }}
            </span>
          </div>
          <p style="margin:8px 0; font-size:14.5px; font-weight:600; line-height:1.5; color:var(--ink);">
            "{{ $h->pesan_ringkas }}..."
          </p>
          <div style="font-size:13px; color:var(--mute); border-top:1px solid var(--line); width:100%; padding-top:8px;">
            Jumlah Indikasi: <b>{{ $h->jumlah_indikasi }} temuan</b> &middot; Total Bobot Skor: <b>{{ $h->bobot_skor }}</b>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</main>
@endsection
