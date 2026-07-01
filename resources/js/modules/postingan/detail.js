/**
 * modules/postingan/detail.js
 * Script untuk views_detail_postingan.blade.php, project user page, project detail page.
 * Exported: initPostinganDetailPage, puPlayVideo, openImageModal, closeImageModal.
 */
/* ==========================================
   VIEWS_DETAIL_POSTINGAN.BLADE.PHP SCRIPTS
   ========================================== */

// ===================== GLOBAL CONFIG =====================
window.currentUserId = null;
window.currentUserName = "";
window.currentUserPhoto = "";
window.locale = document.querySelector('html')?.getAttribute('lang') || 'id';
window.commentLastUpdated = {};

// ===================== HELPERS =====================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function getAvatarHtml(user, size = 'w-8 h-8', textSize = 'text-xs') {
    if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
        const photoPath = user.photo_profile.startsWith('http') ? user.photo_profile : `/storage/${user.photo_profile}`;
        return `<img src="${photoPath}" class="${size} rounded-full object-cover flex-shrink-0" onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
    }
    const name = user?.nama_mahasiswa || window.currentUserName || 'User';
    const initial = name.charAt(0).toUpperCase();
    return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
            </div>`;
}

function getHeaders() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    };
}

// ===================== LOAD COMMENTS =====================
window.loadComments = async function (postinganId) {
    const container = document.getElementById(`comments-container-${postinganId}`);
    if (!container) return;

    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm"><div class="comment-loading inline-block mr-2"></div> Memuat komentar...</div>';

    try {
        const response = await fetch(`/${window.locale}/komentar?id_postingan=${postinganId}`);
        const data = await response.json();

        if (data && data.success === true) {
            window.commentLastUpdated[postinganId] = data.last_updated ?? null;

            let commentsArray = [];
            if (data.comments && Array.isArray(data.comments)) {
                commentsArray = data.comments;
            } else if (data.comments && data.comments.comments && Array.isArray(data.comments.comments)) {
                commentsArray = data.comments.comments;
            }

            if (commentsArray.length === 0) {
                const noCommentsText = (window.locale === 'id')
                    ? 'Belum ada komentar. Jadilah yang pertama!'
                    : 'No comments yet. Be the first!';
                container.innerHTML = `<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-5">${noCommentsText}</p>`;
                return;
            }

            let html = '<div class="space-y-4">';
            commentsArray.forEach(comment => {
                html += renderCommentWithReplies(comment, 0, postinganId);
            });
            html += '</div>';
            container.innerHTML = html;

            attachCommentEventListeners(container, postinganId);
        } else {
            container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar</p>';
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar: ' + error.message + '</p>';
    }
};

// ===================== RENDER COMMENT =====================
function renderCommentWithReplies(comment, level, postinganId) {
    const marginLeft = Math.min(level * 28, 56);
    const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
    const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';
    const userName = escapeHtml(comment.user?.nama_mahasiswa || 'User');
    const commentText = escapeHtml(comment.komentar);
    const commentId = String(comment.id_komentar);
    postinganId = String(postinganId);

    const replyText = (window.locale === 'id') ? 'Balas' : 'Reply';
    const editText = (window.locale === 'id') ? 'Edit' : 'Edit';
    const deleteText = (window.locale === 'id') ? 'Hapus' : 'Delete';

    const userPortfolioUrl = comment.user && comment.user.username
        ? `/${window.locale}/portofolio?user=${comment.user.username}`
        : '#';
    const avatarHtml = comment.user && comment.user.username
        ? `<a href="${userPortfolioUrl}">${getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs')}</a>`
        : getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs');
    const nameHtml = comment.user && comment.user.username
        ? `<a href="${userPortfolioUrl}" class="font-semibold text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 transition-colors">${userName}</a>`
        : `<span class="font-semibold text-sm text-gray-900 dark:text-gray-100">${userName}</span>`;

    let html = `
        <div class="comment-item transition-all duration-200 py-2" data-comment-id="${commentId}" data-postingan-id="${postinganId}" style="margin-left: ${marginLeft}px;">
            <div class="flex gap-3">
                ${avatarHtml}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        ${nameHtml}
                        <span class="text-xs text-gray-500">${formatDate(comment.tanggal || comment.created_at)}</span>
                    </div>
                    <p class="comment-text text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed" id="comment-text-${commentId}">${commentText}</p>
                    <div class="flex flex-wrap gap-3 mt-2">
    `;

    if (isLoggedIn) {
        html += `
                        <button class="reply-btn text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors inline-flex items-center gap-1"
                            data-action="reply" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            ${replyText}
                        </button>
        `;
    }

    if (isOwnComment) {
        html += `
                        <button class="edit-comment-btn text-xs text-blue-500 hover:text-blue-700 transition-colors inline-flex items-center gap-1"
                            data-action="edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            ${editText}
                        </button>
                        <button class="delete-comment-btn text-xs text-red-500 hover:text-red-700 transition-colors inline-flex items-center gap-1"
                            data-action="delete" data-comment-id="${commentId}" data-postingan-id="${postinganId}" data-type="full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            ${deleteText}
                        </button>
        `;
    }

    html += `
                    </div>
                </div>
            </div>
            <div id="reply-form-${commentId}" class="reply-form-container hidden mt-3 ml-11"></div>
        </div>
    `;

    if (comment.balasan && comment.balasan.length > 0) {
        html += `<div class="replies-container ml-4 mt-1">`;
        comment.balasan.forEach(reply => {
            html += renderCommentWithReplies(reply, level + 1, postinganId);
        });
        html += `</div>`;
    }

    return html;
}

// ===================== EVENT DELEGATION =====================
function attachCommentEventListeners(container, postinganId) {
    if (!container.hasAttribute('data-delegated')) {
        container.setAttribute('data-delegated', 'true');
        container.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const commentId = btn.getAttribute('data-comment-id');
            const pid = btn.getAttribute('data-postingan-id');
            const action = btn.getAttribute('data-action');

            if (action === 'reply') window.showReplyForm(commentId, pid);
            else if (action === 'edit') window.showEditForm(commentId, pid);
            else if (action === 'delete') window.deleteComment(commentId, pid, btn.getAttribute('data-type') || 'full');
            else if (action === 'save-edit') window.saveEdit(commentId, pid);
            else if (action === 'cancel-edit') window.cancelEdit(commentId);
        });
    }
}

