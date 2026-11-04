@extends('layouts.app')

@section('content')
<main id="isi">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
    <div>
      <p class="label">Modul 2 &middot; Detektor Pesan Mencurigakan</p>
      <h1>Periksa Pola Pesan & Tautan</h1>
    </div>
    <a href="{{ route('cek.riwayat') }}" class="btn s" style="font-size:12.5px;">Lihat Riwayat Pemeriksaan</a>
  </div>
  <p class="lead">Tempelkan isi chat WhatsApp, SMS, atau tautan yang Anda terima. Sistem akan menganalisis indikasi penipuan, shortlink tersembunyi, dan file malware APK secara otomatis.</p>

  <div class="warn" role="note">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l9 16H3z"/><path d="M12 10v4M12 17v.5"/></svg>
    <span><b>Peringatan Keamanan:</b> JANGAN pernah mengetik kode OTP, nomor PIN ATM, CVV kartu kredit, atau password akun Anda!</span>
  </div>

  <label class="f" for="in">Pesan atau alamat tautan (URL) yang ingin diperiksa:</label>
  <textarea id="in" placeholder="Contoh: Salin pesan WhatsApp/SMS yang Anda terima di sini..."></textarea>
  <div class="meta">
    <span id="cc">0 karakter</span>
    <span>Deteksi Otomatis & Terenkripsi</span>
  </div>
  <div class="err" id="err" role="alert" hidden style="color:var(--rust); margin-top:8px; font-weight:700;"></div>

  <p class="label" style="margin-top:14px;">Atau coba contoh pesan simulasi:</p>
  <div class="preset-chips" style="display:flex; flex-wrap:wrap; gap:6px; margin:8px 0 16px;">
    <button class="chip" type="button" data-demo="apk" style="padding:6px 12px; border-radius:20px; font-size:12.5px; cursor:pointer;">📦 Modus Kurir Kirim APK</button>
    <button class="chip" type="button" data-demo="undian" style="padding:6px 12px; border-radius:20px; font-size:12.5px; cursor:pointer;">🎁 Undian Berhadiah 50jt + Shortlink</button>
    <button class="chip" type="button" data-demo="blokir" style="padding:6px 12px; border-radius:20px; font-size:12.5px; cursor:pointer;">⚠️ Ancaman Akun Diblokir Minta OTP</button>
    <button class="chip" type="button" data-demo="aman" style="padding:6px 12px; border-radius:20px; font-size:12.5px; cursor:pointer;">✅ Notifikasi Resmi Bank (Aman)</button>
  </div>

  <div class="acts">
    <button class="btn pri" id="btn-run" type="button">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
      Periksa Pesan Sekarang
    </button>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
      <button class="btn s" id="btn-copy" type="button">Salin Hasil Analisis</button>
      <button class="btn s" id="btn-clear" type="button">Hapus Teks</button>
    </div>
  </div>

  <section id="out" aria-live="polite" style="margin-top:24px;">
    <div class="empty">Hasil pemeriksaan pola akan muncul di sini setelah Anda mengklik tombol "Periksa Pesan Sekarang".</div>
  </section>
</main>
@endsection

@section('scripts')
<script>
var ANALYZE_URL = '{{ route("cek.analyze") }}';
var CSRF_TOKEN = '{{ csrf_token() }}';

var $ = function(i) { return document.getElementById(i); },
    inp = $('in'), out = $('out'), err = $('err'), cc = $('cc');

inp.addEventListener('input', function() {
  cc.textContent = inp.value.length + ' karakter';
  err.hidden = true;
});

$('btn-run').addEventListener('click', function() {
  var v = inp.value.trim();
  if (!v) {
    err.textContent = 'Harap masukkan teks pesan atau tautan terlebih dahulu.';
    err.hidden = false;
    inp.focus();
    return;
  }
  err.hidden = true;
  out.innerHTML = '<div class="load">Menganalisis indikasi penipuan & kata kunci mencurigakan…</div>';

  fetch(ANALYZE_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    body: JSON.stringify({ text: v })
  })
  .then(function(res) { return res.json(); })
  .then(function(resp) {
    if (resp.status === 'success') {
      renderResult(resp.data);
    } else {
      err.textContent = resp.error || 'Terjadi kesalahan analisis.';
      err.hidden = false;
    }
  })
  .catch(function(e) {
    err.textContent = 'Koneksi ke server gagal: ' + e.message;
    err.hidden = false;
  });
});

