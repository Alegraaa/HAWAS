@extends('layouts.app')

@section('content')
<main id="isi">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
    <div>
      <p class="label">Pusat Advokasi & Pengaduan</p>
      <h1>Daftar Laporan Insiden Penipuan</h1>
    </div>
    <a href="{{ route('sos.index') }}" class="btn pri s">← Form Buat Laporan Baru</a>
  </div>
  <p class="lead">Data pengaduan penipuan yang tersimpan. Data ini dapat disalin atau dicetak sebagai bukti kronologi awal untuk pengaduan ke pihak bank atau kepolisian.</p>

  @if(request('sukses'))
    <div style="background:rgba(46,125,50,0.12); border:1.5px solid var(--green); border-radius:6px; padding:12px; margin-bottom:18px; color:var(--green); font-weight:700;">
      ✅ Laporan pengaduan Anda telah berhasil dicatat dan diarsipkan ke dalam sistem.
    </div>
  @endif

  @if(request('dihapus'))
    <div style="background:rgba(168,67,43,0.12); border:1.5px solid var(--rust); border-radius:6px; padding:12px; margin-bottom:18px; color:var(--rust); font-weight:700;">
      🗑️ Laporan telah berhasil dihapus dari arsip.
    </div>
  @endif

  @if($reports->isEmpty())
    <div class="empty">Belum ada laporan pengaduan yang tercatat saat ini.</div>
  @else
    <div style="display:grid; gap:14px;">
      @foreach($reports as $r)
        <div style="background:var(--card); border:1.5px solid var(--line); border-radius:6px; padding:16px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
            <div>
              <span class="label" style="font-size:11px;">ID #{{ $r->id }} · {{ $r->created_at->format('d/m/Y H:i') }}</span>
              <h3 style="margin:4px 0; font-size:17px;">{{ $r->nama_pelapor }}</h3>
            </div>
            <span class="tag {{ $r->status === 'tercatat' ? 'sim' : '' }}" style="font-size:10.5px;">
              {{ strtoupper($r->status) }}
            </span>
          </div>

          <div style="margin:10px 0; display:grid; grid-template-columns:auto 1fr; gap:4px 12px; font-size:14px;">
            <span style="color:var(--mute)">Bank Korban:</span>
            <b>{{ $r->bank_korban }}</b>

            <span style="color:var(--mute)">Data Penipu:</span>
            <b style="color:var(--rust);">{{ $r->kontak_penipu }}</b>

            <span style="color:var(--mute)">Kerugian:</span>
            <b style="color:var(--rust); font-size:16px;">Rp {{ number_format($r->nominal_rugi, 0, ',', '.') }}</b>
          </div>

          <div style="background:var(--bg); border:1px solid var(--line); border-radius:4px; padding:10px; font-size:13.5px; margin-top:10px;">
            <b style="display:block; font-size:11px; text-transform:uppercase; color:var(--mute); margin-bottom:2px;">Kronologi:</b>
            {!! nl2br(e($r->kronologi)) !!}
          </div>

          <div style="margin-top:12px; display:flex; justify-content:space-between; align-items:center;">
            <button class="btn s" type="button" onclick="copyText('{{ addslashes("LAPORAN #{$r->id}\nPelapor: {$r->nama_pelapor}\nBank: {$r->bank_korban}\nPenipu: {$r->kontak_penipu}\nNominal: Rp " . number_format($r->nominal_rugi, 0, ',', '.') . "\nKronologi: {$r->kronologi}") }}', 'Ringkasan laporan disalin!')">
              Salin Detail Laporan
            </button>

            <form method="POST" action="{{ route('sos.hapus-laporan') }}" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
              @csrf
              <input type="hidden" name="id" value="{{ $r->id }}">
              <button class="btn s" type="submit" style="border-color:var(--rust); color:var(--rust);">Hapus</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</main>
@endsection

