@extends('layouts.app')

@section('content')
<main id="isi">
  <p class="label">Modul 3 &middot; Proteksi Pembayaran Digital</p>
  <h1>Validasi & Periksa Nama Merchant QRIS</h1>
  <p class="lead">Pindai kode QRIS dengan kamera ponsel atau unggah foto/screenshot. Sistem akan mengurai data nama pedagang dan mencocokkan keabsahannya dengan nama toko tujuan fisik Anda.</p>

  <div class="box" role="note">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/></svg>
    <span><b>HAWAS Tidak Memproses Pembayaran:</b> Pengecekan berfungsi murni untuk memverifikasi kecocokan nama pemilik QRIS sebelum Anda mentransfer dana dan memasukkan PIN perbankan.</span>
  </div>

  <section id="stage" aria-live="polite"></section>
  <canvas id="cv" hidden></canvas>
  <input type="file" id="fi" accept="image/*" style="display:none">

  <!-- Tabel Log Pemeriksaan Terakhir -->
  @if($recentLogs->isNotEmpty())
    <div style="margin-top:36px; border-top:1px solid var(--line); padding-top:20px;">
      <p class="label">Riwayat Pemeriksaan QRIS Terakhir:</p>
      <div style="display:grid; gap:8px;">
        @foreach($recentLogs as $log)
          <div style="background:var(--card); border:1px solid var(--line); border-radius:4px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center; font-size:13.5px;">
            <div>
              <b>{{ $log->nama_di_qris }}</b>
              <span style="color:var(--mute); font-size:12px;"> vs Toko: {{ $log->nama_toko_tujuan ?: '-' }}</span>
            </div>
            <span class="tag {{ $log->status_hasil === 'cocok' ? '' : 'sim' }}" style="font-size:10px;">
              {{ strtoupper($log->status_hasil) }}
            </span>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</main>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/jsqr.js') }}"></script>
<script>
var VALIDATE_URL = '{{ route("qris.validate") }}';
var CSRF_TOKEN = '{{ csrf_token() }}';

var $ = function(i) { return document.getElementById(i); },
    stage = $('stage'),
    fileInput = $('fi');

function esc(s) {
  return String(s).replace(/[&<>"']/g, function(c) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
  });
}

var I = {
  w: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l9 16H3z"/><path d="M12 10v4M12 17v.5"/></svg>',
  ok: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l3 3 5-6"/></svg>',
  q: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 1 1 3.5 2.3c-.7.4-1 .8-1 1.7M12 17v.5"/></svg>',
  cam: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 8h3l1.5-2h7L17 8h3v11H4z"/><circle cx="12" cy="13" r="3.5"/></svg>',
  up: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="4" width="17" height="16" rx="1"/><path d="M12 16V9M9 11.5l3-3 3 3"/></svg>'
};

function crc16(s) {
  var c = 0xFFFF;
  for (var i = 0; i < s.length; i++) {
    c ^= s.charCodeAt(i) << 8;
    for (var j = 0; j < 8; j++) {
      c = (c & 0x8000) ? ((c << 1) ^ 0x1021) & 0xFFFF : (c << 1) & 0xFFFF;
    }
  }
  return ('0000' + c.toString(16).toUpperCase()).slice(-4);
}

function tlv(s) {
  var o = {}, i = 0;
  while (i + 4 <= s.length) {
    var tag = s.substr(i, 2);
    var l = parseInt(s.substr(i + 2, 2), 10);
    if (isNaN(l) || i + 4 + l > s.length) return null;
    o[tag] = s.substr(i + 4, l);
    i += 4 + l;
  }
  return i === s.length ? o : null;
}

function parseQRIS(raw) {
  var s = String(raw).trim();
  if (s.indexOf('000201') !== 0) return null;
  var o = tlv(s);
  if (!o || !o['59']) return null;
  var body = s.slice(0, -4);
  var ok = /6304$/.test(body) && crc16(body) === s.slice(-4).toUpperCase();
  return {
    name: o['59'],
    city: o['60'] || '',
    amt: o['54'] || '',
    dyn: o['01'] === '12',
    crc: ok,
    src: 'qr'
  };
}

