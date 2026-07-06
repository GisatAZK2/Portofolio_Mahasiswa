/**
 * resources/js/admin/edit-project-init.js
 * Admin Edit Project Init — projects/views_edit_project.blade.php
 */

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('admin-project-edit-container');
    if (container && typeof window.initAdminProjectEditPage === 'function') {
        window.initAdminProjectEditPage(container);
    }
});
