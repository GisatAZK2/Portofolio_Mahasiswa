/**
 * resources/js/admin/create-notification.js
 * Admin Create Notification — notifikasi/views_create_notification.blade.php
 *
 * Load URL is read from the #notification-form container's data-load-users-url attribute
 */

document.addEventListener('DOMContentLoaded', function() {
    const formContainer = document.getElementById('notification-form-container');
    if (!formContainer) return;

    const loadUsersUrlTemplate = formContainer.dataset.loadUsersUrl; // has __LOCALE__ place holder or is absolute

    let selectedUsers = [];
    let currentPage = 1;
    let isLoading = false;

    const targetTypeInput = document.getElementById('target_type');
    if (targetTypeInput) {
        targetTypeInput.addEventListener('change', function() {
            const roleSelection = document.getElementById('role-selection');
            const userSelection = document.getElementById('user-selection');

            roleSelection.classList.add('hidden');
            userSelection.classList.add('hidden');

            if (this.value === 'role') {
                roleSelection.classList.remove('hidden');
            } else if (this.value === 'specific') {
                userSelection.classList.remove('hidden');
                const userList = document.getElementById('user-list');
                if (userList && userList.children.length <= 1) { // Only loading text or empty
                    loadUsers();
                }
            }
        });

        // Trigger on load
        targetTypeInput.dispatchEvent(new Event('change'));
    }

    const userSearchInput = document.getElementById('user-search');
    if (userSearchInput) {
        userSearchInput.addEventListener('input', function() {
            currentPage = 1;
            loadUsers();
        });
    }

    const loadMoreUsersBtn = document.getElementById('load-more-users');
    if (loadMoreUsersBtn) {
        loadMoreUsersBtn.addEventListener('click', function() {
            currentPage++;
            loadUsers(true); // append
        });
    }

    function loadUsers(append = false) {
        if (isLoading) return;
        isLoading = true;

        const search = document.getElementById('user-search').value;
        // Build route url using the template and search/page params
        const url = loadUsersUrlTemplate.replace('__PAGE__', currentPage).replace('__SEARCH__', encodeURIComponent(search));

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            const userList = document.getElementById('user-list');
            if (!append && userList) {
                userList.innerHTML = '';
            }

            if (userList) {
                data.users.forEach(user => {
                    const userDiv = document.createElement('div');
                    userDiv.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
                    userDiv.innerHTML = `
                        <div class="flex items-center gap-3">
                            ${user.photo_profile ?
                                `<img src="/storage/${user.photo_profile}" class="w-10 h-10 rounded-full object-cover">` :
                                `<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">${user.nama_mahasiswa.charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">${user.nama_mahasiswa}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${user.email || user.username}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="user-checkbox" value="${user.id}" ${selectedUsers.includes(user.id) ? 'checked' : ''}>
                        </div>
                    `;
                    userList.appendChild(userDiv);
                });

                // Add event listeners to checkboxes
                document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const userId = parseInt(this.value);
                        if (this.checked) {
                            if (!selectedUsers.includes(userId)) {
                                selectedUsers.push(userId);
                            }
                        } else {
                            selectedUsers = selectedUsers.filter(id => id !== userId);
                        }
                        updateSelectedCount();
                        const selectedUsersInput = document.getElementById('selected-users-input');
                        if (selectedUsersInput) {
                            selectedUsersInput.value = JSON.stringify(selectedUsers);
                        }
                    });
                });
            }

            updateSelectedCount();
            isLoading = false;

            // Hide load more if no more pages
            const loadMoreUsers = document.getElementById('load-more-users');
            if (loadMoreUsers) {
                loadMoreUsers.style.display = data.hasMore ? 'inline' : 'none';
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
            isLoading = false;
        });
    }

    function updateSelectedCount() {
        const selectedCount = document.getElementById('selected-count');
        if (selectedCount) {
            selectedCount.textContent = `${selectedUsers.length} user dipilih`;
        }
    }

    // Load initial users if specific is selected
    if (targetTypeInput && targetTypeInput.value === 'specific') {
        loadUsers();
    }
});
