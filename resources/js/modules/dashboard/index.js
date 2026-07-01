/**
 * modules/dashboard/index.js
 * Dashboard: comment system, like/share listeners, sparklines, dosen carousel, skeleton reveal.
 * Re-exports helpers to window so Blade inline scripts can call them.
 */

import { escapeHtml, formatDate, getAvatarHtml, getHeaders } from '../core/helpers.js';

// Expose to window (needed by comments/render templates)
window.cpCharts = window.cpCharts || {};
window.commentLastUpdated = window.commentLastUpdated || {};
window.escapeHtml = escapeHtml;
window.formatDate = formatDate;
window.getAvatarHtml = getAvatarHtml;
window.getHeaders = getHeaders;

// ==========================================
// LOAD & RENDER COMMENTS
// ==========================================
window.loadComments = async function (postinganId) {
    const container = document.getElementById(`comments-container-${postinganId}`);
    if (!container) return;
    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm"><div class="comment-loading inline-block mr-2"></div> <span data-translate="loading_comments" data-translate-page="dashboard">Memuat komentar...</span></div>';
    try {
        const response = await fetch(`/${window.locale}/komentar?id_postingan=${postinganId}`);
        const data = await response.json();
        if (data?.success === true) {
            window.commentLastUpdated[postinganId] = data.last_updated ?? null;
            let commentsArray = [];
            if (data.comments && Array.isArray(data.comments)) commentsArray = data.comments;
            else if (data.comments?.comments && Array.isArray(data.comments.comments)) commentsArray = data.comments.comments;
            if (commentsArray.length === 0) {
                const txt = window.locale === 'id' ? 'Belum ada komentar. Jadilah yang pertama!' : 'No comments yet. Be the first!';
                container.innerHTML = `<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-5">${txt}</p>`;
                return;
            }
            let html = '<div class="space-y-4">';
            commentsArray.forEach(c => { html += window.renderCommentWithReplies(c, 0, postinganId); });
            html += '</div>';
            container.innerHTML = html;
            window.attachCommentEventListeners(container, postinganId);
            window.refreshTranslations?.();
        } else {
            container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar</p>';
        }
    } catch (error) {
        container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar: ' + error.message + '</p>';
    }
};

window.renderCommentWithReplies = function (comment, level, postinganId) {
    const marginLeft = Math.min(level * 28, 56);
    const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
    const isLoggedIn = window.currentUserId !== null && window.currentUserId !== 'null';
    const userName = escapeHtml(comment.user?.nama_mahasiswa || 'User');
    const commentText = escapeHtml(comment.komentar);
    const commentId = String(comment.id_komentar);
    const replyText = window.locale === 'id' ? 'Balas' : 'Reply';
    const editText = 'Edit';
    const deleteText = window.locale === 'id' ? 'Hapus' : 'Delete';
    const userPortfolioUrl = comment.user?.username ? `/${window.locale}/portofolio?user=${comment.user.username}` : '#';
    const avatarHtml = comment.user?.username ? `<a href="${userPortfolioUrl}">${getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs')}</a>` : getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs');
    const nameHtml = comment.user?.username
        ? `<a href="${userPortfolioUrl}" class="font-semibold text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 transition-colors">${userName}</a>`
        : `<span class="font-semibold text-sm text-gray-900 dark:text-gray-100">${userName}</span>`;

    let html = `<div class="comment-item transition-all duration-200 py-2" data-comment-id="${commentId}" data-postingan-id="${postinganId}" style="margin-left:${marginLeft}px;">
        <div class="flex gap-3">${avatarHtml}<div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">${nameHtml}<span class="text-xs text-gray-500">${formatDate(comment.tanggal || comment.created_at)}</span></div>
            <p class="comment-text text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed" id="comment-text-${commentId}">${commentText}</p>
            <div class="flex flex-wrap gap-3 mt-2">`;
    if (isLoggedIn) html += `<button class="reply-btn text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors inline-flex items-center gap-1" data-action="reply" data-comment-id="${commentId}" data-postingan-id="${postinganId}"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>${replyText}</button>`;
    if (isOwnComment) html += `<button class="edit-comment-btn text-xs text-blue-500 hover:text-blue-700 transition-colors inline-flex items-center gap-1" data-action="edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>${editText}</button><button class="delete-comment-btn text-xs text-red-500 hover:text-red-700 transition-colors inline-flex items-center gap-1" data-action="delete" data-comment-id="${commentId}" data-postingan-id="${postinganId}" data-type="full"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>${deleteText}</button>`;
    html += `</div></div></div><div id="reply-form-${commentId}" class="reply-form-container hidden mt-3 ml-11"></div></div>`;
    if (comment.balasan?.length) {
        html += `<div class="replies-container ml-4 mt-1">`;
        comment.balasan.forEach(r => { html += window.renderCommentWithReplies(r, level + 1, postinganId); });
        html += `</div>`;
    }
    return html;
};

