/**
 * resources/js/admin/dashboard.js
 * Admin Dashboard — index.blade.php
 */

document.addEventListener('DOMContentLoaded', function () {
    // ===== SKELETON LOADER =====
    // Use a small delay so the page content renders first
    setTimeout(() => {
        document.querySelectorAll('[id$="-skeleton"]').forEach(skeleton => {
            skeleton.classList.add('hidden');

            const wrapperId = skeleton.id.replace('-skeleton', '-wrapper');
            const wrapper = document.getElementById(wrapperId);

            if (wrapper) {
                wrapper.classList.remove('hidden');
            }
        });

        if (typeof sortPostinganByGame === 'function') {
            sortPostinganByGame();
        }
    }, 800);

    // ===== EMAIL TOGGLE =====
    let allEmailsVisible = false;

    function toggleIndividualEmail(button) {
        const container = button.closest('.relative');
        const emailDisplay = container.querySelector('.email-display');
        const eyeShow = button.querySelector('.email-eye-show');
        const eyeHide = button.querySelector('.email-eye-hide');

        if (emailDisplay.textContent === '••••••••') {
            emailDisplay.textContent = emailDisplay.dataset.email;
            eyeShow.classList.add('hidden');
            eyeHide.classList.remove('hidden');
        } else {
            emailDisplay.textContent = '••••••••';
            eyeShow.classList.remove('hidden');
            eyeHide.classList.add('hidden');
        }
    }

    function toggleAllEmails(show) {
        document.querySelectorAll('.email-display').forEach(display => {
            const container = display.closest('.relative');
            const button = container.querySelector('.email-toggle-btn');
            const eyeShow = button.querySelector('.email-eye-show');
            const eyeHide = button.querySelector('.email-eye-hide');

            if (show) {
                display.textContent = display.dataset.email;
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                display.textContent = '••••••••';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        });

        // Update global button icons
        const globalEyeShow = document.getElementById('global-eye-show');
        const globalEyeHide = document.getElementById('global-eye-hide');
        if (show) {
            globalEyeShow.classList.add('hidden');
            globalEyeHide.classList.remove('hidden');
        } else {
            globalEyeShow.classList.remove('hidden');
            globalEyeHide.classList.add('hidden');
        }
        allEmailsVisible = show;
    }

    // Attach event listeners to all email toggle buttons
    document.querySelectorAll('.email-toggle-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleIndividualEmail(this);
        });
    });

    // Global email toggle button
    const globalToggle = document.getElementById('globalEmailToggle');
    if (globalToggle) {
        globalToggle.addEventListener('click', function() {
            toggleAllEmails(!allEmailsVisible);
        });
    }

    // ===== SECTION TOGGLES =====
    const toggleActivity = document.getElementById('toggleActivity');
    const togglePending = document.getElementById('togglePending');
    const toggleRejected = document.getElementById('toggleRejected');

    const toggleSection = (button, selector) => {
        if (!button) return;

        const icon = button.querySelector('svg');

        button.addEventListener('click', () => {
            const items = document.querySelectorAll(selector);
            const isHidden = items.length && items[0].classList.contains('hidden');

            items.forEach(item => item.classList.toggle('hidden', !isHidden));

            // Rotate icon and change text
            if (icon) {
                icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                icon.style.transition = 'transform 0.3s ease';
            }

            const span = button.querySelector('span');
            if (span) {
                span.textContent = isHidden ? 'Sembunyikan' : 'Tampilkan Lebih Banyak';
            }
        });
    };

    toggleSection(toggleActivity, '.extra-activity');
    toggleSection(togglePending, '.extra-pending');
    toggleSection(toggleRejected, '.extra-rejected');

    // ===== CHART.JS SPARKLINES =====
    if (typeof Chart !== 'undefined') {
        const isDark = document.documentElement.classList.contains('dark');

        function createSparkline(canvasId, borderColor) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const data = Array.from({ length: 10 }, () => Math.floor(Math.random() * 40) + 15);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array(data.length).fill(''),
                    datasets: [{
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: borderColor + '15',
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },
                    elements: {
                        line: { borderJoinStyle: 'round' }
                    }
                }
            });
        }

        // Create all sparkline charts
        createSparkline('chart-users', '#3B82F6');
        createSparkline('chart-students', '#10B981');
        createSparkline('chart-admin', '#F59E0B');
        createSparkline('chart-lecturers', '#8B5CF6');
    }

});
