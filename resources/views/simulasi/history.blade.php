@extends('layouts.app')

@section('content')
<main id="isi">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
    <div>
      <h1 style="font-size:24px; margin:0 0 4px;">Riwayat Latihan Simulasi</h1>
      <p style="margin:0; color:var(--mute); font-size:14.5px;">Daftar catatan hasil latihan dan evaluasi keamanan transaksi yang pernah Anda selesaikan.</p>
    </div>
    <a href="{{ route('simulasi.index') }}" class="btn pri s">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      <span>Kembali ke Simulasi</span>
    </a>
  </div>

  @if($history->isEmpty())
    <div class="empty">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:8px; opacity:0.6;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      <p style="margin:0;">Belum ada riwayat latihan yang tercatat. Selesaikan simulasi latihan untuk melihat hasil tersimpan di sini.</p>
    </div>
  @else
    <div style="display:grid; gap:12px;">
      @foreach($history as $h)
        <div class="history-card">
          <div>
            <div style="font-size:12px; color:var(--mute); font-weight:700; letter-spacing:0.04em;">
              WAKTU: {{ $h->created_at->format('d/m/Y H:i') }} WIB
            </div>
            <div style="font-size:18px; font-weight:800; margin:3px 0 2px;">
              Skor: <span style="color:var(--navy);">{{ $h->skor }} Poin</span>
            </div>
            <div style="font-size:13.5px; color:var(--mute);">
              Sisa Saldo Fiktif: <b>Rp {{ number_format($h->saldo_akhir, 0, ',', '.') }}</b>
            </div>
          </div>
          <span class="tag {{ $h->skor >= 30 ? 'safe' : 'sim' }}">
            {{ $h->skor >= 30 ? '✓ LULUS AMAN' : '⚠ PERLU WASPADA' }}
          </span>
        </div>
      @endforeach
    </div>
  @endif
</main>
@endsection