window.attachCommentEventListeners = function (container, postinganId) {
    if (container.hasAttribute('data-delegated')) return;
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
};

window.submitComment = async function (postinganId) {
    const textarea = document.getElementById(`comment-input-${postinganId}`);
    const commentText = textarea.value.trim();
    if (!commentText) { window.showPageInfo?.('Komentar tidak boleh kosong', 'warning', 2000); return; }
    const submitBtn = textarea.closest('.flex-1')?.querySelector('.submit-comment-btn');
    const origHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) { submitBtn.classList.add('btn-loading'); submitBtn.innerHTML = 'Mengirim...'; submitBtn.disabled = true; }
    try {
        const r = await fetch(`/${window.locale}/komentar`, { method: 'POST', headers: getHeaders(), body: JSON.stringify({ id_postingan: postinganId, komentar: commentText }) });
        const data = await r.json();
        if (data.success) { textarea.value = ''; await window.loadComments(postinganId); await window.updateCommentCount(postinganId, 1); window.showPageInfo?.('Komentar berhasil ditambahkan', 'success', 2000); }
        else window.showErrorAlert?.(data.message || 'Gagal');
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message); }
    finally { if (submitBtn) { submitBtn.classList.remove('btn-loading'); submitBtn.innerHTML = origHtml; submitBtn.disabled = false; } }
};

window.submitReply = async function (form, postinganId) {
    if (form.hasAttribute('data-submitting')) return;
    form.setAttribute('data-submitting', 'true');
    const textarea = form.querySelector('textarea[name="komentar"]');
    const commentText = textarea.value.trim();
    const parentId = form.querySelector('input[name="parent_id"]').value;
    if (!commentText) { window.showPageInfo?.('Balasan tidak boleh kosong', 'warning', 2000); form.removeAttribute('data-submitting'); return; }
    const submitBtn = form.querySelector('button[type="submit"]');
    const cancelBtn = form.querySelector('button[type="button"]');
    submitBtn.disabled = true; if (cancelBtn) cancelBtn.disabled = true;
    try {
        const r = await fetch(`/${window.locale}/komentar`, { method: 'POST', headers: getHeaders(), body: JSON.stringify({ id_postingan: postinganId, komentar: commentText, parent_id: parentId, reply_to_id: parentId }) });
        const data = await r.json();
        if (data.success) {
            await window.loadComments(postinganId); await window.updateCommentCount(postinganId, 1);
            const rc = document.getElementById(`reply-form-${parentId}`);
            if (rc) { rc.classList.add('hidden'); rc.innerHTML = ''; }
            window.showPageInfo?.('Balasan berhasil ditambahkan', 'success', 2000);
        } else window.showErrorAlert?.(data.message || 'Gagal');
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message); }
    finally { submitBtn.disabled = false; if (cancelBtn) cancelBtn.disabled = false; form.removeAttribute('data-submitting'); }
};

