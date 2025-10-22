let currentLang = localStorage.getItem('lang') || 'ja';
let translations = {};

async function loadLang(lang) {
  const res = await fetch('lang/' + lang + '.json');
  translations = await res.json();
  currentLang = lang;
  localStorage.setItem('lang', lang);
  applyLang();
}

function applyLang() {
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.dataset.i18n;
    if (translations[key]) el.textContent = translations[key];
  });
}

function setupLangSelector() {
  const sel = document.getElementById('lang-select');
  if (!sel) return;
  sel.value = currentLang;
  sel.addEventListener('change', e => loadLang(e.target.value));
  loadLang(currentLang);
}