// ===================== SUBMIT COMMENT =====================
window.submitComment = async function (postinganId) {
    const textarea = document.getElementById(`comment-input-${postinganId}`);
    const commentText = textarea.value.trim();

    if (!commentText) {
        if (window.showPageInfo) window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
        else alert('Komentar tidak boleh kosong');
        return;
    }

    const submitBtn = document.getElementById(`submit-comment-btn-${postinganId}`);
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.classList.add('btn-loading');
        submitBtn.innerHTML = 'Mengirim...';
        submitBtn.disabled = true;
    }

    try {
        const response = await fetch(`/${window.locale}/komentar`, {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({ id_postingan: postinganId, komentar: commentText })
        });

        const data = await response.json();

        if (data.success) {
            textarea.value = '';
            await window.loadComments(postinganId);
            updateTotalCommentCount(1);
            if (window.showPageInfo) window.showPageInfo('Komentar berhasil ditambahkan', 'success', 2000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menambahkan komentar');
            else alert(data.message || 'Gagal menambahkan komentar');
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
    } finally {
        if (submitBtn) {
            submitBtn.classList.remove('btn-loading');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
};

// ===================== SUBMIT REPLY =====================
async function submitReply(form, postinganId) {
    if (form.hasAttribute('data-submitting')) return;
    form.setAttribute('data-submitting', 'true');

    const textarea = form.querySelector('textarea[name="komentar"]');
    const commentText = textarea.value.trim();
    const parentId = form.querySelector('input[name="parent_id"]').value;

    if (!commentText) {
        if (window.showPageInfo) window.showPageInfo('Balasan tidak boleh kosong', 'warning', 2000);
        else alert('Balasan tidak boleh kosong');
        form.removeAttribute('data-submitting');
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    const cancelBtn = form.querySelector('button[type="button"]');
    submitBtn.disabled = true;
    if (cancelBtn) cancelBtn.disabled = true;

    try {
        const response = await fetch(`/${window.locale}/komentar`, {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({
                id_postingan: postinganId,
                komentar: commentText,
                parent_id: parentId,
                reply_to_id: parentId
            })
        });

        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            updateTotalCommentCount(1);
            const replyContainer = document.getElementById(`reply-form-${parentId}`);
            if (replyContainer) {
                replyContainer.classList.add('hidden');
                replyContainer.innerHTML = '';
            }
            if (window.showPageInfo) window.showPageInfo('Balasan berhasil ditambahkan', 'success', 2000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menambahkan balasan');
            else alert(data.message || 'Gagal menambahkan balasan');
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        if (cancelBtn) cancelBtn.disabled = false;
        form.removeAttribute('data-submitting');
    }
}

// ===================== SHOW REPLY FORM =====================
window.showReplyForm = function (parentCommentId, postinganId) {
    parentCommentId = String(parentCommentId);
    const replyFormContainer = document.getElementById(`reply-form-${parentCommentId}`);
    if (!replyFormContainer) return;

    const sendText = (window.locale === 'id') ? 'Kirim' : 'Send';
    const cancelText = (window.locale === 'id') ? 'Batal' : 'Cancel';
    const placeholderText = (window.locale === 'id') ? 'Tulis balasan...' : 'Write a reply...';

    if (replyFormContainer.innerHTML.trim() !== '' && !replyFormContainer.classList.contains('hidden')) {
        replyFormContainer.classList.add('hidden');
        replyFormContainer.innerHTML = '';
        return;
    }

    replyFormContainer.innerHTML = `
        <form class="reply-submit-form mt-3">
            <input type="hidden" name="parent_id" value="${parentCommentId}">
            <div class="flex flex-col gap-2">
                <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none text-gray-900 dark:text-white" placeholder="${placeholderText}"></textarea>
                <div class="flex gap-2 justify-end">
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition-colors">${sendText}</button>
                    <button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden'); this.closest('.reply-form-container').innerHTML = '';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">${cancelText}</button>
                </div>
            </div>
        </form>
    `;
    replyFormContainer.classList.remove('hidden');

    const form = replyFormContainer.querySelector('form');
    form.onsubmit = async (e) => {
        e.preventDefault();
        await submitReply(form, postinganId);
    };
};

// ===================== SHOW EDIT FORM =====================
window.showEditForm = function (commentId, postinganId) {
    commentId = String(commentId);
    postinganId = String(postinganId);

    const commentTextEl = document.getElementById(`comment-text-${commentId}`);
    if (!commentTextEl) return;

    const originalText = commentTextEl.innerText;
    const commentItem = commentTextEl.closest('.comment-item');

    const existingEditForm = document.getElementById(`edit-form-${commentId}`);
    if (existingEditForm) {
        existingEditForm.remove();
        commentTextEl.style.display = 'block';
        const editBtn = commentItem.querySelector('.edit-comment-btn');
        if (editBtn) editBtn.style.display = 'inline-flex';
        return;
    }

    const saveText = (window.locale === 'id') ? 'Simpan' : 'Save';
    const cancelText = (window.locale === 'id') ? 'Batal' : 'Cancel';

    const editForm = document.createElement('div');
    editForm.className = 'edit-form mt-2';
    editForm.id = `edit-form-${commentId}`;
    editForm.innerHTML = `
        <textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none text-gray-900 dark:text-white" rows="2">${escapeHtml(originalText)}</textarea>
        <div class="flex gap-2 mt-2">
            <button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors"
                data-action="save-edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">${saveText}</button>
            <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 transition-colors"
                data-action="cancel-edit" data-comment-id="${commentId}">${cancelText}</button>
        </div>
    `;

    commentTextEl.style.display = 'none';
    commentTextEl.parentNode.insertBefore(editForm, commentTextEl.nextSibling);

    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'none';
};

// ===================== SAVE EDIT =====================
window.saveEdit = async function (commentId, postinganId) {
    commentId = String(commentId);
    const editForm = document.getElementById(`edit-form-${commentId}`);
    if (!editForm) return;

    const editTextarea = editForm.querySelector('.edit-textarea');
    if (!editTextarea) return;

    const newText = editTextarea.value.trim();
    if (!newText) {
        if (window.showPageInfo) window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000);
        else alert('Komentar tidak boleh kosong');
        return;
    }

    const saveBtn = editForm.querySelector('.save-edit-btn');
    if (!saveBtn) return;

    const originalBtnHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<div class="comment-loading" style="width:14px;height:14px;"></div>';
    saveBtn.disabled = true;

    try {
        const lastUpdated = window.commentLastUpdated?.[postinganId] ?? '';
        const response = await fetch(
            `/${window.locale}/komentar/update?id=${commentId}&id_postingan=${postinganId}&last_updated=${encodeURIComponent(lastUpdated)}`,
            {
                method: 'PUT',
                headers: getHeaders(),
                body: JSON.stringify({ komentar: newText })
            }
        );

        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            if (window.showPageInfo) window.showPageInfo('Komentar berhasil diperbarui', 'success', 2000);
        } else if (data.code === 'STALE_DATA') {
            await window.loadComments(postinganId);
            if (window.showPageInfo) window.showPageInfo('Komentar diperbarui pengguna lain. Edit ulang jika perlu.', 'warning', 3000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal mengupdate komentar');
            else alert(data.message || 'Gagal mengupdate komentar');
            saveBtn.innerHTML = originalBtnHtml;
            saveBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
        saveBtn.innerHTML = originalBtnHtml;
        saveBtn.disabled = false;
    }
};

// ===================== CANCEL EDIT =====================
window.cancelEdit = function (commentId) {
    commentId = String(commentId);
    const commentTextEl = document.getElementById(`comment-text-${commentId}`);
    const editForm = document.getElementById(`edit-form-${commentId}`);
    if (!commentTextEl) return;

    const commentItem = commentTextEl.closest('.comment-item');
    commentTextEl.style.display = 'block';
    if (editForm) editForm.remove();

    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'inline-flex';
};

// ===================== DELETE COMMENT =====================
window.deleteComment = async function (commentId, postinganId, type = 'full') {
    commentId = String(commentId);
    postinganId = String(postinganId);

    const confirmMessageSingle = (window.locale === 'id')
        ? 'Apakah Anda yakin ingin menghapus balasan ini saja?'
        : 'Are you sure you want to delete this reply only?';
    const confirmMessageFull = (window.locale === 'id')
        ? 'Apakah Anda yakin ingin menghapus komentar ini beserta semua balasannya?'
        : 'Are you sure you want to delete this comment and all its replies?';
    const confirmMessage = type === 'single' ? confirmMessageSingle : confirmMessageFull;

    if (window.showConfirm) {
        const confirmed = await window.showConfirm(confirmMessage);
        if (!confirmed) return;
    } else if (window.showConfirmAlert) {
        const confirmed = await window.showConfirmAlert(confirmMessage);
        if (!confirmed) return;
    } else {
        if (!confirm(confirmMessage)) return;
    }

    if (window.showLoading) window.showLoading('Menghapus...');

    try {
        const response = await fetch(
            `/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`,
            { method: 'DELETE', headers: getHeaders() }
        );

        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            updateTotalCommentCount(-1);
            if (window.showPageInfo) window.showPageInfo('Komentar berhasil dihapus', 'success', 2000);
        } else {
            if (window.showErrorAlert) window.showErrorAlert(data.message || 'Gagal menghapus komentar');
            else alert(data.message || 'Gagal menghapus komentar');
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.showErrorAlert) window.showErrorAlert('Terjadi kesalahan: ' + error.message);
        else alert('Terjadi kesalahan: ' + error.message);
    } finally {
        if (window.closeLoading) window.closeLoading();
    }
};

// ===================== UPDATE TOTAL COUNT =====================
function updateTotalCommentCount(delta) {
    document.querySelectorAll('.comment-total-count, .comment-total-count-heading').forEach(el => {
        const current = parseInt(el.textContent) || 0;
        el.textContent = Math.max(0, current + delta);
    });
}

// ===================== SHARE =====================
window.toggleShare = function (btn) {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({ url }).catch(() => { });
        return;
    }
    navigator.clipboard.writeText(url).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
        setTimeout(() => { btn.innerHTML = original; }, 2000);
        if (window.showPageInfo) window.showPageInfo('Link berhasil disalin!', 'success', 1500);
    }).catch(() => alert('Gagal menyalin link'));
};

// ===================== PAGE INITIALIZER FOR DETAIL POSTINGAN =====================
window.initPostinganDetailPage = function (container) {
    window.currentUserId = container.dataset.userId === 'null' ? null : parseInt(container.dataset.userId);
    window.currentUserName = container.dataset.userName || '';
    window.currentUserPhoto = container.dataset.userPhoto || '';
    window.locale = container.dataset.locale || document.querySelector('html').getAttribute('lang') || 'id';
    window.commentLastUpdated = {};
    const postinganId = parseInt(container.dataset.postinganId);

    // Auto-load comments on page load
    window.loadComments(postinganId);

    // Like button
    const likeBtn = container.querySelector('.like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const pid = this.dataset.postinganId;
            fetch(`/${window.locale}/postingan/toggle-like?id=${pid}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const icon = likeBtn.querySelector('svg');
                        const countSpan = likeBtn.querySelector('.like-count');
                        if (data.liked) {
                            likeBtn.classList.add('text-red-500', 'dark:text-red-400');
                            icon.classList.add('fill-current', 'text-red-500');
                        } else {
                            likeBtn.classList.remove('text-red-500', 'dark:text-red-400');
                            icon.classList.remove('fill-current', 'text-red-500');
                        }
                        if (countSpan) countSpan.textContent = data.like_count;
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }

    // Comment toggle button
    const commentToggle = container.querySelector('.comment-toggle');
    if (commentToggle) {
        commentToggle.addEventListener('click', function (e) {
            e.preventDefault();
            const commentsSection = document.getElementById('comments');
            if (commentsSection) {
                commentsSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Share button
    const shareBtn = container.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = container.dataset.postUrl;
            const title = container.dataset.postUserName;
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: 'Cek postingan ini!',
                    url: url
                }).catch(err => console.log('Share cancelled:', err));
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link disalin ke clipboard!');
                }).catch(err => console.error('Copy failed:', err));
            }
        });
    }

    // Post Menu Dropdown
    const postMenuBtn = document.getElementById('postMenuButton');
    const postMenuDropdown = document.getElementById('postMenuDropdown');
    const postMenuContainer = document.getElementById('postMenuContainer');

    if (postMenuBtn && postMenuDropdown) {
        postMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            postMenuDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function (e) {
            if (postMenuContainer && !postMenuContainer.contains(e.target)) {
                postMenuDropdown.classList.add('hidden');
            }
        });
        postMenuDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }
};

/* ==========================================
   VIEWS_PROJECT_USER.BLADE.PHP SCRIPTS
   ========================================== */
window.playVideoInCard = function (wrapperId, type, src) {
    const wrapper = document.getElementById(wrapperId);
    if (!wrapper) return;

    const embedContainer = wrapper.querySelector('.video-embed-container, .pu-embed-container');
    if (!embedContainer) return;

    if (type === 'youtube') {
        const iframe = document.createElement('iframe');
        iframe.src = 'https://www.youtube.com/embed/' + src + '?autoplay=1&rel=0&modestbranding=1';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        embedContainer.innerHTML = '';
        embedContainer.appendChild(iframe);
    } else if (type === 'direct') {
        const video = document.createElement('video');
        video.src = src;
        video.controls = true;
        video.autoplay = true;
        video.style.width = '100%';
        video.style.height = '100%';
        video.style.objectFit = 'contain';
        video.style.background = '#000';
        embedContainer.innerHTML = '';
        embedContainer.appendChild(video);
    }

    wrapper.classList.add('playing');
    wrapper.onclick = null;
};

window.puPlayVideo = window.playVideoInCard;

/* ==========================================
   VIEWS_DETAIL_PROJECT.BLADE.PHP SCRIPTS
   ========================================== */
window.openImageModal = function (imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    if (modal && modalImage) {
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
};

window.closeImageModal = function () {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
};
