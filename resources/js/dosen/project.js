import Swal from 'sweetalert2';

function initDosenProjectPage() {
    const clearEl = document.getElementById('clear-local-storage');
    if (clearEl) {
        const key = clearEl.dataset.key;
        if (key) {
            localStorage.removeItem(key);
        }
    }

    const projectPage = document.querySelector('[data-dosen-project-page]') || document.querySelector('#selectedProjectIds');
    if (!projectPage) return;

    window.handleSingleDelete = async function (button, projectId, event) {
        if (event) event.preventDefault();
        const confirmed = await window.showConfirm?.();
        if (!confirmed) return;

        Swal.fire({
            title: 'Menghapus...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        const form = document.createElement('form');
        form.method = 'POST';
        const locale = document.querySelector('html').getAttribute('lang') || 'id';
        form.action = `/${locale}/dosen/manageProject/DeleteProject?id=${projectId}`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    };

    window.confirmBulkDeleteProjects = async function () {
        const checkedCheckboxes = document.querySelectorAll('.project-checkbox:checked');
        const ids = Array.from(checkedCheckboxes).map((cb) => cb.dataset.projectId);

        if (ids.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak ada yang dipilih',
                text: 'Pilih minimal satu project terlebih dahulu.',
                confirmButtonColor: '#4f46e5',
            });
            return;
        }

        const confirmed = await window.showConfirm?.();
        if (!confirmed) return;

        Swal.fire({
            title: 'Menghapus...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        const bulkForm = document.getElementById('bulkDeleteForm');
        if (bulkForm) {
            document.getElementById('selectedProjectIds').value = ids.join(',');
            bulkForm.submit();
        }
    };

    window.updateSelectionState = function () {
        const checkedCount = document.querySelectorAll('.project-checkbox:checked').length;
        const selectedCountEl = document.getElementById('selectedCount');
        if (selectedCountEl) selectedCountEl.textContent = checkedCount;
    };

    document.querySelectorAll('.project-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', window.updateSelectionState);
    });

    const selectAll = document.getElementById('selectAllCheckbox');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.project-checkbox').forEach((cb) => {
                if (!cb.disabled) cb.checked = this.checked;
            });
            window.updateSelectionState();
        });
    }

    window.updateSelectionState();

    if (typeof window.showPageInfo === 'function') {
        window.showPageInfo('popup.dosen_projects');
    }
}

document.addEventListener('DOMContentLoaded', initDosenProjectPage);
document.addEventListener('turbo:load', initDosenProjectPage);