window.showReplyForm = function (parentCommentId, postinganId) {
    parentCommentId = String(parentCommentId);
    const rc = document.getElementById(`reply-form-${parentCommentId}`);
    if (!rc) return;
    const sendText = window.locale === 'id' ? 'Kirim' : 'Send';
    const cancelText = window.locale === 'id' ? 'Batal' : 'Cancel';
    const ph = window.locale === 'id' ? 'Tulis balasan...' : 'Write a reply...';
    if (rc.innerHTML.trim() !== '' && !rc.classList.contains('hidden')) { rc.classList.add('hidden'); rc.innerHTML = ''; return; }
    rc.innerHTML = `<form class="reply-submit-form mt-3"><input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}"><input type="hidden" name="parent_id" value="${parentCommentId}"><div class="flex flex-col gap-2"><textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none" placeholder="${ph}"></textarea><div class="flex gap-2 justify-end"><button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">${sendText}</button><button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden');this.closest('.reply-form-container').innerHTML='';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg">${cancelText}</button></div></div></form>`;
    rc.classList.remove('hidden');
    rc.querySelector('form').onsubmit = async (e) => { e.preventDefault(); await window.submitReply(rc.querySelector('form'), postinganId); };
};

window.showEditForm = function (commentId, postinganId) {
    commentId = String(commentId);
    const commentTextEl = document.getElementById(`comment-text-${commentId}`);
    if (!commentTextEl) return;
    const originalText = commentTextEl.innerText;
    const commentItem = commentTextEl.closest('.comment-item');
    const existing = document.getElementById(`edit-form-${commentId}`);
    if (existing) { existing.remove(); commentTextEl.style.display = 'block'; const eb = commentItem.querySelector('.edit-comment-btn'); if (eb) eb.style.display = 'inline-flex'; return; }
    const saveText = window.locale === 'id' ? 'Simpan' : 'Save';
    const cancelText = window.locale === 'id' ? 'Batal' : 'Cancel';
    const ef = document.createElement('div');
    ef.className = 'edit-form mt-2'; ef.id = `edit-form-${commentId}`;
    ef.innerHTML = `<textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none" rows="2">${escapeHtml(originalText)}</textarea><div class="flex gap-2 mt-2"><button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg" data-action="save-edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">${saveText}</button><button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg" data-action="cancel-edit" data-comment-id="${commentId}">${cancelText}</button></div>`;
    commentTextEl.style.display = 'none';
    commentTextEl.parentNode.insertBefore(ef, commentTextEl.nextSibling);
    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'none';
};

window.saveEdit = async function (commentId, postinganId) {
    commentId = String(commentId);
    const ef = document.getElementById(`edit-form-${commentId}`);
    if (!ef) return;
    const et = ef.querySelector('.edit-textarea');
    if (!et) return;
    const newText = et.value.trim();
    if (!newText) { window.showPageInfo?.('Komentar tidak boleh kosong', 'warning', 2000); return; }
    const saveBtn = ef.querySelector('.save-edit-btn');
    if (!saveBtn) return;
    const origHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<div class="comment-loading" style="width:14px;height:14px;"></div>'; saveBtn.disabled = true;
    try {
        const lu = window.commentLastUpdated?.[postinganId] ?? '';
        const r = await fetch(`/${window.locale}/komentar/update?id=${commentId}&id_postingan=${postinganId}&last_updated=${encodeURIComponent(lu)}`, { method: 'PUT', headers: getHeaders(), body: JSON.stringify({ komentar: newText }) });
        const data = await r.json();
        if (data.success) { await window.loadComments(postinganId); window.showPageInfo?.('Komentar berhasil diperbarui', 'success', 2000); }
        else if (data.code === 'STALE_DATA') { await window.loadComments(postinganId); window.showPageInfo?.('Komentar diperbarui pengguna lain. Edit ulang jika perlu.', 'warning', 3000); }
        else { window.showErrorAlert?.(data.message || 'Gagal'); saveBtn.innerHTML = origHtml; saveBtn.disabled = false; }
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message); saveBtn.innerHTML = origHtml; saveBtn.disabled = false; }
};

window.cancelEdit = function (commentId) {
    commentId = String(commentId);
    const ct = document.getElementById(`comment-text-${commentId}`);
    const ef = document.getElementById(`edit-form-${commentId}`);
    ct.style.display = 'block'; if (ef) ef.remove();
    const editBtn = ct.closest('.comment-item').querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'inline-flex';
};

