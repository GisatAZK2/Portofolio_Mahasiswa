import { all } from "axios";
import { id } from "./lang/id.js";
import { en } from "./lang/en.js";

window.autoTranslateSync = function (text) {
  return text;
};

export const translations = { id, en };

const DEFAULT_LANG = 'id';
const SUPPORTED_LANGS = ['id', 'en'];

let translateElements = [];

function cacheTranslateElements() {
  translateElements = Array.from(
    document.querySelectorAll('[data-translate], [data-translate-placeholder]')
  );
}

function getLocaleFromUrl() {
  const pathSegments = window.location.pathname.split('/').filter(Boolean);
  return SUPPORTED_LANGS.includes(pathSegments[0]) ? pathSegments[0] : null;
}

function persistLocale(locale) {
  localStorage.setItem('lang', locale);
  const days = 365;
  const expires = new Date(Date.now() + days * 24 * 60 * 60 * 1000).toUTCString();
  document.cookie = `lang=${locale}; expires=${expires}; path=/; SameSite=Lax`;
}

// FIX: URL locale is the source of truth (server rendered with it).
// localStorage is only used as fallback when there is no locale in the URL.
const urlLang = getLocaleFromUrl();
const storedLang = localStorage.getItem('lang');
let currentLang = urlLang || storedLang || DEFAULT_LANG;

function applyTranslations() {
  const langData = translations[currentLang] || translations[DEFAULT_LANG];

  const translateKey = (page, key) => {
    return langData?.[page]?.[key]
      || translations[DEFAULT_LANG]?.[page]?.[key]
      || key;
  };

  translateElements.forEach(el => {
    const page = el.dataset.translatePage || 'sidebar';
    const key = el.dataset.translate;

    if (page && key) {
      const text = translateKey(page, key);
      if (text !== el.textContent) {
        el.textContent = text;
      }
    }

    const placeholderKey = el.dataset.translatePlaceholder;
    if (placeholderKey) {
      const placeholderText = translateKey(page, placeholderKey);
      if (placeholderText && placeholderText !== el.getAttribute('placeholder')) {
        el.setAttribute('placeholder', placeholderText);
      }
    }
  });

  const select = document.getElementById('languageSelect');
  if (select && select.value !== currentLang) {
    select.value = currentLang;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  // Sync localStorage & cookie to the effective lang (URL-first)
  persistLocale(currentLang);

  const select = document.getElementById('languageSelect');
  if (select) {
    select.value = currentLang;
    select.addEventListener('change', changeLanguage);
  }

  cacheTranslateElements();
  applyTranslations();
});

window.changeLanguage = function () {
  const select = document.getElementById('languageSelect');
  const locale = select.value;

  persistLocale(locale);

  const url = new URL(window.location.href);
  const pathSegments = url.pathname.split('/').filter(seg => seg !== '');

  if (pathSegments.length > 0 && SUPPORTED_LANGS.includes(pathSegments[0])) {
    pathSegments.shift();
  }

  const newPathname = '/' + [locale, ...pathSegments].join('/');
  url.pathname = newPathname;

  url.searchParams.delete('locale');

  window.location.href = url.toString();
};

window.refreshTranslations = function () {
  cacheTranslateElements();
  applyTranslations();
};

// Global access
window.translations = translations;
window.currentLang = currentLang;