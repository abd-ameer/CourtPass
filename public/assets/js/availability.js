/**
 * Slot availability grid for one court.
 * Loads slots from GET /api/courts/{id}/slots?date=YYYY-MM-DD.
 * Slot states: available, booked, blocked, unavailable. An available slot may carry a flash_price.
 */
const CourtPassAvailability = {
    courtId: null,
    isGuest: true,
    selectedDate: null,
    selectedSlot: null,
    slots: [],

    init: function (options) {
        this.courtId = options.courtId;
        this.isGuest = options.isGuest;
        this.selectedDate = this.localDate(new Date());
        this.renderDateTabs();
        this.loadSlots();
    },

    /** YYYY-MM-DD in the browser's local time (toISOString would shift to UTC). */
    localDate: function (d) {
        const pad = (n) => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    },

    renderDateTabs: function () {
        const container = document.getElementById('dateSelectorBar');
        if (!container) return;
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        container.textContent = '';

        const today = new Date();
        for (let i = 0; i < 7; i++) {
            const d = new Date(today.getFullYear(), today.getMonth(), today.getDate() + i);
            const dateStr = this.localDate(d);
            const tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'date-tab' + (i === 0 ? ' active' : '');
            tab.dataset.date = dateStr;
            tab.innerHTML = '<span class="day-name"></span><span class="day-number"></span><span class="text-xs" style="margin-top: 2px;"></span>';
            tab.children[0].textContent = i === 0 ? 'Today' : days[d.getDay()];
            tab.children[1].textContent = d.getDate();
            tab.children[2].textContent = months[d.getMonth()];
            tab.addEventListener('click', () => this.selectDate(tab, dateStr));
            container.appendChild(tab);
        }
    },

    selectDate: function (element, dateStr) {
        document.querySelectorAll('.date-tab').forEach((tab) => tab.classList.remove('active'));
        element.classList.add('active');
        this.selectedDate = dateStr;
        this.selectedSlot = null;
        this.updateCheckoutBar();
        this.loadSlots();
    },

    loadSlots: async function () {
        const container = document.getElementById('slotsGrid');
        if (!container) return;
        container.textContent = 'Loading slots...';
        try {
            const data = await CourtPass.api(`/api/courts/${this.courtId}/slots?date=${encodeURIComponent(this.selectedDate)}`);
            this.slots = data.slots || [];
            this.renderSlots();
        } catch (err) {
            container.textContent = 'Could not load slots. Please refresh the page.';
        }
    },

    renderSlots: function () {
        const container = document.getElementById('slotsGrid');
        if (!container) return;
        container.textContent = '';

        const labels = { available: 'Available', booked: 'Booked', blocked: 'Blocked', unavailable: 'Unavailable' };
        this.slots.forEach((slot) => {
            const isFlash = slot.state === 'available' && slot.flash_price !== null;
            let stateClass = 'state-' + (isFlash ? 'flash' : slot.state);
            if (this.selectedSlot === slot.start) stateClass += ' state-selected';

            const card = document.createElement('div');
            card.className = 'slot-card ' + stateClass;
            card.innerHTML = '<div class="slot-time"></div><div class="slot-state-label"></div><div class="slot-price"></div>';
            card.children[0].textContent = slot.start;
            card.children[1].textContent = isFlash ? 'Flash Deal' : (labels[slot.state] || slot.state);
            card.children[2].textContent = slot.state === 'available' ? CourtPass.lkr(isFlash ? slot.flash_price : slot.price) : '';
            card.addEventListener('click', () => this.handleSlotClick(slot));
            container.appendChild(card);
        });
    },

    handleSlotClick: function (slot) {
        if (slot.state !== 'available') {
            CourtPassApp.showToast('warning', 'Slot Not Available', 'Choose an available slot.');
            return;
        }
        if (this.isGuest) {
            CourtPassApp.confirmDialog(
                'Log In Required',
                'Log in with a customer account to book a court slot.',
                'Go to Login',
                () => { window.location.href = CourtPass.base + '/login'; }
            );
            return;
        }
        this.selectedSlot = this.selectedSlot === slot.start ? null : slot.start;
        this.renderSlots();
        this.updateCheckoutBar();
    },

    updateCheckoutBar: function () {
        const bar = document.getElementById('slotSelectionSummary');
        if (!bar) return;
        const slot = this.slots.find((s) => s.start === this.selectedSlot);
        const proceed = document.getElementById('proceedToCheckoutBtn');

        if (!slot) {
            bar.style.display = 'none';
            if (proceed) proceed.disabled = true;
            return;
        }

        bar.style.display = 'flex';
        document.getElementById('selectedSlotTimeDisplay').textContent = `${this.selectedDate} | ${slot.start} (1 hour)`;
        document.getElementById('selectedSlotPriceDisplay').textContent = CourtPass.lkr(slot.flash_price !== null ? slot.flash_price : slot.price);
        if (proceed) {
            proceed.disabled = false;
            proceed.onclick = () => {
                const q = new URLSearchParams({ court: this.courtId, date: this.selectedDate, start: slot.start });
                window.location.href = `${CourtPass.base}/customer/bookings/create?${q.toString()}`;
            };
        }
    }
};
