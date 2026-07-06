/**
 * resources/js/admin/manage-project.js
 * Admin Manage Project — project.blade.php
 */

// ===== FILTER FUNCTIONS =====
window.applyFilters = function() {
    const searchTerm = document.getElementById('searchProject').value;
    const statusFilter = document.getElementById('filterStatus').value;

    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    let url = `/${locale}/admin/manageProject`;
    let params = new URLSearchParams();

    if (searchTerm) {
        params.append('search', searchTerm);
    }
    if (statusFilter) {
        params.append('status', statusFilter);
    }

    if (params.toString()) {
        url += '?' + params.toString();
    }

    window.location.href = url;
};

window.resetFilters = function() {
    const locale = document.querySelector('html').getAttribute('lang') || 'id';
    window.location.href = `/${locale}/admin/manageProject`;
};

// ===== SELECTED PROJECTS =====
window.updateSelectedProjects = function() {
    const visibleCheckboxes = Array.from(document.querySelectorAll('.project-checkbox'))
        .filter(cb => cb.closest('.project-card')?.style.display !== 'none');
    const selectedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
    const selectedCountEl = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAllProjects');

    if (selectedCountEl) {
        selectedCountEl.textContent = selectedCheckboxes.length;
    }

    if (selectAllCheckbox) {
        if (selectedCheckboxes.length === visibleCheckboxes.length && visibleCheckboxes.length > 0) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (selectedCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
    }
};

// ===== BULK DELETE =====
window.confirmBulkDeleteProjects = async function() {
    const visibleCheckboxes = Array.from(document.querySelectorAll('.project-checkbox'))
        .filter(cb => cb.closest('.project-card')?.style.display !== 'none');
    const selectedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);
    const selectedIds = selectedCheckboxes.map(cb => cb.value);

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Ada Data Dipilih',
            text: 'Silakan pilih setidaknya satu project sebelum menghapus.',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    const result = await Swal.fire({
        title: 'Hapus Project Terpilih?',
        html: `${selectedIds.length} project akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    });

    if (result.isConfirmed) {
        document.getElementById('selectedProjectIds').value = JSON.stringify(selectedIds);
        document.getElementById('bulkDeleteForm').submit();
    }
};

// ===== SINGLE DELETE =====
window.confirmDeleteProject = function(projectId, projectName) {
    Swal.fire({
        title: 'Hapus Project?',
        html: `Apakah Anda yakin ingin menghapus project <strong>"${projectName}"</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteForm = document.getElementById('deleteProjectForm');
            const locale = document.querySelector('html').getAttribute('lang') || 'id';

            let url = `/${locale}/admin/manageProject/DeleteProject?id=${projectId}`;
            deleteForm.action = url;
            deleteForm.submit();
        }
    });
};

// ===== DOM READY =====
document.addEventListener('DOMContentLoaded', function() {
    // Search on Enter key
    const searchInput = document.getElementById('searchProject');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                window.applyFilters();
            }
        });
    }

    // Initial count
    const cards = document.querySelectorAll('.project-card');
    const filteredCount = document.getElementById('filteredCount');
    if (filteredCount) {
        filteredCount.textContent = cards.length;
    }

    // Checkbox listeners
    const projectCheckboxes = document.querySelectorAll('.project-checkbox');
    projectCheckboxes.forEach(cb => cb.addEventListener('change', window.updateSelectedProjects));

    const selectAllProjects = document.getElementById('selectAllProjects');
    if (selectAllProjects) {
        selectAllProjects.addEventListener('change', function () {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.project-checkbox'))
                .filter(cb => cb.closest('.project-card')?.style.display !== 'none');
            visibleCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            window.updateSelectedProjects();
        });
    }

    window.updateSelectedProjects();

    // ===== PAGE INFO =====
    if (typeof showPageInfo === 'function') {
        showPageInfo("popup.admin_projects");
    }
});