function renderResult(r) {
  var isHigh = r.is_high_risk;
  var isMed = r.total_bobot >= 1 && !isHigh;

  var tagClass = isHigh ? 'sim' : '';
  var tagText = isHigh ? '⚠️ BAHAYA TINGGI - INDIKASI PENIPUAN' : (isMed ? '🔍 WASPADA - PERLU VERIFIKASI' : '✅ POLA AMAN (TIDAK BERBAHAYA)');

  var h = '<div class="res">' +
    '<span class="tag ' + tagClass + '">' + tagText + '</span>' +
    '<p class="sum" style="margin-top:12px; font-size:15px;">Ditemukan <b>' + r.jumlah_temuan + ' indikasi</b> pada teks pesan dengan skor bobot: <b>' + r.total_bobot + '</b>.</p>';

  if (r.daftar_temuan && r.daftar_temuan.length) {
    h += '<p class="label" style="margin-top:16px;">Rincian Temuan dari Model PolaPesan:</p>';
    r.daftar_temuan.forEach(function(x, idx) {
      h += '<div style="border-top:1px solid var(--line); margin-top:12px; padding-top:12px;">' +
        '<h4 style="margin:0 0 4px; font-size:15.5px;">' + (idx + 1) + '. ' + x.nama_pola + ' (Bobot: ' + x.bobot + ')</h4>' +
        (x.snippet ? '<code style="display:inline-block; background:rgba(20,35,63,0.06); padding:2px 6px; border-radius:3px; font-size:13px; margin-bottom:6px;">Teks: "' + x.snippet + '"</code>' : '') +
        '<p style="margin:4px 0; font-size:14px;"><b>Risiko:</b> ' + x.alasan_risiko + '</p>' +
        '<p style="margin:4px 0; font-size:14px; color:var(--mute);"><b>Saran:</b> ' + x.solusi_aman + '</p>' +
        '</div>';
    });
  }

  if (isHigh) {
    h += '<div style="margin-top:16px;"><a class="btn" style="border-color:var(--rust); color:var(--rust); width:100%;" href="{{ route("sos.index") }}">⚠️ Buka Panduan Darurat & Kontak Bank (SOS)</a></div>';
  }

  h += '</div>';
  out.innerHTML = h;
}

$('btn-clear').addEventListener('click', function() {
  inp.value = '';
  cc.textContent = '0 karakter';
  out.innerHTML = '<div class="empty">Hasil pemeriksaan pola akan muncul di sini setelah Anda mengklik tombol "Periksa Pesan Sekarang".</div>';
  err.hidden = true;
});

$('btn-copy').addEventListener('click', function() {
  var resText = out.innerText || out.textContent;
  copyText('--- HASIL ANALISIS KEAMANAN HAWAS ---\n' + resText, 'Hasil analisis berhasil disalin!');
});

var DEMOS = {
  apk: 'Selamat siang kak, paket Anda dengan nomor resi JNT90218128 tertahan di gudang cabang. Silakan unduh dan instal file berikut untuk melihat foto paket dan konfirmasi alamat: Foto_Paket_JNT.apk',
  undian: 'Selamat! Nomor Anda terpilih memenangkan Hadiah Utama Rp 50.000.000 dari Gebyar Undian Bank. Batas klaim hari ini juga! Segera klik tautan resmi untuk konfirmasi nomor rekening: https://t.ly/gebyar-hadiah-50jt',
  blokir: 'PERINGATAN: Akun m-Banking Anda terdeteksi ada aktivitas login tidak wajar di perangkat lain. Akun akan dibekukan permanen dalam 15 menit. Balas pesan ini segera dengan 6 digit kode OTP yang baru dikirimkan via SMS untuk pembatalan blokir.',
  aman: 'Info BCA: Transaksi debit di EDC Indomaret sebesar Rp 45.000 pada 04/11/2026 berhasil. Sisa saldo rekening Anda Rp 1.250.000. Hubungi HaloBCA 1500888 jika ini bukan transaksi Anda.'
};

document.querySelectorAll('.preset-chips button').forEach(function(btn) {
  btn.addEventListener('click', function() {
    inp.value = DEMOS[btn.dataset.demo] || '';
    cc.textContent = inp.value.length + ' karakter';
    $('btn-run').click();
  });
});
</script>
@endsection

