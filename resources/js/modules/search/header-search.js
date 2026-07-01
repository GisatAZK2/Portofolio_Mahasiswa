/**
 * modules/search/header-search.js
 * Header unified search: live post filter, suggestions dropdown, filter buttons.
 */

(function () {
    const currentLocale = document.documentElement.lang || 'id';

    function isDashboard() { return !!document.getElementById('postingan-container'); }

    function highlightTitle(post, term) {
        const h3 = post.querySelector('h3');
        if (!h3) return;
        if (!h3.dataset.originalText) h3.dataset.originalText = h3.innerText;
        const original = h3.dataset.originalText;
        const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        h3.innerHTML = original.replace(regex, '<mark style="background:#fef08a;color:inherit;border-radius:2px;padding:0 1px;" class="search-highlight">$1</mark>');
    }

    function restoreTitle(post) {
        const h3 = post.querySelector('h3');
        if (!h3 || !h3.dataset.originalText) return;
        h3.innerHTML = h3.dataset.originalText;
    }

    function performPostSearch(term) {
        if (!isDashboard()) return;
        const allPosts = document.querySelectorAll('#postingan-container .post-card');
        const pagination = document.getElementById('postingan-pagination');
        const noResultEl = document.getElementById('postingan-no-results');
        const badge = document.getElementById('header-post-search-badge');
        const badgeMob = document.getElementById('header-post-search-badge-mobile');
        const clearBtn = document.getElementById('header-post-search-clear');

        if (term.length < 2) {
            allPosts.forEach(p => { p.style.display = ''; restoreTitle(p); });
            if (pagination) pagination.style.display = '';
            if (badge) badge.style.display = 'none';
            if (badgeMob) badgeMob.classList.add('hidden');
            if (clearBtn) clearBtn.style.display = 'none';
            if (noResultEl) noResultEl.remove();
            window.performDashboardPostSearch?.('', 0);
            return;
        }

        if (clearBtn) clearBtn.style.display = 'flex';
        if (pagination) pagination.style.display = 'none';

        let visible = 0;
        allPosts.forEach(post => {
            const dataTitle = post.getAttribute('data-post-title') || '';
            const dataDesc = post.getAttribute('data-post-description') || '';
            const dataAuth = post.getAttribute('data-post-author') || '';
            const uiTitle = post.querySelector('h3')?.innerText?.toLowerCase() || '';
            const lc = term.toLowerCase();
            const hit = dataTitle.includes(lc) || dataDesc.includes(lc) || dataAuth.includes(lc) || uiTitle.includes(lc);
            if (hit) { post.style.display = ''; visible++; highlightTitle(post, term); }
            else { post.style.display = 'none'; restoreTitle(post); }
        });
        window.performDashboardPostSearch?.(term, visible);

        let noRes = document.getElementById('postingan-no-results');
        if (visible === 0) {
            if (!noRes) {
                noRes = document.createElement('div');
                noRes.id = 'postingan-no-results';
                noRes.className = 'text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mt-4';
                noRes.innerHTML = `<svg class="w-14 h-14 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada postingan ditemukan</p><p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba kata kunci lain</p>`;
                const container = document.getElementById('postingan-container');
                if (container) container.parentNode.insertBefore(noRes, container.nextSibling);
            } else { noRes.style.display = ''; }
        } else if (noRes) { noRes.style.display = 'none'; }
    }

    function fetchSuggestions(query, containerEl, suggestionsUrl, searchUrl) {
        if (!containerEl) return;
        if (query.length < 2) { containerEl.classList.add('hidden'); return; }
        fetch(`${suggestionsUrl}?q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(data => {
                if (!data.length) {
                    containerEl.innerHTML = `<div class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada hasil</div>`;
                } else {
                    const grouped = data.reduce((acc, item) => { acc[item.type] = acc[item.type] || []; acc[item.type].push(item); return acc; }, {});
                    const titles = { mahasiswa: 'Mahasiswa', project: 'Project', sertifikat: 'Sertifikat', postingan: 'Postingan' };
                    let html = '';
                    Object.keys(titles).forEach(type => {
                        const items = grouped[type] || [];
                        if (items.length) {
                            html += `<div class="border-b border-gray-100 dark:border-gray-700"><div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">${titles[type]}</div>`;
                            items.forEach(item => {
                                html += `<a href="${item.url}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm text-gray-700 dark:text-gray-200"><div class="font-medium">${item.name}</div><div class="text-xs text-gray-500 dark:text-gray-400">${item.label}</div></a>`;
                            });
                            html += `</div>`;
                        }
                    });
                    html += `<div class="px-4 py-3 bg-white dark:bg-gray-800"><a href="${searchUrl}?q=${encodeURIComponent(query)}" class="block text-center text-sm text-indigo-600 dark:text-indigo-400 font-medium">Lihat semua hasil</a></div>`;
                    containerEl.innerHTML = html;
                }
                containerEl.classList.remove('hidden');
            })
            .catch(() => containerEl.classList.add('hidden'));
    }

    function initUnifiedSearch() {
        const desktopInput = document.getElementById('unified-search-input');
        const mobileInput = document.getElementById('unified-search-input-mobile');
        const clearBtn = document.getElementById('header-post-search-clear');
        const suggDesktop = document.getElementById('search-suggestions');
        const suggMobile = document.getElementById('search-suggestions-mobile');
        if (!desktopInput && !mobileInput) return;

        const suggestionsUrl = desktopInput?.dataset.suggestionsUrl || mobileInput?.dataset.suggestionsUrl || '/search/suggestions';
        const searchUrl = desktopInput?.dataset.searchUrl || mobileInput?.dataset.searchUrl || '/search';

        const resetBtn = document.getElementById('filter-reset-btn');
        function checkFiltersActive() {
            if (!resetBtn) return;
            const j = document.getElementById('filter-jurusan')?.value;
            const k = document.getElementById('filter-keahlian')?.value;
            const a = document.getElementById('filter-angkatan')?.value;
            const q = desktopInput?.value?.trim();
            resetBtn.style.display = (j || k || a || q) ? 'flex' : 'none';
        }

        let postTimer, suggTimer;
        function onInput(e) {
            const term = e.target.value.trim();
            if (e.target === desktopInput && mobileInput) mobileInput.value = e.target.value;
            if (e.target === mobileInput && desktopInput) desktopInput.value = e.target.value;
            clearTimeout(postTimer);
            postTimer = setTimeout(() => performPostSearch(term), 280);
            clearTimeout(suggTimer);
            const targetSugg = e.target === desktopInput ? suggDesktop : suggMobile;
            suggTimer = setTimeout(() => fetchSuggestions(term, targetSugg, suggestionsUrl, searchUrl), 250);
            checkFiltersActive();
        }

        if (desktopInput) desktopInput.addEventListener('input', onInput);
        if (mobileInput) mobileInput.addEventListener('input', onInput);

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (desktopInput) desktopInput.value = '';
                if (mobileInput) mobileInput.value = '';
                performPostSearch('');
                if (suggDesktop) suggDesktop.classList.add('hidden');
                if (suggMobile) suggMobile.classList.add('hidden');
                checkFiltersActive();
            });
        }
        ['filter-jurusan', 'filter-keahlian', 'filter-angkatan'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', checkFiltersActive);
        });
        checkFiltersActive();

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#unified-search-input') && !e.target.closest('#search-suggestions') &&
                !e.target.closest('#unified-search-input-mobile') && !e.target.closest('#search-suggestions-mobile')) {
                if (suggDesktop) suggDesktop.classList.add('hidden');
                if (suggMobile) suggMobile.classList.add('hidden');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initUnifiedSearch);
})();

