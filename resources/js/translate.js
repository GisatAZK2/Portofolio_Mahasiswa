export const translations = {
  id: {
    dashboard: {
      total_mahasiswa: 'Total Seluruh Mahasiswa',
      semua_learning_corner: 'Semua Learning Corner',
      total_project: 'Total Semua Project',
      total_sertifikat: 'Total Semua Sertifikat',
      perihal_terbaru: 'Perihal Terbaru',
    },
    dashboard_me: {
      learning_corner: 'Learning Corner',
      total_project: 'Total Project Dikerjakan',
      total_sertifikat: 'Total Sertifikat Didapat',
      perihal_terbaru: 'Perihal Terbaru',
    },
    portofolio_user: {
      portfolio: 'Portfolio',
    },
    result_search: {
      hasil_pencarian: 'Hasil Pencarian',
      filter_aktif: 'Filter aktif:',
    },
    sidebar: {
      dashboard: 'Dashboard',
      my_dashboard: 'My Dashboard',
      project_mahasiswa: 'Project Mahasiswa',
      sertifikat_mahasiswa: 'Sertifikat Mahasiswa',
      corner_mahasiswa: 'Corner Mahasiswa',
      learning_corners: 'Learning Corners',
      setting: 'Setting',
      mode: 'Mode',
      bahasa: 'Bahasa',
      logout: 'Logout',
      login: 'Login',
      register: 'Register',
    },
     footer: {
      footer_rights: '© Portofolio Mahasiswa 2026. Semua hak dilindungi undang-undang.',
    }
  },
  en: {
    dashboard: {
      total_mahasiswa: 'Total Students',
      semua_learning_corner: 'All Learning Corners',
      total_project: 'Total Projects',
      total_sertifikat: 'Total Certificates',
      perihal_terbaru: 'Latest Topics',
    },
    dashboard_me: {
      learning_corner: 'Learning Corner',
      total_project: 'Total Projects Done',
      total_sertifikat: 'Total Certificates Earned',
      perihal_terbaru: 'Latest Topics',
    },
    portofolio_user: {
      portfolio: 'Portfolio',
    },
    result_search: {
      hasil_pencarian: 'Search Results',
      filter_aktif: 'Active filter:',
    },
    sidebar: {
      dashboard: 'Dashboard',
      my_dashboard: 'My Dashboard',
      project_mahasiswa: 'Student Projects',
      sertifikat_mahasiswa: 'Student Certificates',
      corner_mahasiswa: 'Student Corner',
      learning_corners: 'Learning Corners',
      setting: 'Setting',
      mode: 'Mode',
      bahasa: 'Language',
      logout: 'Logout',
      login: 'Login',
      register: 'Register',
    },
    footer: {
      footer_rights: '© 2026 Portofolio Mahasiswa. All rights reserved.',
    }
  }
};

export function getTranslation(page, key) {
  const lang = localStorage.getItem('lang') || 'id';
  return translations[lang]?.[page]?.[key] || key;
}

export function updateTranslations() {
  const lang = localStorage.getItem('lang') || 'id';
  document.querySelectorAll('[data-translate]').forEach(el => {
    const page = el.getAttribute('data-translate-page') || 'dashboard';
    const key = el.getAttribute('data-translate');
    el.textContent = translations[lang]?.[page]?.[key] || key;
  });
}

document.addEventListener('turbo:load', updateTranslations);
document.addEventListener('DOMContentLoaded', updateTranslations);
