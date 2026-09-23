/**
 * CourtPass Slot Availability Engine
 * Supports interactive slot selection, date picking, state switching and checkout integration
 */

const CourtPassAvailability = {
    selectedCourt: 'c1',
    selectedDate: '2026-09-24',
    selectedSlot: null,
    hourlyRate: 5000,
    isGuest: false,

    // Sample schedule grid for 06:00 to 23:00 (1-hour slots)
    scheduleData: {
        '06:00 AM - 07:00 AM': { state: 'available', price: 5000 },
        '07:00 AM - 08:00 AM': { state: 'booked', label: 'Booked' },
        '08:00 AM - 09:00 AM': { state: 'available', price: 5000 },
        '09:00 AM - 10:00 AM': { state: 'available', price: 5000 },
        '10:00 AM - 11:00 AM': { state: 'blocked', label: 'Maintenance' },
        '11:00 AM - 12:00 PM': { state: 'available', price: 5000 },
        '12:00 PM - 01:00 PM': { state: 'booked', label: 'Booked' },
        '01:00 PM - 02:00 PM': { state: 'available', price: 5000 },
        '02:00 PM - 03:00 PM': { state: 'available', price: 5000 },
        '03:00 PM - 04:00 PM': { state: 'coaching', label: 'Coach Dilshan (Full)' },
        '04:00 PM - 05:00 PM': { state: 'coaching', label: 'Coach Shanilka' },
        '05:00 PM - 06:00 PM': { state: 'booked', label: 'Booked' },
        '06:00 PM - 07:00 PM': { state: 'booked', label: 'Booked' },
        '07:00 PM - 08:00 PM': { state: 'available', price: 5000 },
        '08:00 PM - 09:00 PM': { state: 'available', price: 5000 },
        '09:00 PM - 10:00 PM': { state: 'flash', label: 'Flash 30% OFF', price: 3500 },
        '10:00 PM - 11:00 PM': { state: 'flash', label: 'Flash 30% OFF', price: 3500 }
    },

    init: function(options = {}) {
        if (options.courtId) this.selectedCourt = options.courtId;
        if (options.hourlyRate) this.hourlyRate = options.hourlyRate;
        if (options.isGuest !== undefined) this.isGuest = options.isGuest;

        this.renderDateTabs();
        this.renderSlots();
        this.bindEvents();
    },

    renderDateTabs: function() {
        const container = document.getElementById('dateSelectorBar');
        if (!container) return;

        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        let html = '';
        const baseDate = new Date(2026, 8, 24); // Sept 24, 2026

        for (let i = 0; i < 7; i++) {
            const d = new Date(baseDate);
            d.setDate(baseDate.getDate() + i);
            const isToday = i === 0;
            const dateStr = d.toISOString().split('T')[0];
            const activeClass = i === 0 ? 'active' : '';

            html += `
                <div class="date-tab ${activeClass}" data-date="${dateStr}" onclick="CourtPassAvailability.selectDate(this, '${dateStr}')">
                    <span class="day-name">${isToday ? 'Today' : days[d.getDay()]}</span>
                    <span class="day-number">${d.getDate()}</span>
                    <span class="text-xs" style="margin-top: 2px;">${months[d.getMonth()]}</span>
                </div>
            `;
        }

        container.innerHTML = html;
    },

    selectDate: function(element, dateStr) {
        document.querySelectorAll('.date-tab').forEach(tab => tab.classList.remove('active'));
        element.classList.add('active');
        this.selectedDate = dateStr;
        this.selectedSlot = null;
        this.renderSlots();
        this.updateCheckoutBar();
        CourtPassApp.showToast('info', 'Date Changed', `Viewing availability for ${dateStr}`);
    },

    renderSlots: function() {
        const container = document.getElementById('slotsGrid');
        if (!container) return;

        let html = '';
        for (const [timeSlot, info] of Object.entries(this.scheduleData)) {
            let stateClass = `state-${info.state}`;
            let label = 'Available';
            let priceText = `LKR ${info.price ? info.price.toLocaleString() : this.hourlyRate.toLocaleString()}`;

            if (info.state === 'booked') {
                label = 'Booked';
                priceText = 'Unavailable';
            } else if (info.state === 'coaching') {
                label = info.label || 'Coaching Session';
                priceText = 'Coaching Slot';
            } else if (info.state === 'blocked') {
                label = info.label || 'Owner Blocked';
                priceText = 'Private Event';
            } else if (info.state === 'flash') {
                label = info.label || 'Flash Deal';
                priceText = `LKR ${info.price.toLocaleString()}`;
            }

            if (this.selectedSlot === timeSlot) {
                stateClass += ' state-selected';
            }

            html += `
                <div class="slot-card ${stateClass}" data-slot="${timeSlot}" data-state="${info.state}" data-price="${info.price || this.hourlyRate}" onclick="CourtPassAvailability.handleSlotClick('${timeSlot}', '${info.state}', ${info.price || this.hourlyRate})">
                    <div class="slot-time">${timeSlot.split(' - ')[0]}</div>
                    <div class="slot-state-label">${label}</div>
                    <div class="slot-price">${priceText}</div>
                </div>
            `;
        }

        container.innerHTML = html;
    },

    handleSlotClick: function(timeSlot, state, price) {
        const base = (window.CourtPass && window.CourtPass.base) ? window.CourtPass.base : (document.querySelector('meta[name="base-url"]')?.content || '');

        if (this.isGuest) {
            CourtPassApp.confirmDialog(
                'Sign In Required',
                'You must log in or create an account to book court slots.',
                'Go to Login',
                function() {
                    window.location.href = base + '/login?redirect=' + encodeURIComponent(window.location.href);
                }
            );
            return;
        }

        if (state === 'booked') {
            CourtPassApp.showToast('warning', 'Slot Unavailable', 'This slot is already booked by another customer.');
            return;
        }

        if (state === 'blocked') {
            CourtPassApp.showToast('warning', 'Slot Blocked', 'This court slot is blocked by the venue for private/maintenance purposes.');
            return;
        }

        if (state === 'coaching') {
            CourtPassApp.confirmDialog(
                'Coaching Session Slot',
                'This slot is reserved for a coaching clinic. Would you like to view the session details to register?',
                'View Coaching Session',
                function() {
                    window.location.href = base + '/customer/session-details?id=session-101';
                }
            );
            return;
        }

        // Toggle selection for available and flash deals
        if (this.selectedSlot === timeSlot) {
            this.selectedSlot = null;
        } else {
            this.selectedSlot = timeSlot;
            this.hourlyRate = price;
        }

        this.renderSlots();
        this.updateCheckoutBar();
    },

    updateCheckoutBar: function() {
        const summaryBar = document.getElementById('slotSelectionSummary');
        const slotTimeDisplay = document.getElementById('selectedSlotTimeDisplay');
        const slotPriceDisplay = document.getElementById('selectedSlotPriceDisplay');
        const proceedBtn = document.getElementById('proceedToCheckoutBtn');

        if (!summaryBar) return;

        if (this.selectedSlot) {
            summaryBar.style.display = 'flex';
            if (slotTimeDisplay) slotTimeDisplay.textContent = `${this.selectedDate} | ${this.selectedSlot}`;
            if (slotPriceDisplay) slotPriceDisplay.textContent = `LKR ${this.hourlyRate.toLocaleString()}`;
            if (proceedBtn) {
                proceedBtn.disabled = false;
                proceedBtn.onclick = () => {
                    const base = (window.CourtPass && window.CourtPass.base) ? window.CourtPass.base : (document.querySelector('meta[name="base-url"]')?.content || '');
                    window.location.href = `${base}/customer/create-booking?court=${this.selectedCourt}&date=${this.selectedDate}&slot=${encodeURIComponent(this.selectedSlot)}&rate=${this.hourlyRate}`;
                };
            }
        } else {
            summaryBar.style.display = 'none';
            if (proceedBtn) proceedBtn.disabled = true;
        }
    },

    bindEvents: function() {
        // Court tab switching if present
        document.querySelectorAll('.court-tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.court-tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                this.selectedCourt = btn.dataset.courtId;
                this.selectedSlot = null;
                this.renderSlots();
                this.updateCheckoutBar();
            });
        });
    }
};
