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

let currentLang = localStorage.getItem('lang') || getLocaleFromUrl() || DEFAULT_LANG;

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
    let pathname = url.pathname;
    pathname = pathname.replace(/^\/(id|en)(\/|$)/, '/$2'); 
    if (pathname === '') pathname = '/';
    url.pathname = pathname;

    url.searchParams.set('locale', locale);

    window.location.href = url.toString();
};

window.refreshTranslations = function () {
  cacheTranslateElements();
  applyTranslations();
};

// Global access
window.translations = translations;
window.currentLang = currentLang;