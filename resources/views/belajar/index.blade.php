@extends('layouts.app')

@section('content')
<main id="isi">
  <p class="label">Modul 4 &middot; Kebiasaan Aman Transaksi</p>
  <h1>Tiga Kebiasaan Yang Melindungi Rekening Anda</h1>
  <p class="lead">Tiga prinsip perlindungan mendasar untuk menjaga rekening bank, PIN, dan kode rahasia OTP Anda dari berbagai modus kejahatan rekayasa sosial.</p>
  <svg class="band rule" aria-hidden="true" style="height:20px; margin:8px 0 18px;"><rect width="100%" height="20" fill="url(#pn)"/></svg>

  <ol class="cards">
    @foreach ($habits as $h)
      <li>
        <article class="k" style="background:var(--card); border:1.5px solid var(--ink); border-radius:6px; overflow:hidden;">
          <div class="hd" style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-bottom:1px solid var(--line);">
            <span class="ic" style="width:40px; height:40px; border:1.5px solid var(--ink); display:grid; place-items:center; border-radius:4px; font-weight:800; color:var(--navy);">
              #{{ $h->nomor_urut }}
            </span>
            <span class="no" style="font-size:12px; letter-spacing:0.12em; font-weight:800; text-transform:uppercase; color:var(--rust);">
              Prinsip Keamanan #{{ $h->nomor_urut }}
            </span>
          </div>
          <div class="bd" style="padding:16px;">
            <h2 style="font-size:18.5px; line-height:1.35; margin:0 0 12px;">{{ $h->judul }}</h2>
            <div style="padding:10px 0; border-top:1px solid var(--line); font-size:14.5px;">
              <b style="display:block; font-size:11.5px; text-transform:uppercase; color:var(--mute); margin-bottom:2px;">Mengapa Berbahaya:</b>
              {{ $h->deskripsi_risiko }}
            </div>
            <div style="padding:10px 0; border-top:1px solid var(--line); font-size:14.5px;">
              <b style="display:block; font-size:11.5px; text-transform:uppercase; color:var(--green); margin-bottom:2px;">Tindakan Aman:</b>
              {{ $h->tindakan_aman }}
            </div>
            <a class="btn s" href="{{ url($h->link_skenario ?: 'simulasi') }}" style="margin-top:10px; width:100%;">
              Buka Latihan Terkait →
            </a>
          </div>
        </article>
      </li>
    @endforeach
  </ol>

  <!-- Interactive Quiz Section -->
  <section class="quiz-box" style="margin-top:36px; border:2px solid var(--navy); border-radius:8px; padding:20px; background:var(--card); box-shadow:0 4px 0 var(--gold);">
    <p class="label">Evaluasi Pemahaman</p>
    <h2 style="font-size:22px; margin-bottom:6px;">Uji Pemahaman Anda ({{ $questions->count() }} Soal)</h2>
    <p style="color:var(--mute); font-size:14.5px; margin-bottom:18px;">Pilih jawaban yang paling aman untuk menguji sejauh mana kesiapan Anda bertransaksi secara bijak:</p>

    <form id="quiz-form">
      @foreach ($questions as $idx => $q)
        <div class="q-item" data-id="{{ $q->id }}" style="padding:16px 0; border-bottom:1px solid var(--line);">
          <div style="font-weight:800; font-size:15.5px; margin-bottom:10px;">
            {{ ($idx + 1) . '. ' . $q->pertanyaan }}
          </div>
          <div style="display:grid; gap:8px;">
            <label style="display:flex; align-items:center; gap:10px; background:var(--bg); padding:10px 14px; border:1px solid var(--line); border-radius:4px; cursor:pointer;">
              <input type="radio" name="ans[{{ $q->id }}]" value="A" required>
              <span>A. {{ $q->opsi_a }}</span>
            </label>
            <label style="display:flex; align-items:center; gap:10px; background:var(--bg); padding:10px 14px; border:1px solid var(--line); border-radius:4px; cursor:pointer;">
              <input type="radio" name="ans[{{ $q->id }}]" value="B">
              <span>B. {{ $q->opsi_b }}</span>
            </label>
            <label style="display:flex; align-items:center; gap:10px; background:var(--bg); padding:10px 14px; border:1px solid var(--line); border-radius:4px; cursor:pointer;">
              <input type="radio" name="ans[{{ $q->id }}]" value="C">
              <span>C. {{ $q->opsi_c }}</span>
            </label>
          </div>
          <div class="q-fb" style="display:none; font-size:13.5px; margin-top:10px; padding:8px 12px; border-radius:4px;"></div>
        </div>
      @endforeach

      <div style="margin-top:20px;">
        <button type="submit" class="btn pri" id="btn-submit-quiz" style="width:100%;">Kirim Jawaban & Dapatkan Nilai</button>
      </div>
    </form>
    <div id="quiz-summary" style="display:none; margin-top:20px;"></div>
  </section>
</main>
@endsection

@section('scripts')
<script>
var QUIZ_URL = '{{ route("belajar.submit-quiz") }}';
var CSRF_TOKEN = '{{ csrf_token() }}';

document.getElementById('quiz-form').addEventListener('submit', function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  var answers = {};
  for (var pair of formData.entries()) {
    var m = pair[0].match(/\[(\d+)\]/);
    if (m) answers[m[1]] = pair[1];
  }

  fetch(QUIZ_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': CSRF_TOKEN
    },
    body: JSON.stringify({ answers: answers })
  })
  .then(function(r) { return r.json(); })
  .then(function(res) {
    if (res.status === 'success') {
      var d = res.data;
      for (var qid in d.evaluasi) {
        var item = document.querySelector('.q-item[data-id="' + qid + '"]');
        if (item) {
          var fb = item.querySelector('.q-fb');
          var ev = d.evaluasi[qid];
          if (ev.is_correct) {
            fb.style.background = 'rgba(46, 125, 50, 0.12)';
            fb.innerHTML = '<b style="color:var(--green)">✅ Jawaban Tepat!</b> ' + ev.penjelasan;
          } else {
            fb.style.background = 'rgba(168, 67, 43, 0.12)';
            fb.innerHTML = '<b style="color:var(--rust)">❌ Kurang Tepat (Kunci: ' + ev.correct_answer + ').</b> ' + ev.penjelasan;
          }
          fb.style.display = 'block';
        }
      }

      var sm = document.getElementById('quiz-summary');
      sm.innerHTML = '<div style="background:var(--bg); border:1.5px solid var(--navy); border-radius:6px; padding:16px; text-align:center;">' +
        '<span class="label">Hasil Evaluasi Kuis</span>' +
        '<div style="font-size:36px; font-weight:800; color:var(--navy); margin:6px 0;">' + d.skor + ' / 100 Poin</div>' +
        '<p style="margin:0; font-size:14px; color:var(--mute);">' + (d.skor >= 75 ? '🎉 Selamat! Pemahaman keamanan transaksi Anda sangat baik.' : '👍 Pelajari kembali prinsip 3 Jangan dan coba lagi.') + '</p>' +
        '</div>';
      sm.style.display = 'block';
      showToast('Skor kuis Anda berhasil dievaluasi!');
    }
  });
});
</script>
@endsection

