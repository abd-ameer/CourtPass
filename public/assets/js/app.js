/**
 * CourtPass Application JavaScript
 * Combines API fetch handler with CSRF support and UI interaction controllers.
 */

// API Client with CSRF token support
const CourtPass = (() => {
    const base = document.querySelector('meta[name="base-url"]')?.content || '';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    async function api(path, { method = 'GET', data = null } = {}) {
        const options = {
            method,
            headers: { 'Accept': 'application/json', 'X-CSRF-Token': csrf },
            credentials: 'same-origin',
        };
        if (data !== null) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }
        const response = await fetch(base + path, options);
        const body = await response.json().catch(() => ({}));
        if (!response.ok) {
            throw Object.assign(new Error(body.error || 'Request failed'), { status: response.status, body });
        }
        return body;
    }

    return { api, base };
})();

// UI & Component Helpers
const CourtPassApp = {
    // Toast Notification System
    showToast: function(type = 'success', title = 'Success', message = '') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="modal-close" onclick="this.parentElement.remove()" style="font-size: 16px;">&times;</button>
        `;

        container.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 4500);
    },

    // Modal Management
    openModal: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    },

    closeModal: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    },

    // Dropdown Toggles
    toggleDropdown: function(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    },

    // Universal Sidebar Toggle (Desktop collapse & Mobile offcanvas)
    toggleSidebar: function() {
        const layout = document.querySelector('.dashboard-layout');
        const sidebar = document.querySelector('.dashboard-sidebar');
        if (window.innerWidth <= 1024) {
            if (sidebar) sidebar.classList.toggle('open');
        } else {
            if (layout) layout.classList.toggle('sidebar-collapsed');
        }
    },

    // Form Validation Engine
    setupFormValidation: function(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredInputs = form.querySelectorAll('[required]');

            requiredInputs.forEach(input => {
                const feedback = input.parentElement.querySelector('.form-feedback');
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    if (feedback) feedback.textContent = 'This field is required.';
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }

                // Email validation
                if (input.type === 'email' && input.value.trim()) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.value.trim())) {
                        input.classList.add('is-invalid');
                        if (feedback) feedback.textContent = 'Please enter a valid email address.';
                        isValid = false;
                    }
                }
            });

            // Password matching
            const pwd = form.querySelector('input[name="password"]');
            const confirmPwd = form.querySelector('input[name="confirm_password"]');
            if (pwd && confirmPwd && pwd.value !== confirmPwd.value) {
                confirmPwd.classList.add('is-invalid');
                const feedback = confirmPwd.parentElement.querySelector('.form-feedback');
                if (feedback) feedback.textContent = 'Passwords do not match.';
                isValid = false;
            }

            // Resale price cap validation (<= 90%)
            const originalPriceInput = form.querySelector('[data-original-price]');
            const resalePriceInput = form.querySelector('[name="resale_price"]');
            if (originalPriceInput && resalePriceInput) {
                const orig = parseFloat(originalPriceInput.dataset.originalPrice);
                const resale = parseFloat(resalePriceInput.value);
                const maxAllowed = orig * 0.90;
                const feedback = resalePriceInput.parentElement.querySelector('.form-feedback');
                if (resale > maxAllowed) {
                    resalePriceInput.classList.add('is-invalid');
                    if (feedback) feedback.textContent = `Resale price cannot exceed LKR ${maxAllowed.toLocaleString()} (90% price cap).`;
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                CourtPassApp.showToast('error', 'Validation Error', 'Please correct the highlighted fields before submitting.');
            }
        });

        // Real-time password strength meter
        const passwordInput = form.querySelector('input[name="password"]');
        const meterFill = form.querySelector('.password-meter-fill');
        if (passwordInput && meterFill) {
            passwordInput.addEventListener('input', function() {
                const val = this.value;
                let score = 0;
                if (val.length >= 8) score += 25;
                if (/[A-Z]/.test(val)) score += 25;
                if (/[0-9]/.test(val)) score += 25;
                if (/[^A-Za-z0-9]/.test(val)) score += 25;

                meterFill.style.width = score + '%';
                if (score <= 25) meterFill.style.backgroundColor = '#e74c3c';
                else if (score <= 50) meterFill.style.backgroundColor = '#e59c00';
                else if (score <= 75) meterFill.style.backgroundColor = '#3498db';
                else meterFill.style.backgroundColor = '#00b562';
            });
        }
    },

    // Generic Confirmation Dialog Trigger
    confirmDialog: function(title, message, confirmBtnText, onConfirm) {
        let modal = document.getElementById('globalConfirmModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'globalConfirmModal';
            modal.className = 'modal-backdrop';
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-header">
                        <h3 class="modal-title" id="globalConfirmTitle">Confirm Action</h3>
                        <button class="modal-close" onclick="CourtPassApp.closeModal('globalConfirmModal')">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p id="globalConfirmMessage" style="font-size: 15px; color: var(--color-text-main);"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="CourtPassApp.closeModal('globalConfirmModal')">Cancel</button>
                        <button type="button" class="btn btn-danger" id="globalConfirmExecuteBtn">Proceed</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        document.getElementById('globalConfirmTitle').textContent = title;
        document.getElementById('globalConfirmMessage').textContent = message;
        const execBtn = document.getElementById('globalConfirmExecuteBtn');
        execBtn.textContent = confirmBtnText;

        execBtn.onclick = function() {
            CourtPassApp.closeModal('globalConfirmModal');
            if (typeof onConfirm === 'function') onConfirm();
        };

        CourtPassApp.openModal('globalConfirmModal');
    },

    // Close dropdowns when clicking outside
    initGlobalEvents: function() {
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    }
};

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', () => {
    CourtPassApp.initGlobalEvents();
});
