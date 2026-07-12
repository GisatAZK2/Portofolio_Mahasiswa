function initDosenDashboardPage() {
    const dashboardRoot = document.querySelector('[data-dosen-dashboard]') || document.getElementById('learningChart') || document.getElementById('projectChart') || document.getElementById('sertifikatChart');
    if (!dashboardRoot) return;

    const hideSkeletons = () => {
        document.querySelectorAll('[id$="-skeleton"]').forEach((skeleton) => {
            skeleton.classList.add('hidden');
            const wrapperId = skeleton.id.replace('-skeleton', '-wrapper');
            const wrapper = document.getElementById(wrapperId);
            if (wrapper) wrapper.classList.remove('hidden');
        });

        if (typeof window.sortPostinganByGame === 'function') {
            window.sortPostinganByGame();
        }
    };

    window.addEventListener('load', () => {
        setTimeout(hideSkeletons, 800);
    }, { once: true });

    const loadChartJs = () => new Promise((resolve) => {
        if (window.Chart || document.querySelector('script[src*="chart.js"]')) {
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
        script.async = true;
        script.onload = resolve;
        script.onerror = resolve;
        document.head.appendChild(script);
    });

    const createSparkline = (canvasId, borderColor) => {
        const canvas = document.getElementById(canvasId);
        if (!canvas || typeof window.Chart === 'undefined') return;

        const ctx = canvas.getContext('2d');
        const data = Array.from({ length: 8 }, () => Math.floor(Math.random() * 45) + 10);

        new window.Chart(ctx, {
            type: 'line',
            data: {
                labels: Array(data.length).fill(''),
                datasets: [{
                    data,
                    borderColor,
                    backgroundColor: `${borderColor}15`,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: { x: { display: false }, y: { display: false } },
                elements: { line: { borderJoinStyle: 'round' } },
            },
        });
    };

    loadChartJs().then(() => {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#374151' : '#E5E7EB';
        if (document.getElementById('learningChart')) createSparkline('learningChart', '#8b5cf6');
        if (document.getElementById('projectChart')) createSparkline('projectChart', '#f97316');
        if (document.getElementById('sertifikatChart')) createSparkline('sertifikatChart', '#f59e0b');
        if (typeof window.showPageInfo === 'function') {
            window.showPageInfo('popup.dosen_dashboard');
        }
    });
}

document.addEventListener('DOMContentLoaded', initDosenDashboardPage);
document.addEventListener('turbo:load', initDosenDashboardPage);
