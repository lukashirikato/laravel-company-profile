/**
 * FTM Society - Member JavaScript
 * Production-ready, bundled with Vite
 */

/* ========================================= */
/* LOGOUT MODAL                              */
/* ========================================= */

window.showLogoutModal = function() {
    document.getElementById('logout-modal').classList.remove('hidden');
    document.getElementById('logout-modal').classList.add('flex');
};

window.closeLogoutModal = function() {
    document.getElementById('logout-modal').classList.add('hidden');
    document.getElementById('logout-modal').classList.remove('flex');
};

/* ========================================= */
/* MOBILE MENU                               */
/* ========================================= */

(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const openBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeBtn = document.getElementById('close-menu-button');
        const backdrop = document.getElementById('mobile-backdrop');
        
        if (!openBtn || !mobileMenu) return;

        function showBackdrop() {
            if (!backdrop) return;
            backdrop.classList.remove('hidden');
            backdrop.classList.add('block');
        }

        function hideBackdrop() {
            if (!backdrop) return;
            backdrop.classList.add('hidden');
            backdrop.classList.remove('block');
        }

        function openMenu() {
            try { if (typeof window.closeProfilePopup === 'function') window.closeProfilePopup(); } catch(e){}
            try { if (typeof window.closeServiceDetail === 'function') window.closeServiceDetail(); } catch(e){}
            try { if (typeof window.closeModal === 'function') window.closeModal(); } catch(e){}

            mobileMenu.style.display = 'block';
            setTimeout(function() { mobileMenu.classList.add('active'); }, 10);
            mobileMenu.style.transform = '';
            showBackdrop();
            if (backdrop) backdrop.style.pointerEvents = 'auto';
            document.body.classList.add('overflow-hidden');
            openBtn.setAttribute('aria-expanded', 'true');
            mobileMenu.setAttribute('aria-hidden', 'false');
        }

        function closeMenu() {
            mobileMenu.classList.remove('active');
            hideBackdrop();
            try { mobileMenu.style.transform = 'translateX(100%)'; } catch(e){}
            setTimeout(function() { 
                try { 
                    mobileMenu.style.display = 'none'; 
                    mobileMenu.style.transform = ''; 
                } catch(e){} 
            }, 350);
            if (backdrop) backdrop.style.pointerEvents = 'none';
            document.body.classList.remove('overflow-hidden');
            openBtn.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
        }

        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (mobileMenu.classList.contains('active')) { 
                closeMenu(); 
            } else { 
                openMenu(); 
            }
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                try { e.stopImmediatePropagation(); } catch(_) {}
                closeMenu();
                return false;
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', function() { closeMenu(); });
        }

        mobileMenu.querySelectorAll('a, button[type="submit"]').forEach(function(el) { 
            el.addEventListener('click', function() { 
                setTimeout(closeMenu, 80); 
            }); 
        });

        mobileMenu.addEventListener('click', function(e) {
            const t = e.target.closest('a, button');
            if (!t) return;
            try { closeMenu(); } catch (err) {}
        }, true);

        window.closeMobileMenu = closeMenu;

        document.addEventListener('keydown', function(e) { 
            if (e.key === 'Escape') { closeMenu(); } 
        });

        // Initial state
        hideBackdrop();
        mobileMenu.classList.remove('active');
        mobileMenu.setAttribute('aria-hidden', 'true');
        openBtn.setAttribute('aria-expanded', 'false');
        mobileMenu.style.transform = 'translateX(100%)';
        mobileMenu.style.display = 'none';
        if (backdrop) { 
            backdrop.style.display = 'none'; 
            backdrop.style.pointerEvents = 'none'; 
        }
    });
})();

/* ========================================= */
/* FEATURE SLIDER                            */
/* ========================================= */

window.slideFeature = function(direction) {
    const slider = document.getElementById('feature-slider');
    if (!slider) return;
    const scrollAmount = 360;
    slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    setTimeout(window.toggleFeatureScroll, 300);
};

window.toggleFeatureScroll = function() {
    const slider = document.getElementById('feature-slider');
    const leftBtn = document.getElementById('featureScrollLeft');
    const rightBtn = document.getElementById('featureScrollRight');
    if (!slider || !leftBtn || !rightBtn) return;
    leftBtn.style.display = slider.scrollLeft > 0 ? 'block' : 'none';
    rightBtn.style.display = (slider.scrollLeft + slider.clientWidth) < slider.scrollWidth ? 'block' : 'none';
};

/* ========================================= */
/* PROFILE POPUP                             */
/* ========================================= */

window.showProfilePopup = function() {
    const popup = document.getElementById('profile-popup');
    if (!popup) return;
    popup.classList.remove('hidden');
    popup.classList.add('flex');
};

