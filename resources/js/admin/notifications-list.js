/**
 * resources/js/admin/notifications-list.js
 * Admin Notifications List — notifikasi/views-notifications.blade.php
 */

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const headerCheckbox = document.getElementById('header-checkbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const rowCheckboxesMobile = document.querySelectorAll('.row-checkbox-mobile');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

    window.handleDeleteConfirm = async function (event) {
        event.preventDefault();

        const form = event.target;

        if (typeof showConfirm === 'function') {
            const confirmed = await showConfirm();
            if (confirmed) {
                form.submit();
            }
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                form.submit();
            }
        }

        return false;
    };

    function updateBulkDeleteButton() {
        if (!bulkDeleteBtn) return;
        const allCheckboxes = document.querySelectorAll('.row-checkbox:checked, .row-checkbox-mobile:checked');
        bulkDeleteBtn.disabled = allCheckboxes.length === 0;
        
        // Update hidden inputs in form to include all selected IDs
        const form = document.getElementById('bulk-action-form');
        if (form) {
            const existingInputs = form.querySelectorAll('input[name="selected_ids[]"]:not(.row-checkbox):not(.row-checkbox-mobile)');
            existingInputs.forEach(input => input.remove());
            
            allCheckboxes.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'selected_ids[]';
                hiddenInput.value = checkbox.value;
                form.appendChild(hiddenInput);
            });
        }
    }

    function updateSelectAllState() {
        const allCheckboxes = document.querySelectorAll('.row-checkbox, .row-checkbox-mobile');
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked, .row-checkbox-mobile:checked');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allCheckboxes.length > 0 && checkedBoxes.length === allCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < allCheckboxes.length;
        }
        if (headerCheckbox) {
            headerCheckbox.checked = selectAllCheckbox?.checked || false;
            headerCheckbox.indeterminate = selectAllCheckbox?.indeterminate || false;
        }
    }

    function selectAll(checked) {
        const allCheckboxes = document.querySelectorAll('.row-checkbox, .row-checkbox-mobile');
        allCheckboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });
        updateSelectAllState();
        updateBulkDeleteButton();
    }

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            selectAll(this.checked);
        });
    }
    
    if (headerCheckbox) {
        headerCheckbox.addEventListener('change', function() {
            selectAll(this.checked);
        });
    }

    // Individual checkbox listeners
    function checkboxChangeHandler() {
        updateSelectAllState();
        updateBulkDeleteButton();
    }

    function addCheckboxListeners() {
        const allCheckboxes = document.querySelectorAll('.row-checkbox, .row-checkbox-mobile');
        allCheckboxes.forEach(checkbox => {
            checkbox.removeEventListener('change', checkboxChangeHandler);
            checkbox.addEventListener('change', checkboxChangeHandler);
        });
    }
    
    addCheckboxListeners();
    
    // Initial update
    updateSelectAllState();
    updateBulkDeleteButton();
    
    // Handle dynamically added checkboxes (if any)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                addCheckboxListeners();
                updateSelectAllState();
            }
        });
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
});
