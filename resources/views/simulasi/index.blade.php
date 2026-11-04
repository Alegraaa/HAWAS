@extends('layouts.app')

@section('content')
<main id="isi">
  <div class="sim-container">
    <div class="sim-strip">
      <span class="tag safe">SIMULASI RESMI</span>
      <span>Latihan interaktif aman tanpa memakai uang sungguhan &middot; Kenali tanda-tanda manipulasi penipu online.</span>
    </div>

    <div class="sim-top-nav">
      <div class="sim-top-info">
        <span class="sim-step-badge">LATIHAN INTERAKTIF</span>
        <a href="{{ route('simulasi.riwayat') }}" class="btn s">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>Lihat Riwayat Latihan</span>
        </a>
      </div>
      <ol class="prog" id="prog" aria-label="Progres Simulasi"></ol>
    </div>

    <div class="bal" aria-live="polite">
      <div>
        <span class="label">Saldo Fiktif</span>
        <b id="bal">Rp 1.000.000</b>
      </div>
      <div>
        <span class="label">Skor Belajar</span>
        <b id="sc">0 / 30</b>
      </div>
    </div>

    <section id="stage" aria-live="polite"></section>
  </div>
</main>
@endsection

@section('scripts')
<script>
var S = @json($skenarios);

var SAVE_URL = '{{ route("simulasi.save") }}';
var CSRF_TOKEN = '{{ csrf_token() }}';

var rp = function(n) {
  return 'Rp ' + n.toLocaleString('id-ID');
};

var ICO = {
  ok: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>',
  warn: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l9 16H3z"/><path d="M12 10v4M12 17v.5"/></svg>'
};

var st, $ = function(i) { return document.getElementById(i); };

function init() {
  st = { i: 0, bal: 1000000, sc: 0, ph: 'q', last: null, log: [] };
  draw();
}

function head() {
  var h = '';
  S.forEach(function(x, k) {
    var s = st.ph === 'end' || k < st.i ? 'done' : k === st.i ? 'now' : '';
    h += '<li data-s="' + s + '"' + (s === 'now' ? ' aria-current="step"' : '') + '>Kasus ' + (k + 1) + (s === 'done' ? ' · selesai' : '') + '</li>';
  });
  $('prog').innerHTML = h;
  $('bal').textContent = rp(st.bal);
  $('sc').textContent = st.sc + ' / ' + (S.length * 10);
}

function saveToDatabase() {
  fetch(SAVE_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    body: JSON.stringify({
      skor: st.sc,
      saldo_akhir: st.bal,
      hasil_per_tahap: st.log
    })
  }).then(function(res) { return res.json(); })
    .then(function(data) {
      if (data.status === 'success') {
        showToast('Hasil latihan Anda berhasil disimpan!');
      }
    }).catch(function(e) {
      console.log('Save offline/error:', e);
    });
}

function draw() {
  head();
  var o = $('stage'), c = S[st.i], h = '';

  if (st.ph === 'q') {
    h = '<h2 tabindex="-1" id="hd">' + c.t + '</h2>' +
      '<p class="ctx">' + c.ctx + '</p>' +
      '<div class="phone"><div class="sb"><span>SIMULASI RESMI HAWAS</span><i>Kasus #' + (st.i + 1) + '</i></div><div class="ph">' + c.ph + '</div></div>' +
      '<p class="cap">Pilih keputusan yang akan Anda ambil pada situasi di atas:</p>' +
      '<div class="acts">' +
      '<button class="btn sec" data-a="risk">' + ICO.warn + c.risk + '</button>' +
      '<button class="btn pri" data-a="safe">' + ICO.ok + c.safe + '</button>' +
      '</div>';
  } else if (st.ph === 'fb') {
    var r = st.last === 'risk';
    h = '<h2 tabindex="-1" id="hd">Hasil Pilihan Anda</h2>' +
      '<div class="fb">' +
      '<span class="tag ' + (r ? 'sim' : 'safe') + '">' + (r ? ICO.warn + ' Tindakan Berisiko' : ICO.ok + ' Tindakan Aman & Tepat') + '</span>' +
      '<p>' + (r ? c.r : c.s) + '</p>' +
      (c.n ? '<p class="note">' + c.n + '</p>' : '') +
      '<div class="delta" style="color:' + (r ? 'var(--rust)' : 'var(--green)') + '">' +
      (r ? ICO.warn + ' Saldo fiktif Anda berkurang ' + rp(c.loss) : ICO.ok + ' Skor latihan Anda bertambah +10 poin') +
      '</div></div>' +
      '<div class="acts">' +
      (r ? '<button class="btn sec" data-a="retry">Coba Skenario Ini Lagi</button>' : '') +
      '<button class="btn pri" data-a="next">' + (st.i < S.length - 1 ? 'Lanjut ke Kasus Berikutnya' : 'Lihat Hasil Akhir Simulasi') + '</button>' +
      '<a class="btn" href="{{ route("home") }}">Kembali ke Beranda</a>' +
      '</div>';
  } else {
    var maxSc = S.length * 10;
    var isPerfect = st.sc === maxSc;
    h = '<h2 tabindex="-1" id="hd">Simulasi Selesai!</h2>' +
      '<p class="label" style="margin-top:10px">Hasil Evaluasi Simulasi</p>' +
      '<div class="score"><b>' + st.sc + '</b><span>/ ' + maxSc + ' poin ' + (isPerfect ? '🎉 (Sempurna!)' : '👍 (Bagus!)') + '</span></div>' +
      '<p class="ctx">Sisa saldo fiktif: <b>' + rp(st.bal) + '</b>. Catatan evaluasi Anda telah tersimpan otomatis di riwayat latihan.</p>' +
      '<div class="links">' +
      '<a class="full" href="#" data-a="again">Ulangi Simulasi dari Awal</a>' +
      '<a href="{{ route("simulasi.riwayat") }}">Lihat Riwayat Latihan</a>' +
      '<a href="{{ route("cek.index") }}">Uji Cek Pesan</a>' +
      '<a href="{{ route("qris.index") }}">Uji Cek QRIS</a>' +
      '<a href="{{ route("sos.index") }}" style="color:var(--rust)">Panduan Darurat (SOS)</a>' +
      '</div>';
  }

  o.innerHTML = h;
  var hd = $('hd');
  if (hd && window.__go) hd.focus({ preventScroll: false });
  window.__go = 1;
}

document.addEventListener('click', function(e) {
  var a = e.target.closest('[data-a]');
  if (a) {
    e.preventDefault();
    var k = a.dataset.a, c = S[st.i];
    if (k === 'risk') {
      st.bal = Math.max(0, st.bal - c.loss);
      st.last = 'risk';
      st.ph = 'fb';
      st.log.push({ index: st.i, pilihan: 'berisiko', loss: c.loss });
    } else if (k === 'safe') {
      st.sc += 10;
      st.last = 'safe';
      st.ph = 'fb';
      st.log.push({ index: st.i, pilihan: 'aman', score: 10 });
    } else if (k === 'retry') {
      st.bal += c.loss;
      st.ph = 'q';
      st.log.pop();
    } else if (k === 'next') {
      if (st.i < S.length - 1) {
        st.i++;
        st.ph = 'q';
      } else {
        st.ph = 'end';
        saveToDatabase();
      }
    } else if (k === 'again') {
      init();
      return;
    }
    draw();
  }
});

init();
</script>
@endsection
