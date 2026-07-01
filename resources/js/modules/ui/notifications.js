/**
 * modules/ui/notifications.js
 * Notification bell Alpine component + placeholder typing effect + search outside click.
 */

// ==========================================
// NOTIFICATION BELL (Alpine Component)
// ==========================================
window.notificationBell = function (data) {
    const currentLocale = document.documentElement.lang || 'id';
    return {
        userId: data.userId, userRole: data.userRole,
        isOpen: false, notifications: [], unreadCount: 0,
        page: 1, pollingInterval: null, isLoading: false,

        filterNotifications(notifications) {
            if (!notifications || !Array.isArray(notifications)) return [];
            return notifications.filter(item => {
                if (item.read === 1 || item.read === true) return false;
                const notifData = item.data || {};
                const selected = notifData.selected_users;
                if (notifData.target_type === 'specific') {
                    if (!selected) return false;
                    if (Array.isArray(selected)) return selected.map(Number).includes(Number(this.userId));
                    if (typeof selected === 'string' && selected.startsWith('[')) {
                        try { const p = JSON.parse(selected); return Array.isArray(p) ? p.map(Number).includes(Number(this.userId)) : false; } catch (e) { }
                    }
                    return Number(selected) === Number(this.userId);
                }
                if (notifData.target_type === 'all') return true;
                if (notifData.target_role) return notifData.target_role === this.userRole;
                if (!notifData.admin_id && !notifData.sender_id) return this.userRole === 'admin';
                return false;
            });
        },

        async init() {
            await this.loadNotifications();
            this.startPolling();
            if (Notification.permission === 'default') Notification.requestPermission();
        },

        getCurrentNotificationIds() { return this.notifications.map(n => n.id); },
        getUnreadNotificationIds() { return this.notifications.filter(n => !n.read).map(n => n.id); },

        getIconBg(type) {
            const colors = {
                'user-registered': 'bg-gradient-to-br from-blue-500 to-indigo-600',
                'project-created': 'bg-gradient-to-br from-green-500 to-emerald-600',
                'certificate-uploaded': 'bg-gradient-to-br from-purple-500 to-pink-600',
            };
            return colors[type] || 'bg-gradient-to-br from-gray-500 to-gray-600';
        },

        formatTime(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        },

        toggleDropdown() { this.isOpen = !this.isOpen; if (this.isOpen && this.unreadCount > 0) this.loadNotifications(); },

        startPolling() {
            if (this.pollingInterval) clearInterval(this.pollingInterval);
            this.pollingInterval = setInterval(async () => {
                try {
                    const res = await fetch(`/${currentLocale}/api/notifications/unread-count`);
                    const data = await res.json();
                    if (data.count !== this.unreadCount) await this.loadNotifications();
                } catch (e) { console.error('Polling error:', e); }
            }, 10000);
        },

        async loadNotifications() {
            if (this.isLoading) return;
            this.isLoading = true;
            try {
                const res = await fetch(`/${currentLocale}/api/notifications?page=${this.page}`);
                const data = await res.json();
                this.notifications = this.filterNotifications(data.data || []);
                this.updateUnreadCount();
            } catch (e) { console.error('Load notifications error:', e); }
            finally { this.isLoading = false; }
        },

        async handleNotificationClick(item) {
            if (!item.read) await this.markAsRead(item.id);
            const locale = document.documentElement.lang || 'id';
            if (item.data.link) {
                window.location.href = item.data.link;
            } else {
                const routes = {
                    'user-registered': `/${locale}/admin/manageUser`,
                    'project-created': `/${locale}/admin/manageProject`,
                    'certificate-uploaded': `/${locale}/admin/manageSertifikat`,
                };
                window.location.href = routes[item.type] || `/${locale}/dashboard`;
            }
        },

        async markAsRead(id) {
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const res = await fetch(`/${currentLocale}/api/notifications/mark-read?id=${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    credentials: 'same-origin'
                });
                const result = await res.json();
                if (result.success) await this.loadNotifications();
            } catch (e) { console.error('Mark as read error:', e); }
        },

        async markAllAsRead() {
            const ids = this.getUnreadNotificationIds();
            if (!ids.length) { this.showToast('Tidak ada notifikasi yang belum dibaca', 'info'); return; }
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const res = await fetch(`/${currentLocale}/api/notifications/mark-all-read`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ notification_ids: ids })
                });
                const result = await res.json();
                if (result.success) { await this.loadNotifications(); this.showToast(result.message || 'Semua notifikasi telah ditandai dibaca', 'success'); }
                else this.showToast('Gagal menandai notifikasi', 'error');
            } catch (e) { this.showToast('Gagal menandai notifikasi. Silakan coba lagi.', 'error'); }
        },

        async clearAll() {
            const ids = this.getCurrentNotificationIds();
            if (!ids.length) { this.showToast('Tidak ada notifikasi yang dapat dihapus', 'info'); return; }
            if (!confirm(`Apakah Anda yakin ingin menghapus ${ids.length} notifikasi?`)) return;
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const res = await fetch(`/${currentLocale}/api/notifications/clear-all`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ notification_ids: ids })
                });
                const result = await res.json();
                if (result.success) { await this.loadNotifications(); this.showToast(result.message || 'Notifikasi telah dihapus', 'success'); }
                else this.showToast('Gagal menghapus notifikasi', 'error');
            } catch (e) { this.showToast('Gagal menghapus notifikasi. Silakan coba lagi.', 'error'); }
        },

        updateUnreadCount() { this.unreadCount = this.notifications.filter(n => !n.read).length; },

        showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-sm text-white ${type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-gray-800')}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    };
};

// ==========================================
// PLACEHOLDER TYPING EFFECT
// ==========================================
(function () {
    function getPlaceholderTexts() {
        const lang = localStorage.getItem('lang') || 'id';
        const texts = {
            id: ['Cari Mahasiswa...', 'Cari Portofolio...', 'Cari Sertifikat...', 'Cari Postingan...'],
            en: ['Search Students...', 'Search Portfolio...', 'Search Certificate...', 'Search Posts...']
        };
        return texts[lang] || texts['id'];
    }
    const getInputs = () => [
        document.getElementById('unified-search-input'),
        document.getElementById('unified-search-input-mobile')
    ].filter(Boolean);

    let texts = getPlaceholderTexts(), textIndex = 0, charIndex = 0, isDeleting = false, speed = 80;
    function typeEffect() {
        const currentText = texts[textIndex];
        getInputs().forEach(input => {
            if (!input.value && document.activeElement !== input) input.setAttribute('placeholder', currentText.substring(0, charIndex));
        });
        if (!isDeleting) {
            charIndex++;
            if (charIndex > currentText.length) { isDeleting = true; setTimeout(typeEffect, 1500); return; }
            speed = 60 + Math.random() * 40;
        } else {
            charIndex--;
            if (charIndex === 0) { isDeleting = false; textIndex = (textIndex + 1) % texts.length; texts = getPlaceholderTexts(); }
            speed = 30 + Math.random() * 30;
        }
        setTimeout(typeEffect, speed);
    }
    document.addEventListener('DOMContentLoaded', typeEffect);
})();

// Close suggestions on outside click
document.addEventListener('click', function (e) {
    if (!e.target.closest('#search-input') && !e.target.closest('#search-suggestions') &&
        !e.target.closest('#search-input-mobile') && !e.target.closest('#search-suggestions-mobile')) {
        document.querySelectorAll('#search-suggestions, #search-suggestions-mobile').forEach(box => box.classList.add('hidden'));
    }
});