function makeDemoQRIS(name, city, amt) {
  var amtTag = amt ? ('54' + ('0' + amt.length).slice(-2) + amt) : '';
  var b = '000201010211520458125303360' + amtTag + '5802ID' +
    ('59' + ('0' + name.length).slice(-2) + name) +
    ('60' + ('0' + city.length).slice(-2) + city) +
    '6304';
  return b + crc16(b);
}

var st = { v: 'home', m: null, e: null, mth: null, cmp: null, exp: '' },
    stream = null, raf = 0;

function stopCam() {
  cancelAnimationFrame(raf);
  if (stream) {
    stream.getTracks().forEach(function(t) { t.stop(); });
    stream = null;
  }
}

function go(v, x) {
  stopCam();
  st.v = v;
  if (x) {
    for (var k in x) st[k] = x[k];
  }
  draw();
}

function draw() {
  var h = '', s = st;

  if (s.v === 'home') {
    h = '<h2 tabindex="-1" id="hd">Pilih Cara Memeriksa QRIS</h2>' +
      '<div class="acts">' +
      '<button class="btn pri" data-a="perm">' + I.cam + ' Pindai dengan Kamera Ponsel</button>' +
      '<button class="btn" data-a="pick">' + I.up + ' Unggah Gambar / Foto QR</button>' +
      '<button class="btn" data-a="manual">Ketik Manual Nama Merchant</button>' +
      '</div>' +
      '<div style="margin-top:24px; border-top:1px solid var(--line); padding-top:16px;">' +
      '<p class="label">Uji Coba Cepat dengan Contoh Fiktif:</p>' +
      '<div style="display:grid; gap:8px; margin-top:8px;">' +
      '<button class="btn s" data-demo="sah" style="text-align:left; justify-content:flex-start;"><b>✅ Toko Resmi: KOPI BERKAH</b> (Merchant terverifikasi)</button>' +
      '<button class="btn s" data-demo="manipulasi" style="text-align:left; justify-content:flex-start;"><b>⚠️ Manipulasi: REK PRIBADI AHMAD S</b> (Indikasi rekening perseorangan)</button>' +
      '<button class="btn s" data-demo="rusak" style="text-align:left; justify-content:flex-start;"><b>❌ Format Tidak Valid / Rusak</b></button>' +
      '</div></div>';
  } else if (s.v === 'perm') {
    h = '<h2 tabindex="-1" id="hd">Izin Akses Kamera</h2>' +
      '<div class="box"><p>Kamera hanya digunakan secara lokal di browser untuk memindai pola kode QR.</p></div>' +
      '<div class="acts">' +
      '<button class="btn pri" data-a="cam">Izinkan Kamera & Mulai Pindai</button>' +
      '<button class="btn" data-a="manual">Periksa Manual Tanpa Kamera</button>' +
      '<button class="btn s" data-a="home">← Kembali ke Pilihan</button>' +
      '</div>';
  } else if (s.v === 'cam') {
    h = '<h2 tabindex="-1" id="hd">Arahkan Kamera ke Kode QRIS</h2>' +
      '<div class="vid" style="position:relative; background:#0B1426; border-radius:8px; overflow:hidden; aspect-ratio:1/1; max-width:380px; margin:14px auto;"><video id="vd" playsinline muted style="width:100%;height:100%;object-fit:cover;"></video></div>' +
      '<p class="lead" style="margin-top:10px;text-align:center;" id="cs">Mempersiapkan kamera…</p>' +
      '<div class="acts"><button class="btn" data-a="home">Hentikan Kamera</button></div>';
  } else if (s.v === 'manual') {
    h = '<h2 tabindex="-1" id="hd">Pemeriksaan Nama Manual</h2>' +
      '<p class="lead">Ketik nama merchant yang tampil di aplikasi pembayaran Anda:</p>' +
      '<label class="f" for="mn">Nama Merchant di Aplikasi</label>' +
      '<input type="text" id="mn" autocomplete="off" maxlength="60" placeholder="Contoh: Kopi Berkah">' +
      '<div class="acts">' +
      '<button class="btn pri" data-a="mok">Lanjut Periksa Keaslian</button>' +
      '<button class="btn s" data-a="home">← Kembali</button>' +
      '</div>';
  } else if (s.v === 'err') {
    var E = {
      denied: 'Akses Kamera Ditolak. Gunakan fitur "Unggah Gambar QR" atau periksa manual.',
      nocam: 'Kamera Tidak Ditemukan. Gunakan opsi unggah gambar QR.',
      unread: 'Gambar QR Tidak Terbaca. Pastikan gambar jelas dan tidak terpotong.',
      unsup: 'Format QRIS tidak dikenali atau data rusak.',
      lib: 'Pustaka pemindai QR belum termuat.'
    }[s.e] || 'Terjadi kendala saat membaca QR.';

    h = '<div class="warn">' + I.w + '<span><b>Pemberitahuan:</b> ' + E + '</span></div>' +
      '<div class="acts">' +
      '<button class="btn pri" data-a="pick">Unggah Gambar QR</button>' +
      '<button class="btn" data-a="manual">Periksa Manual</button>' +
      '<button class="btn s" data-a="home">← Kembali</button>' +
      '</div>';
  } else if (s.v === 'res') {
    var m = s.m;
    h = '<h2 tabindex="-1" id="hd">QRIS Berhasil Dibaca</h2>' +
      '<div class="prev" style="border:1.5px solid var(--ink); border-radius:6px; background:var(--card); padding:16px;">' +
      '<p class="label">Hasil Dekode EMVCo</p>' +
      '<dl style="display:grid; grid-template-columns:auto 1fr; gap:6px 12px; font-size:15px; margin:8px 0;">' +
      '<dt style="color:var(--mute)">Nama Merchant</dt><dd style="font-size:19px; font-weight:800; color:var(--navy);">' + esc(m.name) + '</dd>' +
      (m.city ? '<dt style="color:var(--mute)">Kota</dt><dd>' + esc(m.city) + '</dd>' : '') +
      (m.amt ? '<dt style="color:var(--mute)">Nominal</dt><dd>Rp ' + esc(m.amt) + '</dd>' : '') +
      '</dl>' +
      '<p style="font-size:13px; color:var(--mute); margin-top:10px;">Integritas Data (CRC): ' + (m.crc ? '✅ Valid' : '⚠️ Tidak Sesuai') + '</p>' +
      '</div>' +
      '<label class="f" for="ex">Nama Toko yang Anda Kunjungi:</label>' +
      '<input type="text" id="ex" autocomplete="off" maxlength="60" value="' + esc(s.exp) + '" placeholder="Contoh: Kopi Berkah">' +
      '<div class="acts">' +
      '<button class="btn pri" data-a="cmp">Validasi Kecocokan Nama</button>' +
      '</div>' +
      '<div id="rs" style="margin-top:16px;"></div>' +
      '<div class="acts" style="margin-top:20px;">' +
      '<button class="btn s" data-a="home">Periksa QR Lain</button>' +
      '</div>';
  }

  stage.innerHTML = h;
  if (s.v === 'cam') startCam();
  if (s.v === 'res' && s.cmp) validateServer();
}