window.deleteComment = async function (commentId, postinganId, type = 'full') {
    commentId = String(commentId); postinganId = String(postinganId);
    const msg = type === 'single'
        ? (window.locale === 'id' ? 'Apakah Anda yakin ingin menghapus balasan ini saja?' : 'Are you sure you want to delete this reply only?')
        : (window.locale === 'id' ? 'Apakah Anda yakin ingin menghapus komentar ini beserta semua balasannya?' : 'Are you sure you want to delete this comment and all its replies?');
    const confirmed = window.showConfirm ? await window.showConfirm(msg) : confirm(msg);
    if (!confirmed) return;
    window.showLoading?.('Menghapus...');
    try {
        const r = await fetch(`/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`, { method: 'DELETE', headers: getHeaders() });
        const data = await r.json();
        if (data.success) { await window.loadComments(postinganId); await window.updateCommentCount(postinganId, -1); window.showPageInfo?.('Komentar berhasil dihapus', 'success', 2000); }
        else window.showErrorAlert?.(data.message || 'Gagal');
    } catch (e) { window.showErrorAlert?.('Terjadi kesalahan: ' + e.message); }
    finally { window.closeLoading?.(); }
};

window.updateCommentCount = async function (postinganId, delta) {
    const el = document.querySelector(`.comment-toggle[data-postingan-id="${postinganId}"] .comment-count`);
    if (el) el.innerText = Math.max(0, (parseInt(el.innerText) || 0) + delta);
};

// ==========================================
// MODAL HELPERS (cp prefix = create post)
// ==========================================
window.cpOpenModal = function (id) { const el = document.getElementById(id); if (el) { el.classList.add('show'); document.body.style.overflow = 'hidden'; } };
window.cpCloseModal = function (id) { const el = document.getElementById(id); if (el) { el.classList.remove('show'); document.body.style.overflow = ''; } };
window.cpOverlayClick = function (e, id) { if (e.target === document.getElementById(id)) window.cpCloseModal(id); };
window.cpPreviewImage = function (e, imgId, wrapId) {
    const file = e.target.files[0]; if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => { const img = document.getElementById(imgId); const wrap = document.getElementById(wrapId); if (img) img.src = ev.target.result; if (wrap) wrap.style.display = 'block'; };
    reader.readAsDataURL(file);
};
window.cpRemovePreview = function (imgId, wrapId, inputId) {
    const img = document.getElementById(imgId); const wrap = document.getElementById(wrapId); const input = document.getElementById(inputId);
    if (img) img.src = ''; if (wrap) wrap.style.display = 'none'; if (input) input.value = '';
};

// ==========================================
// SPARKLINE CHARTS
// ==========================================
window.createSparkline = function (canvasId, borderColor) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    if (window.cpCharts[canvasId]) window.cpCharts[canvasId].destroy();
    const data = Array.from({ length: 7 }, () => Math.floor(Math.random() * 40) + 10);
    window.cpCharts[canvasId] = new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: { labels: Array(7).fill(''), datasets: [{ data, borderColor, backgroundColor: borderColor + '22', tension: 0.4, pointRadius: 0, borderWidth: 2, fill: true }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false }, y: { display: false } } }
    });
};

// ==========================================
// LIKE / SHARE / COMMENT TOGGLE LISTENERS
// ==========================================
window.setupCommentToggleListeners = function () {
    document.querySelectorAll('.comment-toggle').forEach(btn => {
        btn.removeEventListener('click', window.handleCommentToggle);
        btn.addEventListener('click', window.handleCommentToggle);
    });
};
window.handleCommentToggle = async function (event) {
    const btn = event.currentTarget;
    const postCard = btn.closest('.post-card');
    if (!postCard) return;
    const commentSection = postCard.querySelector('.comment-section');
    const isHidden = commentSection.style.display !== 'block';
    commentSection.style.display = isHidden ? 'block' : 'none';
    if (isHidden) { const postinganId = btn.dataset.postinganId; if (postinganId) await window.loadComments(postinganId); }
};

