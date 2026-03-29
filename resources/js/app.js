// ===== LMS SMK Tunas Harapan - Main JavaScript =====

// Dark Mode Toggle
function toggleDarkMode() {
    const html = document.documentElement;
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('darkMode', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('darkMode', 'dark');
    }
    updateDarkModeIcon();
}

// Initialize dark mode from localStorage
function initDarkMode() {
    const savedMode = localStorage.getItem('darkMode');
    if (savedMode === 'dark' || (!savedMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }
    updateDarkModeIcon();
}

// Update dark mode icon visibility
function updateDarkModeIcon() {
    const isDark = document.documentElement.classList.contains('dark');
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');
    if (sunIcon && moonIcon) {
        sunIcon.classList.toggle('hidden', isDark);
        moonIcon.classList.toggle('hidden', !isDark);
    }
}

// Sidebar Toggle (mobile)
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar) {
        sidebar.classList.toggle('-translate-x-full');
    }
    if (overlay) {
        overlay.classList.toggle('hidden');
    }
}

// Copy to clipboard
function copyToClipboard(text, buttonElement) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = buttonElement.textContent;
        buttonElement.textContent = 'Tersalin!';
        buttonElement.classList.add('text-green-500');
        setTimeout(() => {
            buttonElement.textContent = originalText;
            buttonElement.classList.remove('text-green-500');
        }, 2000);
    });
}

// Confirm delete dialog
function confirmDelete(message) {
    return confirm(message || 'Apakah Anda yakin ingin menghapus data ini?');
}

// Format time ago (Indonesian)
function timeAgo(dateString) {
    const now = new Date();
    const date = new Date(dateString);
    const seconds = Math.floor((now - date) / 1000);
    
    const intervals = {
        tahun: 31536000,
        bulan: 2592000,
        minggu: 604800,
        hari: 86400,
        jam: 3600,
        menit: 60
    };
    
    for (const [label, secondsInInterval] of Object.entries(intervals)) {
        const count = Math.floor(seconds / secondsInInterval);
        if (count >= 1) {
            return `${count} ${label} yang lalu`;
        }
    }
    
    return 'Baru saja';
}

// Deadline countdown
function updateCountdowns() {
    document.querySelectorAll('[data-deadline]').forEach(el => {
        const deadline = new Date(el.dataset.deadline);
        const now = new Date();
        const diff = deadline - now;
        
        if (diff <= 0) {
            el.textContent = 'Sudah lewat';
            el.className = el.className.replace(/text-\w+-\d+/g, '') + ' text-red-500';
            return;
        }
        
        const days = Math.floor(diff / 86400000);
        const hours = Math.floor((diff % 86400000) / 3600000);
        const minutes = Math.floor((diff % 3600000) / 60000);
        
        let text = '';
        if (days > 0) text += `${days} hari `;
        if (hours > 0) text += `${hours} jam `;
        text += `${minutes} menit`;
        
        el.textContent = text;
        
        if (days < 1) {
            el.className = el.className.replace(/text-\w+-\d+/g, '') + ' text-red-500 font-semibold';
        } else if (days < 3) {
            el.className = el.className.replace(/text-\w+-\d+/g, '') + ' text-yellow-500';
        }
    });
}

// AJAX notification polling
function pollNotifications() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notifBadge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        })
        .catch(() => {});
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initDarkMode();
    updateCountdowns();
    setInterval(updateCountdowns, 60000); // Update countdowns every minute
    setInterval(pollNotifications, 30000); // Poll notifications every 30 seconds
    
    // Flash message auto-dismiss
    document.querySelectorAll('.flash-alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