function startCam() {
  if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return go('err', { e: 'nocam' });
  if (typeof jsQR === 'undefined') return go('err', { e: 'lib' });

  navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
    .then(function(s) {
      if (st.v !== 'cam') { s.getTracks().forEach(function(t) { t.stop(); }); return; }
      stream = s;
      var v = $('vd');
      v.srcObject = s;
      v.play();
      var c = $('cv'), x = c.getContext('2d', { willReadFrequently: true });

      function loop() {
        if (!stream) return;
        if (v.readyState === v.HAVE_ENOUGH_DATA) {
          c.width = v.videoWidth;
          c.height = v.videoHeight;
          x.drawImage(v, 0, 0, c.width, c.height);
          var img = x.getImageData(0, 0, c.width, c.height);
          var code = jsQR(img.data, img.width, img.height);
          if (code && code.data) {
            var m = parseQRIS(code.data);
            if (m) return go('res', { m: m, exp: '', cmp: null });
          }
        }
        raf = requestAnimationFrame(loop);
      }
      raf = requestAnimationFrame(loop);
    })
    .catch(function() { go('err', { e: 'denied' }); });
}

function validateServer() {
  var rs = $('rs'), m = st.m, exp = (st.exp || '').trim();

  fetch(VALIDATE_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    body: JSON.stringify({
      nama_qris: m.name,
      nama_toko_tujuan: exp,
      crc_valid: m.crc
    })
  })
  .then(function(r) { return r.json(); })
  .then(function(res) {
    if (res.status === 'success') {
      var h = '';
      if (res.is_match) {
        h = '<div class="res" style="border-color:var(--green);">' +
          '<span class="tag" style="background:var(--green);color:#fff;">✅ NAMA MERCHANT COCOK</span>' +
          '<p style="margin-top:10px;">Nama merchant di QRIS (<b>' + esc(m.name) + '</b>) persis sesuai dengan toko yang Anda tuju.</p>' +
          '</div>';
      } else if (res.is_partial) {
        h = '<div class="res" style="border-color:var(--gold);">' +
          '<span class="tag" style="background:var(--gold);color:#14233F;">🔍 NAMA SEBAGIAN MIRIP</span>' +
          '<p style="margin-top:10px;">Nama di QRIS adalah <b>' + esc(m.name) + '</b>. Konfirmasikan ke kasir toko sebelum menekan PIN.</p>' +
          '</div>';
      } else {
        h = '<div class="res" style="border-color:var(--rust); background:rgba(168,67,43,0.06);">' +
          '<span class="tag sim">⚠️ PERINGATAN: NAMA SANGAT BERBEDA</span>' +
          '<p style="margin-top:10px;">Nama pada QRIS: <b style="color:var(--rust); font-size:17px;">' + esc(m.name) + '</b>, bukan <b>' + esc(exp) + '</b>.</p>' +
          '<p><b>Peringatan:</b> Stiker barcode di meja kasir kemungkinan ditempel stiker rekening pribadi penipu. <b>JANGAN BAYAR!</b></p>' +
          '</div>';
      }

      if (res.known_merchant) {
        h += '<div style="background:var(--bg); border:1px solid var(--line); border-radius:4px; padding:10px; margin-top:12px; font-size:13.5px;">' +
          '<b>Data Merchant Resmi:</b> Terdaftar sebagai kategori <i>' + esc(res.known_merchant.kategori) + '</i> (' + esc(res.known_merchant.status_keaslian) + '). Catatan: ' + esc(res.known_merchant.catatan) +
          '</div>';
      }

      rs.innerHTML = h;
    }
  });
}