window.setupShareListeners = function () {
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.removeEventListener('click', window.handleShare);
        btn.addEventListener('click', window.handleShare);
    });
};
window.fallbackCopyToClipboard = function (text, btn) {
    const textarea = document.createElement('textarea');
    textarea.value = text; textarea.style.cssText = 'position:fixed;opacity:0;';
    document.body.appendChild(textarea);
    try {
        textarea.select();
        if (document.execCommand('copy')) {
            const origHtml = btn.innerHTML;
            btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
            window.showPageInfo?.('Link berhasil disalin!', 'success', 1500);
        }
    } catch (err) { window.showErrorAlert?.('Gagal menyalin URL'); }
    finally { document.body.removeChild(textarea); }
};
window.handleShare = function (event) {
    const btn = event.currentTarget;
    const postCard = btn.closest('.post-card');
    if (!postCard) return;
    const url = postCard.dataset.shareUrl || window.location.href;
    const title = postCard.querySelector('h3')?.innerText || 'Postingan Menarik';
    if (navigator.share) { navigator.share({ title, url }).catch(() => { }); }
    else if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(url).then(() => {
            const origHtml = btn.innerHTML;
            btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
            window.showPageInfo?.('Link berhasil disalin!', 'success', 1500);
        }).catch(() => window.fallbackCopyToClipboard(url, btn));
    } else window.fallbackCopyToClipboard(url, btn);
};