// Navigate to search route helper
document.addEventListener('DOMContentLoaded', function () {
    const desktopInput = document.getElementById('unified-search-input');
    const mobileInput = document.getElementById('unified-search-input-mobile');
    if (!desktopInput && !mobileInput) return;

    const searchUrl = desktopInput?.dataset.searchUrl || mobileInput?.dataset.searchUrl || '/search';

    function buildSearchUrl(inputId, jurusanId, keahlianId, angkatanId) {
        const q = document.getElementById(inputId)?.value?.trim() || '';
        const jurusan = document.getElementById(jurusanId)?.value || '';
        const keahlian = document.getElementById(keahlianId)?.value || '';
        const angkatan = document.getElementById(angkatanId)?.value || '';
        const params = new URLSearchParams();
        if (q) params.set('q', q);
        if (jurusan) params.set('jurusan', jurusan);
        if (keahlian) params.set('keahlian', keahlian);
        if (angkatan) params.set('angkatan', angkatan);
        return `${searchUrl}${params.toString() ? '?' + params.toString() : ''}`;
    }

    const filterBtn = document.getElementById('filter-search-btn');
    if (filterBtn) filterBtn.addEventListener('click', () => window.location.href = buildSearchUrl('unified-search-input', 'filter-jurusan', 'filter-keahlian', 'filter-angkatan'));

    const unifiedInput = document.getElementById('unified-search-input');
    if (unifiedInput) unifiedInput.addEventListener('keydown', e => { if (e.key === 'Enter') window.location.href = buildSearchUrl('unified-search-input', 'filter-jurusan', 'filter-keahlian', 'filter-angkatan'); });

    const filterBtnMobile = document.getElementById('filter-search-btn-mobile');
    if (filterBtnMobile) filterBtnMobile.addEventListener('click', () => window.location.href = buildSearchUrl('unified-search-input-mobile', 'filter-jurusan-mobile', 'filter-keahlian-mobile', 'filter-angkatan-mobile'));

    const mobInput = document.getElementById('unified-search-input-mobile');
    if (mobInput) mobInput.addEventListener('keydown', e => { if (e.key === 'Enter') window.location.href = buildSearchUrl('unified-search-input-mobile', 'filter-jurusan-mobile', 'filter-keahlian-mobile', 'filter-angkatan-mobile'); });
});
