/**
 * HAWAS - Shared Utilities
 */

// Theme Management
(function initTheme() {
  var savedTheme = localStorage.getItem('hawas_theme');
  if (savedTheme) {
    document.documentElement.setAttribute('data-theme', savedTheme);
  } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.setAttribute('data-theme', 'dark');
  }
})();

function toggleTheme() {
  var current = document.documentElement.getAttribute('data-theme') || 
    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  var next = current === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem('hawas_theme', next);
  showToast(next === 'dark' ? 'Mode Gelap diaktifkan' : 'Mode Terang diaktifkan');
}

// Toast Notification
var __toastTimeout = null;
function showToast(msg, duration) {
  duration = duration || 2800;
  var t = document.getElementById('toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toast';
    t.className = 'toast';
    t.setAttribute('role', 'status');
    document.body.appendChild(t);
  }
  t.textContent = msg;
  t.hidden = false;
  clearTimeout(__toastTimeout);
  __toastTimeout = setTimeout(function() {
    t.hidden = true;
  }, duration);
}

// Clipboard Helper
function copyText(text, successMessage) {
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(text).then(function() {
      showToast(successMessage || 'Berhasil disalin ke papan klip!');
    }).catch(function() {
      fallbackCopy(text, successMessage);
    });
  } else {
    fallbackCopy(text, successMessage);
  }
}

function fallbackCopy(text, successMessage) {
  var ta = document.createElement('textarea');
  ta.value = text;
  ta.style.position = 'fixed';
  ta.style.opacity = '0';
  document.body.appendChild(ta);
  ta.select();
  try {
    document.execCommand('copy');
    showToast(successMessage || 'Berhasil disalin!');
  } catch (err) {
    showToast('Gagal menyalin otomatis');
  }
  document.body.removeChild(ta);
}

// Initialize active links and theme button on DOM ready
document.addEventListener('DOMContentLoaded', function() {
  // Bind theme button
  var themeBtns = document.querySelectorAll('.theme-toggle');
  themeBtns.forEach(function(btn) {
    btn.addEventListener('click', toggleTheme);
  });
});