window.setupLikeListeners = function () {
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.removeEventListener('click', window.handleLike);
        btn.addEventListener('click', window.handleLike);
    });
};
window.handleLike = async function (event) {
    event.preventDefault(); event.stopPropagation();
    const likeBtn = event.currentTarget;
    const postinganId = likeBtn.getAttribute('data-postingan-id');
    try {
        const response = await fetch(`/${window.locale}/postingan/toggle-like?id=${postinganId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
        const data = await response.json();
        if (data.success) {
            const countEl = likeBtn.querySelector('.like-count');
            if (countEl) countEl.textContent = data.like_count;
            const svg = likeBtn.querySelector('svg');
            if (data.liked) { likeBtn.classList.add('text-red-500'); svg?.classList.add('fill-current', 'text-red-500'); }
            else { likeBtn.classList.remove('text-red-500'); svg?.classList.remove('fill-current', 'text-red-500'); }
        }
    } catch (error) { console.error('Error:', error); }
};

window.sortPostinganByGame = function () {
    const container = document.getElementById('postingan-container');
    if (!container) return;
    const posts = Array.from(container.children);
    posts.sort((a, b) => {
        const aHas = a.querySelector('.bg-teal-600') !== null;
        const bHas = b.querySelector('.bg-teal-600') !== null;
        return (aHas === bHas) ? 0 : aHas ? -1 : 1;
    });
    container.innerHTML = ''; posts.forEach(p => container.appendChild(p));
};

// ==========================================
// DOSEN CAROUSEL
// ==========================================
window.initDosenCarousel = function () {
    const dosenCards = document.querySelectorAll('.dosen-card');
    const dots = document.querySelectorAll('.dot');
    if (!dosenCards.length) return;
    let current = 0;
    const visibleDots = 4, dotSize = 20;
    function updateCarousel() {
        dosenCards.forEach((card, i) => {
            const offset = i - current;
            const name = card.querySelector('.dosen-name');
            card.style.zIndex = offset === 0 ? '3' : (Math.abs(offset) === 1 ? '2' : '1');
            card.style.opacity = offset === 0 ? '1' : (Math.abs(offset) === 1 ? '0.6' : '0');
            card.style.transform = offset === 0 ? 'translateX(0) scale(1)' : (offset === -1 ? 'translateX(-120px) scale(0.8)' : (offset === 1 ? 'translateX(120px) scale(0.8)' : 'translateX(0) scale(0.5)'));
            if (name) name.style.opacity = offset === 0 ? '1' : '0';
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-blue-500', i === current); dot.classList.toggle('opacity-100', i === current);
            dot.classList.toggle('bg-gray-300', i !== current); dot.classList.toggle('opacity-40', i !== current);
        });
        const offsetIndex = Math.max(0, Math.min(current - Math.floor(visibleDots / 2), dots.length - visibleDots));
        const dt = document.getElementById('dosenDotsTrack');
        if (dt) dt.style.transform = `translateX(${-(offsetIndex * dotSize)}px)`;
    }
    const nextBtn = document.getElementById('dosenNextBtn');
    const prevBtn = document.getElementById('dosenPrevBtn');
    if (nextBtn) nextBtn.onclick = () => { current = (current + 1) % dosenCards.length; updateCarousel(); };
    if (prevBtn) prevBtn.onclick = () => { current = (current - 1 + dosenCards.length) % dosenCards.length; updateCarousel(); };
    dots.forEach(dot => { dot.onclick = () => { current = parseInt(dot.dataset.index); updateCarousel(); }; });
    updateCarousel();
    setInterval(() => { current = (current + 1) % dosenCards.length; updateCarousel(); }, 5000);
};

// ==========================================
// DASHBOARD INIT (skeleton reveal + typing)
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        ['postingan-skeleton', 'project-skeleton', 'sertifikat-skeleton', 'learning-skeleton', 'learning-skeleton-sidebar', 'total-lrn-skeleton', 'total-pjt-skeleton', 'total-stk-skeleton']
            .forEach(id => document.getElementById(id)?.classList.add('hidden'));
        ['postingan-content-wrapper', 'project-content-wrapper', 'sertifikat-content-wrapper', 'learning-content-wrapper', 'learning-content-wrapper-sidebar', 'total-lrn-wrapper', 'total-pjt-wrapper', 'total-stk-wrapper']
            .forEach(id => document.getElementById(id)?.classList.remove('hidden'));
        window.sortPostinganByGame?.();
        window.setupCommentToggleListeners?.();
        window.setupShareListeners?.();
        window.setupLikeListeners?.();
        window.initDosenCarousel?.();
    }, 800);

    // Typing animation for create-post placeholder
    const el = document.getElementById('cp-typed-text');
    if (el) {
        const phrases = ['Apa yang ingin Anda bagikan hari ini?', 'Bagikan pengalaman terbaru Anda...', 'Ceritakan sesuatu yang menarik...', 'Tulis postingan baru sekarang...'];
        let pIdx = 0, cIdx = 0, deleting = false;
        function tick() {
            const phrase = phrases[pIdx];
            if (!deleting) { cIdx++; el.textContent = phrase.slice(0, cIdx); if (cIdx === phrase.length) { deleting = true; setTimeout(tick, 2200); return; } setTimeout(tick, 60); }
            else { cIdx--; el.textContent = phrase.slice(0, cIdx); if (cIdx === 0) { deleting = false; pIdx = (pIdx + 1) % phrases.length; setTimeout(tick, 500); return; } setTimeout(tick, 30); }
        }
        tick();
    }

    // Modal Item Adder for create post
    let cpExtraIdx = 0;
    const addBtn = document.getElementById('cp-add-item-post');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            const container = document.getElementById('cp-items-post');
            const row = document.createElement('div');
            row.className = 'cp-item-row';
            row.innerHTML = `<div class="cp-item-top"><select name="items[${cpExtraIdx}][type]" class="cp-item-select cp-type-sel"><option value="image">Gambar</option><option value="link">Link / Referensi</option></select><button type="button" class="cp-item-remove" onclick="this.closest('.cp-item-row').remove()">Hapus</button></div><div class="cp-item-body"><input type="file" name="items[${cpExtraIdx}][file]" accept="image/*" class="cp-file-field block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"><input type="url" name="items[${cpExtraIdx}][content]" class="cp-input cp-link-field-extra hidden" placeholder="https://example.com"></div>`;
            container.appendChild(row);
            const sel = row.querySelector('.cp-type-sel');
            const file = row.querySelector('.cp-file-field');
            const link = row.querySelector('.cp-link-field-extra');
            sel.addEventListener('change', function () {
                file.classList.toggle('hidden', this.value !== 'image'); link.classList.toggle('hidden', this.value !== 'link');
                file.disabled = this.value !== 'image'; link.disabled = this.value !== 'link';
            });
            link.classList.add('hidden'); link.disabled = true;
            cpExtraIdx++;
        });
    }

    if (document.getElementById('learningChart')) window.createSparkline('learningChart', '#8b5cf6');
    if (document.getElementById('projectChart')) window.createSparkline('projectChart', '#f97316');
    if (document.getElementById('sertifikatChart')) window.createSparkline('sertifikatChart', '#f59e0b');
});