window.closeProfilePopup = function() {
    const popup = document.getElementById('profile-popup');
    if (!popup) return;
    popup.classList.add('hidden');
    popup.classList.remove('flex');
};

/* ========================================= */
/* PROGRAM POPUP                             */
/* ========================================= */

window.showProgramPopup = function() {
    const popup = document.getElementById('program-popup');
    if (!popup) return;
    popup.classList.remove('hidden');
    popup.classList.add('flex');
};

window.closeProgramPopup = function() {
    const popup = document.getElementById('program-popup');
    if (!popup) return;
    popup.classList.add('hidden');
    popup.classList.remove('flex');
};

/* ========================================= */
/* SERVICE SLIDER                            */
/* ========================================= */

window.slideService = function(direction) {
    const slider = document.getElementById('service-slider');
    if (!slider) return;
    const isMobile = window.innerWidth < 768;
    const scrollAmount = isMobile ? (window.innerWidth * 0.9 + 24) : 260;
    slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    setTimeout(window.toggleServiceScroll, 300);
};

window.toggleServiceScroll = function() {
    const slider = document.getElementById('service-slider');
    const leftBtn = document.getElementById('serviceScrollLeft');
    const rightBtn = document.getElementById('serviceScrollRight');
    if (!slider || !leftBtn || !rightBtn) return;
    leftBtn.style.display = slider.scrollLeft > 0 ? 'block' : 'none';
    rightBtn.style.display = (slider.scrollLeft + slider.clientWidth) < slider.scrollWidth ? 'block' : 'none';
};

/* ========================================= */
/* SERVICE DETAIL MODAL                      */
/* ========================================= */

const serviceDetails = {
    'private-group': {
        title: 'Private Group Class',
        content: `
            <ul class="mb-3 list-disc pl-5">
                <li>Latihan kelompok privat dengan instruktur profesional</li>
                <li>Cocok untuk komunitas, keluarga, atau teman</li>
                <li>Jadwal fleksibel & suasana eksklusif</li>
            </ul>
            <p class="mb-2 font-semibold">Fasilitas:</p>
            <ul class="mb-3 list-disc pl-5">
                <li>Ruang latihan khusus</li>
                <li>Peralatan lengkap</li>
                <li>Free konsultasi awal</li>
            </ul>
        `
    },
    'private-training': {
        title: 'Private Training',
        content: `
            <ul class="mb-3 list-disc pl-5">
                <li>Latihan 1-on-1 dengan trainer profesional</li>
                <li>Program disesuaikan dengan kebutuhan Anda</li>
            </ul>
        `
    },
    'single-visit': {
        title: 'Single Visit Class',
        content: `
            <ul class="mb-3 list-disc pl-5">
                <li>Semi privat max 6–7 orang</li>
                <li>Coach perempuan tersertifikasi</li>
            </ul>
        `
    },
    'reformer-pilates': {
        title: 'Reformer Pilates',
        content: `
            <ul class="mb-3 list-disc pl-5">
                <li>Group Class: semi private max 3 orang</li>
                <li>Private Class untuk special case</li>
            </ul>
        `
    },
    'exclusive-program': {
        title: 'Exclusive Class Program',
        content: `
            <ul class="mb-3 list-disc pl-5">
                <li>8 sesi per bulan (2x/minggu)</li>
                <li>Dilatih oleh pelatih perempuan muslim tersertifikasi</li>
                <li>Semi private max 6–7 orang</li>
            </ul>
        `
    }
};

window.showServiceDetail = function(key) {
    const modal = document.getElementById('service-detail-modal');
    const box = document.getElementById('service-detail-box');
    if (!modal || !box || !serviceDetails[key]) return;

    document.getElementById('service-detail-title').textContent = serviceDetails[key].title;
    document.getElementById('service-detail-content').innerHTML = serviceDetails[key].content;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        box.classList.remove('opacity-0', 'scale-95');
        box.classList.add('opacity-100', 'scale-100');
    }, 10);
};

window.closeServiceDetail = function() {
    const modal = document.getElementById('service-detail-modal');
    const box = document.getElementById('service-detail-box');
    if (!modal || !box) return;

    box.classList.add('opacity-0', 'scale-95');
    box.classList.remove('opacity-100', 'scale-100');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
};

/* ========================================= */
/* PACKAGE VARIANT MODAL                     */
/* ========================================= */