document.addEventListener('click', function(e) {
  var a = e.target.closest('[data-a]');
  if (a) {
    e.preventDefault();
    var act = a.dataset.a;
    if (act === 'home') go('home');
    else if (act === 'perm') go('perm');
    else if (act === 'cam') go('cam');
    else if (act === 'manual') go('manual');
    else if (act === 'pick') fileInput.click();
    else if (act === 'mok') {
      var mn = ($('mn').value || '').trim();
      if (!mn) { showToast('Ketik nama merchant terlebih dahulu.'); return; }
      go('res', { m: { name: mn, city: '', amt: '', crc: true, src: 'manual' }, exp: '', cmp: null });
    }
    else if (act === 'cmp') {
      st.exp = ($('ex').value || '').trim();
      st.cmp = 1;
      validateServer();
    }
  }

  var d = e.target.closest('[data-demo]');
  if (d) {
    e.preventDefault();
    var dt = d.dataset.demo;
    if (dt === 'sah') {
      var raw = makeDemoQRIS('KOPI BERKAH', 'JAKARTA SELATAN', '28000');
      var m = parseQRIS(raw);
      go('res', { m: m, exp: 'Kopi Berkah', cmp: 1 });
    } else if (dt === 'manipulasi') {
      var raw = makeDemoQRIS('REK PRIBADI AHMAD S', 'JAKARTA TIMUR', '28000');
      var m = parseQRIS(raw);
      go('res', { m: m, exp: 'Kopi Berkah', cmp: 1 });
    } else if (dt === 'rusak') {
      go('err', { e: 'unsup' });
    }
  }
});

fileInput.addEventListener('change', function(e) {
  if (e.target.files && e.target.files[0]) {
    var reader = new FileReader();
    reader.onload = function(evt) {
      var img = new Image();
      img.onload = function() {
        var c = $('cv'), x = c.getContext('2d');
        c.width = img.width; c.height = img.height;
        x.drawImage(img, 0, 0);
        var idata = x.getImageData(0, 0, c.width, c.height);
        var code = jsQR(idata.data, idata.width, idata.height);
        if (code && code.data) {
          var m = parseQRIS(code.data);
          if (m) go('res', { m: m, exp: '', cmp: null });
          else go('err', { e: 'unsup' });
        } else go('err', { e: 'unread' });
      };
      img.src = evt.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
  }
});

go('home');
</script>
@endsection

