/**
 * modules/ui/sidebar.js
 * Sidebar: toggle dropdown setting, search menu, close on mobile, previewPhoto.
 */

export function initSidebar() {
    // Zoom logo on click
    document.querySelectorAll('#logo-zoom').forEach(img => {
        img.style.transition = 'transform 0.3s ease';
        img.addEventListener('click', (e) => {
            e.stopPropagation();
            const modal = document.createElement('div');
            modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.85);display:flex;justify-content:center;align-items:center;z-index:9999;cursor:zoom-out;';
            const modalImg = document.createElement('img');
            modalImg.src = img.src;
            modalImg.alt = img.alt || 'Zoomed image';
            modalImg.style.cssText = 'max-width:90vw;max-height:90vh;object-fit:contain;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.6);background:#fff;';
            if (img.classList.contains('rounded-full')) modalImg.style.padding = '16px';
            modal.appendChild(modalImg);
            modal.addEventListener('click', () => { modal.remove(); document.body.style.overflow = ''; });
            modalImg.addEventListener('click', (e) => e.stopPropagation());
            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
        });
    });

    // Sidebar close on mobile link click
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
        document.getElementById('close-sidebar')?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    }

    // Sidebar search
    const searchInput = document.getElementById('sidebarSearch');
    const searchResults = document.getElementById('searchResults');
    const configContainer = document.getElementById('sidebar-config');
    if (searchInput && searchResults && configContainer) {
        let allMenus = [];
        try {
            allMenus = JSON.parse(configContainer.dataset.menus || '[]');
        } catch (err) {
            console.error('Failed to parse sidebar menus JSON:', err);
        }

        function resolveMenuLabel(key) {
            const lang = window.currentLang || 'id';
            const dict = window.translations || {};
            return dict?.[lang]?.sidebar?.[key] || dict?.id?.sidebar?.[key] || key;
        }

        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            if (!q) { searchResults.classList.add('hidden'); return; }
            const filtered = allMenus
                .map(m => ({ ...m, name: resolveMenuLabel(m.key) }))
                .filter(m => m.name && m.name.toLowerCase().includes(q));
            if (!filtered.length) {
                searchResults.innerHTML = `<div class="px-4 py-3 text-gray-500 text-sm text-center">${resolveMenuLabel('menu_tidak_ditemukan')}</div>`;
            } else {
                searchResults.innerHTML = filtered.map(m =>
                    `<a href="${m.url}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-700 text-black dark:text-white text-sm transition-colors">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        ${m.name}
                    </a>`
                ).join('');
            }
            searchResults.classList.remove('hidden');
        });

        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target))
                searchResults.classList.add('hidden');
        });

        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Escape') { searchResults.classList.add('hidden'); searchInput.blur(); }
        });
    }

    // Tutup setting dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        const settingMenu = document.getElementById('settingMenu');
        if (!settingMenu) return;
        const isToggleBtn = e.target.closest('button[onclick*="toggleSidebarSettingDropdown"]');
        if (!isToggleBtn && !settingMenu.contains(e.target)) {
            settingMenu.classList.add('hidden');
            document.getElementById('settingArrow')?.classList.remove('rotate-180');
        }
    });
}

window.previewPhoto = function (event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('preview-photo');
        if (preview) preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
};

window.toggleSidebarSettingDropdown = function () {
    const el = document.getElementById('settingMenu');
    const arrow = document.getElementById('settingArrow');
    if (el && arrow) {
        el.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
};

// Dropdown menu untuk sidebar (section)
window.toggleDropdown = function (section) {
    const menu = document.getElementById(section + 'Menu');
    const arrow = document.getElementById(section + 'Arrow');
    if (!menu || !arrow) {
        console.warn(`Dropdown section "${section}" not found`);
        return;
    }
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
};

document.addEventListener('DOMContentLoaded', initSidebar);
