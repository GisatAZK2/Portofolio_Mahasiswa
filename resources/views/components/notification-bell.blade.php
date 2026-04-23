<div class="fixed top-20 left-4 sm:left-6 z-1000" id="notification-bell" x-data="notificationBell()" x-init="init()">
    <button @click="toggleDropdown" class="relative p-2.5 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full shadow-md transition">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white animate-pulse"></span>
    </button>

    <div x-show="isOpen" @click.away="isOpen = false" x-transition class="absolute left-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
        <div class="p-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ autoTranslate('Notifikasi') }}</h3>
                <div class="flex gap-2">
                    <button @click="markAllAsRead" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">{{ autoTranslate('Tandai dibaca') }}</button>
                    <span class="text-gray-300">|</span>
                    <button @click="clearAll" class="text-xs text-red-600 dark:text-red-400 hover:underline">{{ autoTranslate('Hapus') }}</button>
                </div>
            </div>
        </div>
        
        <div class="max-h-96 overflow-y-auto" @scroll="handleScroll">
            <template x-if="notifications.length === 0">
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm">{{ autoTranslate('Belum ada notifikasi') }}</p>
                </div>
            </template>
            
            <template x-for="item in notifications" :key="item.id">
                <div @click="handleNotificationClick(item)" class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 cursor-pointer transition" :class="{ 'bg-blue-50 dark:bg-blue-900/20': !item.read }">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="getIconBg(item.type)">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center">
                                <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="item.data.title"></p>
                                <span x-show="item.priority === 'high'" class="ml-2 px-1.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded">{{ autoTranslate('PENTING') }}</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="item.data.message"></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="formatTime(item.created_at)"></p>
                        </div>
                        <span x-show="!item.read" class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full"></span>
                    </div>
                </div>
            </template>
            
            <div x-show="isLoading" class="p-3 text-center">
                <svg class="w-5 h-5 mx-auto animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
    function notificationBell() {
        return {
            isOpen: false,
            notifications: [],
            unreadCount: 0,
            page: 1,
            hasMore: true,
            isLoading: false,
            pollingInterval: null,

            init() {
                this.loadNotifications();
                this.startPolling();
                
                if (Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            },

            getIconBg(type) {
                const colors = {
                    'user-registered': 'bg-gradient-to-br from-blue-500 to-indigo-600',
                    'pending-count': 'bg-gradient-to-br from-orange-500 to-amber-600',
                    'project-created': 'bg-gradient-to-br from-green-500 to-emerald-600',
                };
                return colors[type] || 'bg-gradient-to-br from-gray-500 to-gray-600';
            },

            formatTime(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diff = Math.floor((now - date) / 1000);
                
                if (diff < 60) return '{{ autoTranslate("Baru saja") }}';
                if (diff < 3600) return Math.floor(diff / 60) + ' {{ autoTranslate("menit lalu") }}';
                if (diff < 86400) return Math.floor(diff / 3600) + ' {{ autoTranslate("jam lalu") }}';
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
            },

            startPolling() {
                this.pollingInterval = setInterval(async () => {
                    try {
                        const res = await fetch('/api/notifications/unread-count');
                        const data = await res.json();
                        
                        const currentUnread = this.notifications.filter(n => !n.read).length;
                        
                        if (data.count > currentUnread) {
                            this.page = 1;
                            await this.loadNotifications();
                        }
                    } catch (error) {
                        console.error('Polling error:', error);
                    }
                }, 10000); // Cek setiap 10 detik
            },

            stopPolling() {
                if (this.pollingInterval) {
                    clearInterval(this.pollingInterval);
                    this.pollingInterval = null;
                }
            },

            async loadNotifications() {
                try {
                    const res = await fetch(`/api/notifications?page=${this.page}`);
                    const data = await res.json();
                    
                    if (this.page === 1) {
                        this.notifications = data.data;
                    } else {
                        this.notifications = [...this.notifications, ...data.data];
                    }
                    
                    this.hasMore = data.current_page < data.last_page;
                    this.updateUnreadCount();
                } catch (error) {
                    console.error('Failed to load notifications:', error);
                }
            },

            async loadMore() {
                if (this.isLoading || !this.hasMore) return;
                
                this.isLoading = true;
                this.page++;
                await this.loadNotifications();
                this.isLoading = false;
            },

            handleScroll(e) {
                const el = e.target;
                if (el.scrollTop + el.clientHeight >= el.scrollHeight - 50) {
                    this.loadMore();
                }
            },

            async handleNotificationClick(item) {
                if (!item.read) {
                    await this.markAsRead(item.id);
                }
                
                if (item.data.link) {
                    window.location.href = item.data.link;
                }
            },

            async markAsRead(id) {
                try {
                    await fetch(`/api/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    const notification = this.notifications.find(n => n.id === id);
                    if (notification) {
                        notification.read = true;
                    }
                    
                    this.updateUnreadCount();
                } catch (error) {
                    console.error('Failed to mark as read:', error);
                }
            },

            async markAllAsRead() {
                try {
                    await fetch('/api/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    this.notifications.forEach(n => n.read = true);
                    this.updateUnreadCount();
                } catch (error) {
                    console.error('Failed to mark all as read:', error);
                }
            },

            async clearAll() {
                if (!confirm('{{ autoTranslate("Hapus semua notifikasi?") }}')) return;
                
                try {
                    await fetch('/api/notifications/clear-all', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    this.notifications = [];
                    this.unreadCount = 0;
                } catch (error) {
                    console.error('Failed to clear notifications:', error);
                }
            },

            updateUnreadCount() {
                this.unreadCount = this.notifications.filter(n => !n.read).length;
            },

            destroy() {
                this.stopPolling();
            }
        };
    }
</script>