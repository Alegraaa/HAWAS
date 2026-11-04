@extends('layouts.app')

@section('content')
<main id="isi">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
    <div>
      <p class="label" style="color:var(--rust);">Pusat Bantuan Cepat</p>
      <h1 style="color:var(--rust);">Layanan Darurat & Pengaduan (SOS)</h1>
    </div>
    <a href="{{ route('sos.daftar-laporan') }}" class="btn s" style="border-color:var(--rust); color:var(--rust); font-size:12.5px;">
      Daftar Pengaduan Tersimpan ({{ $totalLaporan }}) →
    </a>
  </div>
  <p class="lead">Panduan penyelamatan darurat jika Anda terlanjur mentransfer uang, mengunduh file APK, atau memberikan kode OTP ke pihak tak dikenal.</p>

  <div class="calm" style="border-left:4px solid var(--gold); padding:8px 0 8px 14px; margin:0 0 20px; font-size:15px; background:rgba(217,174,69,0.12); border-radius:0 4px 4px 0;">
    <b>Tetap tenang dan jangan menyalahkan diri sendiri.</b> Penipuan online menggunakan rekayasa psikologis terencana. Bertindak dalam 1-2 jam pertama adalah kunci meminimalkan kerugian.
  </div>

  <ol class="steps">
    <li class="first">
      <div>
        <h2>1. Hentikan Komunikasi Seketika</h2>
        <p>Segera putuskan panggilan telepon atau obrolan chat. Jangan kirimkan kode OTP, foto kartu ATM, atau data apa pun lagi, meski diancam atau dirayu.</p>
      </div>
    </li>
    <li>
      <div>
        <h2>2. Jika File APK Terlanjur Terpasang di Ponsel</h2>
        <p>Aplikasi APK penipu bertugas menyadap SMS kode OTP.</p>
        <ul>
          <li><b>Segera aktifkan Mode Pesawat (Airplane Mode)</b> atau matikan Wi-Fi dan Data Seluler agar aplikasi terputus dari server penipu.</li>
          <li><b>JANGAN buka aplikasi perbankan (m-Banking)</b> pada ponsel tersebut sebelum aplikasi dibersihkan.</li>
          <li>Copot pemasangan (Uninstall) aplikasi asing tersebut via Pengaturan Aplikasi, atau reset pabrik ponsel jika ragu.</li>
        </ul>
      </div>
    </li>
    <li>
      <div>
        <h2>3. Hubungi Call Center Resmi Bank untuk Blokir Rekening</h2>
        <p>Gunakan nomor telepon resmi bank di bawah untuk memblokir sementara akses rekening, kartu debit/kredit, dan akun m-Banking Anda.</p>
      </div>
    </li>
    <li>
      <div>
        <h2>4. Amankan Bukti & Buat Formulir Pengaduan</h2>
        <p>Ambil screenshot mutasi transfer, nomor rekening penipu, dan percakapan. Buat draf laporan pengaduan menggunakan form di bawah sebagai arsip pelengkap.</p>
      </div>
    </li>
  </ol>

  <!-- Emergency Contacts -->
  <h3 class="sec" style="font-size:20px; margin:32px 0 6px;">Hotline Resmi Pemblokiran Rekening Bank</h3>
  <p class="sub" style="margin:0 0 14px; color:var(--mute); font-size:14.5px;">Hubungi nomor telepon resmi bank berikut untuk membekukan rekening atau kartu Anda segera:</p>

  <div class="contact-grid" style="display:grid; gap:10px;">
    @foreach ($contacts['bank'] as $c)
      <div class="c-card" style="border:1px solid var(--line); background:var(--card); border-radius:6px; padding:12px 14px; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <b style="display:block; font-size:15px;">{{ $c->nama_institusi }}</b>
          <span style="font-size:13px; color:var(--mute);">{{ $c->nomor_telepon }} · {{ $c->deskripsi }}</span>
        </div>
        <div style="display:flex; gap:6px;">
          <a class="btn s pri" href="tel:{{ preg_replace('/[^\d]/', '', $c->nomor_telepon) }}" style="min-height:36px; padding:4px 10px; font-size:12.5px;">Panggil</a>
          <button class="btn s" type="button" onclick="copyText('{{ $c->nomor_telepon }}', 'Nomor {{ $c->nama_institusi }} disalin!')" style="min-height:36px; padding:4px 10px; font-size:12.5px;">Salin</button>
        </div>
      </div>
    @endforeach
  </div>

  <h3 class="sec" style="font-size:20px; margin:24px 0 6px;">Lembaga Regulasi & Aparat Penegak Hukum</h3>
  <div class="contact-grid" style="display:grid; gap:10px;">
    @foreach (array_merge($contacts['regulator']->all(), $contacts['aparat']->all()) as $c)
      <div class="c-card" style="border:1px solid var(--line); background:var(--card); border-radius:6px; padding:12px 14px; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <b style="display:block; font-size:15px;">{{ $c->nama_institusi }}</b>
          <span style="font-size:13px; color:var(--mute);">{{ $c->nomor_telepon }} · {{ $c->deskripsi }}</span>
        </div>
        <div style="display:flex; gap:6px;">
          @if (!empty($c->url_resmi))
            <a class="btn s pri" href="{{ $c->url_resmi }}" target="_blank" rel="noopener" style="min-height:36px; padding:4px 10px; font-size:12.5px;">Buka Web</a>
          @else
            <a class="btn s pri" href="tel:{{ $c->nomor_telepon }}" style="min-height:36px; padding:4px 10px; font-size:12.5px;">Panggil</a>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <!-- Form Laporan Pengaduan Insiden Penipuan -->
  <h3 class="sec" style="font-size:20px; margin:32px 0 6px;">Formulir Pembuatan Laporan Pengaduan</h3>
  <p class="sub" style="margin:0 0 14px; color:var(--mute); font-size:14.5px;">Isi data kejadian di bawah untuk menghasilkan format surat laporan resmi dan menyimpan arsip kronologi Anda:</p>

  <div class="report-box" style="border:1.5px solid var(--ink); background:var(--card); border-radius:6px; padding:18px;">
    <form method="POST" action="{{ route('sos.lapor') }}" id="form-laporan">
      @csrf
      <label class="f" for="rep-nama">Nama Lengkap Pelapor / Korban:</label>
      <input type="text" name="nama_pelapor" id="rep-nama" placeholder="Contoh: Rian Pratama" required>

      <label class="f" for="rep-bank">Bank Anda & Nomor Rekening Korban:</label>
      <input type="text" name="bank_korban" id="rep-bank" placeholder="Contoh: Bank BCA - 1234567890" required>

      <label class="f" for="rep-penipu">Rekening Tujuan / Kontak WhatsApp Penipu:</label>
      <input type="text" name="kontak_penipu" id="rep-penipu" placeholder="Contoh: Bank Mandiri 987654321 an. Budi / WA 0812xxx" required>

      <label class="f" for="rep-nominal">Total Nominal Kerugian (Rp):</label>
      <input type="text" name="nominal_rugi" id="rep-nominal" placeholder="Contoh: Rp 2.500.000" required>

      <label class="f" for="rep-kronologi">Kronologi Kejadian Singkat:</label>
      <textarea name="kronologi" id="rep-kronologi" style="min-height:90px;" placeholder="Contoh: Pada tanggal 04/11/2026 pukul 14:00, saya menerima pesan WhatsApp mengaku kurir paket yang mengirim file APK..." required></textarea>

      <div class="acts" style="margin-top:16px; display:grid; gap:10px;">
        <button class="btn pri" type="submit">Kirim & Simpan Laporan Pengaduan</button>
        <button class="btn s" type="button" id="btn-copy-template">Salin Format Teks Surat Laporan</button>
      </div>
    </form>
  </div>