window.openVariantModal = function(variants) {
    const modal = document.getElementById('package-variant-modal');
    const list = document.getElementById('package-variant-list');
    if (!modal || !list) return;

    list.innerHTML = '';
    variants.forEach(function(v) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:bg-gray-50';
        btn.textContent = v.label || 'Pilih Paket';
        btn.addEventListener('click', function() {
            window.location.href = v.url;
        });
        list.appendChild(btn);
    });

    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.closeVariantModal = function() {
    const modal = document.getElementById('package-variant-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};

/* ========================================= */
/* MEMBERSHIP SLIDER                         */
/* ========================================= */

window.slideMembership = function(direction) {
    const slider = document.getElementById('membershipList');
    if (!slider) return;
    slider.scrollBy({ left: direction * 370, behavior: 'smooth' });
    setTimeout(window.toggleMembershipScroll, 350);
};

window.toggleMembershipScroll = function() {
    const slider = document.getElementById('membershipList');
    const leftBtn = document.getElementById('membershipScrollLeft');
    const rightBtn = document.getElementById('membershipScrollRight');
    if (!slider || !leftBtn || !rightBtn) return;
    leftBtn.style.display = slider.scrollLeft > 10 ? 'flex' : 'none';
    const atEnd = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10;
    rightBtn.style.display = atEnd ? 'none' : 'flex';
};

/* ========================================= */
/* CLASS PROGRAMS                            */
/* ========================================= */

const classPrograms = {
    'muaythai': ["Exclusive Class Program", "Single Visit Class Program", "Private Program"],
    'body-shaping': ["Exclusive Class Program", "Single Visit Class Program", "Private Program"],
    'mat-pilates': ["Exclusive Class Program", "Single Visit Class Program", "Private Program"],
    'reformer-pilates': ["Single Visit Group Class", "Single Visit Packages"]
};

const waMessages = {
    'muaythai': "Halo FTM Society, saya ingin daftar kelas Muaythai.",
    'body-shaping': "Halo FTM Society, saya tertarik dengan kelas Body Shaping.",
    'mat-pilates': "Halo FTM Society, saya ingin info dan daftar Mat Pilates.",
    'reformer-pilates': "Halo FTM Society, saya ingin ikut Reformer Pilates."
};

const waNumber = "6287785767395";

window.openModal = function(key) {
    const modal = document.getElementById('class-modal');
    const title = document.getElementById('modal-title');
    const content = document.getElementById('modal-content');
    const waBtn = document.getElementById('modal-wa-btn');

    if (!modal || !title || !content) return;

    title.textContent = "Jadwal Kelas Exclusive Program";

    if (waBtn) {
        waBtn.onclick = function(e) {
            e.preventDefault();
            const waText = encodeURIComponent(waMessages[key] || "Halo FTM Society, saya ingin daftar kelas.");
            const waLink = `https://wa.me/${waNumber}?text=${waText}`;
            window.open(waLink, '_blank');
        };
    }

    let jadwalHTML = `<h4 class="font-semibold text-primary mb-2 text-lg">Jadwal Kelas ${key.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase())}</h4>`;
    
    if (window.classSchedules && window.classSchedules[key]) {
        jadwalHTML += `<table class="w-full mb-4 text-sm">
            <thead>
                <tr>
                    <th class="text-left py-1 px-2 text-gray-600">Class</th>
                    <th class="text-left py-1 px-2 text-gray-600">Hari</th>
                    <th class="text-left py-1 px-2 text-gray-600">Jam</th>
                    <th class="text-left py-1 px-2 text-gray-600">Instruktur</th>
                </tr>
            </thead>
            <tbody>
                ${window.classSchedules[key].map(j => `
                <tr>
                    <td class="py-1 px-2 text-xs text-gray-500">${j.kelas}</td>
                    <td class="py-1 px-2">${j.hari}</td>
                    <td class="py-1 px-2">${j.jam ? j.jam.split(' ')[1].slice(0,5) : ''}</td>
                    <td class="py-1 px-2">${j.instruktur}</td>
                </tr>
            `).join('')}
            </tbody>
        </table>`;
    } else {
        jadwalHTML += `<p class="text-gray-500 mb-4">Jadwal belum tersedia.</p>`;
    }

    let programs = classPrograms[key] || [];
    let serviceHTML = `<h4 class="font-semibold text-primary mb-2 text-lg">Pilihan Programs</h4>
        <ul class="mb-4 grid grid-cols-2 gap-2">
            ${programs.map(s => `
                <li class="bg-white border-2 border-secondary rounded-full px-3 py-2 text-xs text-primary font-semibold text-center shadow-sm transition-all duration-200 hover:bg-secondary hover:text-white cursor-pointer" style="border-color:#c68e8f;">
                    ${s}
                </li>
            `).join('')}
        </ul>`;

    content.innerHTML = jadwalHTML + serviceHTML;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.closeModal = function() {
    const modal = document.getElementById('class-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};

/* ========================================= */
/* FACILITY SLIDER                           */
/* ========================================= */

(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const TOTAL = 10;
        const track = document.getElementById('facility-track');
        const counter = document.getElementById('facility-counter');
        const dotsWrap = document.getElementById('facility-dots');
        const thumbs = document.querySelectorAll('.facility-thumb');
        const btnPrev = document.getElementById('facility-prev');
        const btnNext = document.getElementById('facility-next');
        const sliderEl = document.getElementById('facility-slider');

        if (!track || !dotsWrap) return;

        let idx = 0;
        let timer = null;
        const dots = [];

        // Create dots
        for (let i = 0; i < TOTAL; i++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'facility-dot' + (i === 0 ? ' active' : '');
            btn.addEventListener('click', () => { 
                idx = i; 
                render(); 
                resetTimer(); 
            });
            dotsWrap.appendChild(btn);
            dots.push(btn);
        }

        function render() {
            track.style.transform = `translateX(-${idx * 10}%)`;
            if (counter) {
                counter.textContent = `${String(idx + 1).padStart(2,'0')} / ${String(TOTAL).padStart(2,'0')}`;
            }
            dots.forEach((d, i) => {
                d.classList.toggle('active', i === idx);
            });
            thumbs.forEach((t, i) => {
                const active = i === idx;
                t.style.opacity = active ? '1' : '0.45';
                t.style.borderColor = active ? '#c68e8f' : 'transparent';
                t.style.transform = active ? 'scale(1.07)' : 'scale(1)';
            });
        }

        function next() { 
            idx = (idx + 1) % TOTAL; 
            render(); 
            resetTimer(); 
        }

        function prev() { 
            idx = (idx - 1 + TOTAL) % TOTAL; 
            render(); 
            resetTimer(); 
        }

        function resetTimer() {
            clearInterval(timer);
            timer = setInterval(next, 4500);
        }

        if (btnNext) btnNext.addEventListener('click', next);
        if (btnPrev) btnPrev.addEventListener('click', prev);

        thumbs.forEach(t => {
            t.addEventListener('click', () => {
                idx = parseInt(t.dataset.index);
                render();
                resetTimer();
            });
        });

        if (sliderEl) {
            sliderEl.addEventListener('mouseenter', () => clearInterval(timer));
            sliderEl.addEventListener('mouseleave', () => resetTimer());

            let startX = 0;
            sliderEl.addEventListener('touchstart', e => startX = e.touches[0].clientX, { passive: true });
            sliderEl.addEventListener('touchend', e => {
                const diff = startX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 40) diff > 0 ? next() : prev();
            });
        }

        render();
        resetTimer();
    });
})();

/* ========================================= */
/* DOM CONTENT LOADED INITIALIZATION         */
/* ========================================= */

document.addEventListener('DOMContentLoaded', function() {
    // Toggle scroll functions
    if (typeof window.toggleFeatureScroll === 'function') {
        window.toggleFeatureScroll();
    }
    if (typeof window.toggleServiceScroll === 'function') {
        window.toggleServiceScroll();
    }
    if (typeof window.toggleMembershipScroll === 'function') {
        window.toggleMembershipScroll();
    }

    // Join button handlers
    document.querySelectorAll('.join-btn').forEach(function(el) {
        el.addEventListener('click', function(e) {
            const data = el.getAttribute('data-variants');
            if (!data) return;
            try {
                const variants = JSON.parse(data);
                if (!Array.isArray(variants) || variants.length === 0) return;
                if (variants.length === 1) {
                    window.location.href = variants[0].url;
                    return;
                }
                window.openVariantModal(variants);
            } catch(err) {
                console.error('Invalid variants JSON', err);
            }
        });
    });

    // Close variant modal on backdrop click
    const variantModal = document.getElementById('package-variant-modal');
    if (variantModal) {
        variantModal.addEventListener('click', function(ev) {
            if (ev.target.id === 'package-variant-modal') {
                window.closeVariantModal();
            }
        });
    }

    // WhatsApp booking interceptor
    const waSelector = 'a[href^="https://wa.me/6287785767395"]';
    document.querySelectorAll(waSelector).forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();

            if (!window.isCustomerAuthenticated) {
                window.location.href = (window.homeRoute || '/') + '#signup';
                return;
            }

            const pkg = this.dataset.package;
            const checkoutUrl = pkg ? '/checkout?package=' + encodeURIComponent(pkg) : '/checkout';
            window.location.href = checkoutUrl;
        });
    });
});

// Resize handlers
window.addEventListener('resize', function() {
    if (typeof window.toggleServiceScroll === 'function') {
        window.toggleServiceScroll();
    }
    if (typeof window.toggleMembershipScroll === 'function') {
        window.toggleMembershipScroll();
    }
});