</main>
@endsection

@section('scripts')
<script>
document.getElementById('btn-copy-template').addEventListener('click', function() {
  var nama = (document.getElementById('rep-nama').value || '').trim() || '[Nama Korban]';
  var bank = (document.getElementById('rep-bank').value || '').trim() || '[Bank & No Rekening Korban]';
  var penipu = (document.getElementById('rep-penipu').value || '').trim() || '[No Rekening / No HP Penipu]';
  var nominal = (document.getElementById('rep-nominal').value || '').trim() || '[Nominal Kerugian]';
  var kronologi = (document.getElementById('rep-kronologi').value || '').trim() || '[Jelaskan kronologi kejadian]';

  var template = 
    "SURAT PERMOHONAN PEMBLOKIRAN & PENGADUAN TRANSAKSI MENCURIGAKAN\n" +
    "Tanggal: " + new Date().toLocaleDateString('id-ID', { dateStyle: 'full' }) + "\n\n" +
    "Yth. Tim Customer Care / Fraud Investigation,\n\n" +
    "Saya yang bertanda tangan di bawah ini:\n" +
    "- Nama Lengkap : " + nama + "\n" +
    "- Bank / No Rek: " + bank + "\n\n" +
    "Mengajukan permohonan pembekuan transaksi atas indikasi tindak penipuan (fraud):\n" +
    "- Terlapor / Penipu: " + penipu + "\n" +
    "- Total Kerugian   : " + nominal + "\n\n" +
    "Kronologi:\n" +
    kronologi + "\n\n" +
    "Bersama ini saya sertakan bukti transfer dan tangkapan layar percakapan. Mohon segera diproses pemblokiran rekening tujuan penipu.\n\n" +
    "Hormat saya,\n" +
    nama;

  copyText(template, 'Format surat pengaduan berhasil disalin!');
});
</script>
@endsection

