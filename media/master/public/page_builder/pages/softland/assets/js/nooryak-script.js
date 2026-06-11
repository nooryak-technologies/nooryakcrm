/**
 * NOORYAK CRM â€“ Landing Page Scripts v2
 * - Staggered entrance animations for lp2-hero
 * - Counter animation (with comma formatting)
 * - Sparkline draw animation
 */

(function () {
    'use strict';

    /* â”€â”€ Ease out cubic â”€â”€ */
    function easeOut(t) { return 1 - Math.pow(1 - t, 3); }

    /* â”€â”€ Counter animation â”€â”€ */
    function animateCounter(el) {
        var target = parseInt(el.getAttribute('data-target'), 10);
        var useSep = el.hasAttribute('data-sep');
        if (isNaN(target)) return;

        var duration = 1600;
        var startTime = null;

        function fmt(n) {
            return useSep ? n.toLocaleString() : String(n);
        }

        function tick(ts) {
            if (!startTime) startTime = ts;
            var p = Math.min((ts - startTime) / duration, 1);
            el.textContent = fmt(Math.floor(easeOut(p) * target));
            if (p < 1) requestAnimationFrame(tick);
            else el.textContent = fmt(target);
        }
        requestAnimationFrame(tick);
    }

    /* â”€â”€ Staggered reveal â”€â”€ */
    function revealHero() {
        var hero = document.getElementById('lp2-hero');
        if (!hero) return;

        var items = hero.querySelectorAll('.lp2-anim');
        items.forEach(function (el) {
            var delay = parseInt(el.getAttribute('data-delay') || '0', 10);
            setTimeout(function () {
                el.classList.add('lp2-visible');
            }, delay);
        });

        /* Start counters after 500ms */
        setTimeout(function () {
            hero.querySelectorAll('.lp2-counter[data-target]').forEach(animateCounter);
        }, 500);
    }

    /* â”€â”€ Sparkline draw animation â”€â”€ */
    function animateSparkline() {
        var paths = document.querySelectorAll('#lp2-hero .lp2-sparkline path[stroke]');
        paths.forEach(function (path) {
            var len = path.getTotalLength ? path.getTotalLength() : 120;
            path.style.strokeDasharray = len;
            path.style.strokeDashoffset = len;
            path.style.transition = 'stroke-dashoffset 3.5s cubic-bezier(0.16,1,0.3,1) 0.8s';
            setTimeout(function () {
                path.style.strokeDashoffset = '0';
            }, 100); // Small initial delay, then transition handles the rest
        });
    }

    function handleScroll() {
        var nav = document.querySelector('.nooryak-nav.navbar-area') || document.querySelector('.navbar-area');
        if (!nav) return;
        var isScrolled = window.scrollY > 30;
        nav.classList.toggle('scrolled', isScrolled);
        nav.classList.toggle('sticky', isScrolled);
    }

    /* ── Mobile Menu Toggler ── */
    function initMobileMenu() {
        var toggler = document.querySelector('.navbar-toggler');
        var menu = document.querySelector('#nooryakPublicNavbar') || document.querySelector('#navbarSupportedContent');
        if (!toggler || !menu) return;

        toggler.addEventListener('click', function () {
            var isExpanded = toggler.getAttribute('aria-expanded') === 'true';
            toggler.setAttribute('aria-expanded', !isExpanded);
            toggler.classList.toggle('collapsed', isExpanded);
            menu.classList.toggle('show', !isExpanded);
        });

        /* Close menu when clicking a link */
        menu.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                toggler.setAttribute('aria-expanded', 'false');
                toggler.classList.add('collapsed');
                menu.classList.remove('show');
            });
        });
    }

    /* Scroll-to-top button handler */
    function initScrollToTop() {
        var scrollTopBtn = document.getElementById('scrollTopBtn');
        if (!scrollTopBtn) return;

        // Prevent duplicate listener binding
        if (scrollTopBtn.getAttribute('data-scroll-bound') === 'true') return;
        scrollTopBtn.setAttribute('data-scroll-bound', 'true');

        // Robust scroll position — works even when overflow is on html/body
        function getScrollY() {
            return window.scrollY
                || window.pageYOffset
                || document.documentElement.scrollTop
                || document.body.scrollTop
                || 0;
        }

        // Show/hide button based on scroll position
        function toggleScrollTopVisibility() {
            if (getScrollY() > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        }

        // Smooth scroll to top with robust fallbacks
        function scrollToTop(e) {
            if (e) e.preventDefault();

            var scrollOptions = { top: 0, behavior: 'smooth' };

            // Try native smooth scrolling first on all possible scrollable boundaries
            try {
                window.scrollTo(scrollOptions);
            } catch (err) {}
            try {
                document.documentElement.scrollTo(scrollOptions);
            } catch (err) {}
            try {
                document.body.scrollTo(scrollOptions);
            } catch (err) {}

            // Animation fallback for browsers that block or ignore smooth scroll behavior
            var start = getScrollY();
            var duration = 400; // ms
            var startTime = performance.now();

            function easeOutQuad(t) { return t * (2 - t); }

            function step(currentTime) {
                var elapsed = currentTime - startTime;
                var progress = Math.min(elapsed / duration, 1);
                var ease = easeOutQuad(progress);
                var currentPos = start - (start * ease);

                window.scrollTo(0, currentPos);
                if (document.documentElement) document.documentElement.scrollTop = currentPos;
                if (document.body) document.body.scrollTop = currentPos;

                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    window.scrollTo(0, 0);
                    if (document.documentElement) document.documentElement.scrollTop = 0;
                    if (document.body) document.body.scrollTop = 0;
                }
            }
            requestAnimationFrame(step);
        }

        scrollTopBtn.addEventListener('click', scrollToTop);
        window.addEventListener('scroll', toggleScrollTopVisibility, { passive: true });
        document.addEventListener('scroll', toggleScrollTopVisibility, { passive: true });

        // Initial check
        toggleScrollTopVisibility();
    }

    function init() {
        revealHero();
        animateSparkline();
        initMobileMenu();
        initScrollToTop();
        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also run on window load as a safety net for late-rendered content
    window.addEventListener('load', function () {
        initScrollToTop();
    });
})();

/* â”€â”€ Smart CRM Features â€“ Tab Switcher + Panel Restructure â”€â”€ */
(function () {

    /* Move h3.sf-pd-title inside .sf-pd-head so icon+title share one row */
    function restructurePanelHeads() {
        var panels = document.querySelectorAll('#sf-features div.sf-panel');
        panels.forEach(function (panel) {
            var head = panel.querySelector('.sf-pd-head');
            var title = panel.querySelector('h3.sf-pd-title');
            var dot = head ? head.querySelector('.sf-pd-dot') : null;
            if (head && title && dot) {
                head.insertBefore(title, dot);
            }
        });
    }

    function initFeatureTabs() {
        var items = document.querySelectorAll('#sf-features div.sf-item');
        var panels = document.querySelectorAll('#sf-features div.sf-panel');
        if (!items.length) return;

        function activate(item) {
            var target = item.getAttribute('data-target');

            items.forEach(function (el) { el.classList.remove('sf-active'); });
            item.classList.add('sf-active');

            panels.forEach(function (p) {
                p.classList.remove('sf-panel-active');
                p.style.display = 'none';
            });

            var panel = document.getElementById(target);
            if (panel) {
                panel.style.display = 'block';
                panel.classList.add('sf-panel-active');
            }
        }

        items.forEach(function (item) {
            item.addEventListener('mouseenter', function () { activate(item); });
            item.addEventListener('click', function () { activate(item); });
        });
    }

    function init() {
        restructurePanelHeads();
        initFeatureTabs();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   DYNAMIC PRICING SECTION
   Fetches packages from: /crm/perfex_saas/landing/api_packages
   and renders them into #dynamic-pricing-grid
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
(function () {
    'use strict';

    var CACHED_PACKAGES = [];
    var CURRENT_INTERVAL = 'month'; // 'month' or 'year'

    var PLAN_ICONS = [
        'fa-solid fa-seedling',
        'fa-solid fa-rocket',
        'fa-solid fa-crown',
        'fa-solid fa-building',
        'fa-solid fa-gem',
        'fa-solid fa-bolt',
    ];

    function getApiUrl() {
        var loc = window.location;
        var parts = loc.pathname.split('/').filter(Boolean);
        var base = loc.origin;
        if (parts.length && parts[0] !== 'perfex_saas') {
            base += '/' + parts[0];
        }
        return base + '/perfex_saas/landing/api_packages';
    }

    function formatPrice(pkg, interval) {
        var price = parseFloat(pkg.price);
        if (interval === 'year') price = price * 12;

        if (price === 0) return '<span class="currency">' + pkg.currency_symbol + '</span>0';

        // Format with 2 decimals if not whole
        var formatted = price.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
        return '<span class="currency">' + pkg.currency_symbol + '</span>' + formatted;
    }

    function buildFeatureItems(pkg) {
        // If admin has set custom feature lines, use those directly
        if (pkg.feature_lines && pkg.feature_lines.length > 0) {
            return pkg.feature_lines;
        }
        // Fallback: build from modules and non-zero limitations
        var items = [];
        var modules = pkg.modules || [];
        var limits = pkg.limitations || {};
        modules.forEach(function (m) {
            var label = m.replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
            items.push(label);
        });
        Object.keys(limits).forEach(function (key) {
            var val = limits[key];
            var keyLabel = key.replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
            if (parseInt(val) === -1) {
                items.push('Unlimited ' + keyLabel);
            } else if (parseInt(val) > 0) {
                items.push(val + ' ' + keyLabel);
            }
        });
        if (pkg.storage_label && pkg.storage_label !== '0 GB') {
            var alreadyHas = items.some(function (i) { return i.toLowerCase().indexOf('storage') !== -1; });
            if (!alreadyHas) items.push(pkg.storage_label + ' Storage');
        }
        return items;
    }

    function resolveUserLimit(pkg) {
        var limit = parseInt(pkg.user_limit, 10);
        if (!isNaN(limit) && limit !== 0) {
            return limit;
        }
        var lim = pkg.limitations || {};
        if (lim.users !== undefined && lim.users !== '') {
            var users = parseInt(lim.users, 10);
            if (!isNaN(users)) {
                return users;
            }
        }
        if (lim.staff !== undefined && lim.staff !== '') {
            var staff = parseInt(lim.staff, 10);
            if (!isNaN(staff)) {
                return staff;
            }
        }
        return -1;
    }

    function buildPlanNote(pkg) {
        var isFreeTrial = parseFloat(pkg.price) === 0;
        var userLimit = resolveUserLimit(pkg);
        var teamInput = document.getElementById('team-size-input');
        var teamSize = teamInput ? parseInt(teamInput.value, 10) || 0 : 0;
        var parts = [];

        if (isFreeTrial && pkg.trial_period > 0) {
            parts.push(pkg.trial_period + '-Day Access');
        }

        if (userLimit > 0) {
            parts.push(userLimit + ' User' + (userLimit > 1 ? 's' : ''));
        } else if (userLimit === -1) {
            parts.push('Unlimited Users');
        } else if (!isFreeTrial && teamSize > 0) {
            parts.push(teamSize + ' Users');
        }

        if (parts.length) {
            return '<div class="plan-note">' + parts.join(' &bull; ') + '</div>';
        }
        if (pkg.description) {
            return '<div class="plan-note">' + pkg.description + '</div>';
        }
        return '<div class="plan-note">&nbsp;</div>';
    }

    function getPopularPackageIndex(packages) {
        if (!packages || !packages.length) return -1;
        for (var i = 0; i < packages.length; i++) {
            var name = (packages[i].name || '').toLowerCase();
            var slug = (packages[i].slug || '').toLowerCase();
            if (name.indexOf('professional') !== -1 || slug.indexOf('professional') !== -1
                || name.indexOf('profession') !== -1 || slug.indexOf('profession') !== -1) {
                return i;
            }
        }
        if (packages.length >= 3) {
            return 2;
        }
        return -1;
    }

    function buildCard(pkg, index, isPopular, interval) {
        var icon = PLAN_ICONS[index % PLAN_ICONS.length];
        var isFreeTrial = parseFloat(pkg.price) === 0;
        var cardClass = 'plan-card' + (isPopular ? ' popular' : '');
        var btnClass = 'btn btn-solid';
        var btnText = isFreeTrial ? 'Start Free Trial' : 'Get Started';
        var features = buildFeatureItems(pkg);
        var displayInterval = interval === 'year' ? 'Year' : 'Month';

        var priceBlock = '';
        if (isFreeTrial) {
            priceBlock = '<div class="plan-price">' + formatPrice(pkg, interval) + '</div>';
        } else {
            priceBlock = '<div class="plan-price">'
                + formatPrice(pkg, interval)
                + '<span class="per"> / ' + displayInterval + '</span>'
                + '</div>';
        }

        var noteBlock = buildPlanNote(pkg);

        var featureHTML = '<ul class="features-list">';
        var shown = features.slice(0, 8);
        var hidden = features.slice(8);
        shown.forEach(function (f) {
            featureHTML += '<li><i class="fa-solid fa-circle-check"></i> ' + f + '</li>';
        });
        if (hidden.length > 0) {
            var hiddenId = 'extra-' + pkg.id;
            // Hidden extra features
            featureHTML += '<li class="extra-features" id="' + hiddenId + '" style="display:none;list-style:none;padding:0;margin:0;">';
            featureHTML += '<ul style="padding:0;margin:0;list-style:none;">';
            hidden.forEach(function (f) {
                featureHTML += '<li style="margin-top:6px;"><i class="fa-solid fa-circle-check"></i> ' + f + '</li>';
            });
            featureHTML += '</ul></li>';
            // Toggle button
            featureHTML += '<li class="show-more-toggle" style="list-style:none;padding:0;margin-top:10px;">'
                + '<span onclick="(function(el){var t=document.getElementById(\'' + hiddenId + '\');var isOpen=t.style.display!==\'none\';t.style.display=isOpen?\'none\':\'block\';el.textContent=isOpen?\'+ ' + hidden.length + ' more\':\'Show less\';})(this)" '
                + 'style="cursor:pointer;color:var(--primary,#f60);font-size:13px;font-weight:600;">+ ' + hidden.length + ' more</span>'
                + '</li>';
        }
        featureHTML += '</ul>';

        return '<div class="' + cardClass + '" data-plan-id="' + pkg.id + '">'
            + (isPopular ? '<span class="popular-badge"><i class="fa-solid fa-star" style="font-size:9px;margin-right:4px"></i>Most Popular</span>' : '')
            + '<div class="plan-icon"><i class="' + icon + '"></i></div>'
            + '<div class="plan-name">' + pkg.name + '</div>'
            + priceBlock
            + noteBlock
            + featureHTML
            + '<a href="' + pkg.register_url + '" class="' + btnClass + '">'
            + btnText + ' <i class="fa-solid fa-chevron-right" style="font-size:12px;margin-left:4px;"></i>'
            + '</a>'
            + '</div>';
    }

    function syncTeamSizeHighlight() {
        var input = document.getElementById('team-size-input');
        var cards = document.querySelectorAll('.plan-card');
        if (!input || !cards.length) return;
        var val = parseInt(input.value, 10);
        var targetIndex = -1;
        if (val >= 1 && val <= 2) targetIndex = 0;
        else if (val >= 3 && val <= 4) targetIndex = 1;
        else if (val >= 5 && val <= 6) targetIndex = 2;
        else if (val > 6) targetIndex = 3;
        var popularIndex = getPopularPackageIndex(CACHED_PACKAGES);
        cards.forEach(function (card, i) {
            card.classList.remove('active-highlight');
            var isProfessionalPopular = popularIndex >= 0 && i === popularIndex;
            card.classList.toggle('popular', isProfessionalPopular);
            if (i === targetIndex && i !== popularIndex) {
                card.classList.add('active-highlight');
            }
        });
    }

    function renderCards(packages, interval) {
        var grid = document.getElementById('dynamic-pricing-grid');
        if (!grid) return;
        if (!packages || packages.length === 0) return;
        var popularIndex = getPopularPackageIndex(packages);
        var html = '';
        packages.forEach(function (pkg, i) {
            html += buildCard(pkg, i, i === popularIndex, interval);
        });
        grid.innerHTML = html;
        syncTeamSizeHighlight();
    }

    function syncBillingPeriodLabels() {
        var toggle = document.getElementById('billing-toggle-input');
        var monthly = document.getElementById('billing-label-monthly');
        var yearly = document.getElementById('billing-label-yearly');
        if (!toggle || !monthly || !yearly) return;
        var isYear = toggle.checked;
        monthly.classList.toggle('is-active', !isYear);
        monthly.classList.toggle('is-inactive', isYear);
        yearly.classList.toggle('is-active', isYear);
        yearly.classList.toggle('is-inactive', !isYear);
    }

    function initBillingToggle() {
        var toggle = document.getElementById('billing-toggle-input');
        var monthly = document.getElementById('billing-label-monthly');
        var yearly = document.getElementById('billing-label-yearly');
        if (!toggle) return;
        if (toggle.getAttribute('data-billing-bound') === '1') return;
        toggle.setAttribute('data-billing-bound', '1');

        function applyBillingMode() {
            CURRENT_INTERVAL = toggle.checked ? 'year' : 'month';
            syncBillingPeriodLabels();
            if (CACHED_PACKAGES.length) {
                renderCards(CACHED_PACKAGES, CURRENT_INTERVAL);
            }
        }

        toggle.addEventListener('change', applyBillingMode);

        if (monthly) {
            monthly.addEventListener('click', function () {
                if (!toggle.checked) return;
                toggle.checked = false;
                applyBillingMode();
            });
        }

        if (yearly) {
            yearly.addEventListener('click', function () {
                if (toggle.checked) return;
                toggle.checked = true;
                applyBillingMode();
            });
        }

        syncBillingPeriodLabels();
    }

    function loadPricing() {
        var grid = document.getElementById('dynamic-pricing-grid');
        if (!grid) return;
        var apiUrl = getApiUrl();
        fetch(apiUrl)
            .then(function (res) {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(function (data) {
                if (data && data.success && Array.isArray(data.packages)) {
                    CACHED_PACKAGES = data.packages;
                    renderCards(CACHED_PACKAGES, CURRENT_INTERVAL);
                    var input = document.getElementById('team-size-input');
                    if (input) {
                        input.addEventListener('input', function () {
                            syncTeamSizeHighlight();
                            renderCards(CACHED_PACKAGES, CURRENT_INTERVAL);
                        });
                        syncTeamSizeHighlight();
                    }
                    initBillingToggle();
                }
            })
            .catch(function (err) {
                console.warn('[Nooryak CRM] Could not load pricing packages:', err.message);
            });
    }

    function bootPricingSection() {
        initBillingToggle();
        loadPricing();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootPricingSection);
    } else {
        bootPricingSection();
    }

    /* Capture plan selection (from Pricing Cards) */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.plan-card .btn');
        if (btn) {
            var card = btn.closest('.plan-card');
            var planId = card.getAttribute('data-plan-id');
            var pkg = CACHED_PACKAGES.find(function (p) { return p.id == planId; });

            if (!pkg) return;

            var teamSizeInput = document.getElementById('team-size-input');
            var teamSize = teamSizeInput ? teamSizeInput.value : '1';

            var price = parseFloat(pkg.price);
            if (CURRENT_INTERVAL === 'year') price = price * 12;
            var formattedPrice = price.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });

            var selection = {
                id: pkg.id,
                name: pkg.name,
                price: pkg.currency_symbol + formattedPrice,
                teamSize: teamSize,
                isFree: parseFloat(pkg.price) === 0,
                interval: CURRENT_INTERVAL,
                timestamp: Date.now()
            };
            localStorage.setItem('nooryak_selected_plan', JSON.stringify(selection));
        }
    });

    /* Capture global free trial triggers (Hero, Navbar) */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.ny-free-trial-btn');
        if (btn) {
            var freePkg = CACHED_PACKAGES.find(function (p) {
                return parseFloat(p.price) === 0 || p.name.toLowerCase().indexOf('free') !== -1;
            });

            if (freePkg) {
                var selection = {
                    id: freePkg.id,
                    name: freePkg.name,
                    price: freePkg.currency_symbol + '0',
                    teamSize: '2',
                    isFree: true,
                    interval: 'month',
                    timestamp: Date.now()
                };
                localStorage.setItem('nooryak_selected_plan', JSON.stringify(selection));
            }
        }
    });
})();

/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   INTERACTIVE SALES PIPELINE DEMO
   Handles drag & drop for .deal-card across .pipeline-column
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
(function () {
    'use strict';

    function initPipelineDemo() {
        const cards = document.querySelectorAll('.deal-card');
        const columns = document.querySelectorAll('.pipeline-column');
        let draggedCard = null;

        if (!cards.length || !columns.length) return;

        /* MAKE CARDS DRAGGABLE */
        cards.forEach(card => {
            card.setAttribute('draggable', true);

            card.addEventListener('dragstart', function () {
                draggedCard = card;
                setTimeout(() => {
                    card.classList.add('pl-dragging');
                }, 0);
            });

            card.addEventListener('dragend', function () {
                card.classList.remove('pl-dragging');
                draggedCard = null;
                columns.forEach(col => col.classList.remove('pl-col-hover'));
            });
        });

        /* COLUMN EVENTS */
        columns.forEach(column => {
            column.addEventListener('dragover', function (e) {
                e.preventDefault();
                column.classList.add('pl-col-hover');
            });

            column.addEventListener('dragleave', function () {
                column.classList.remove('pl-col-hover');
            });

            column.addEventListener('drop', function (e) {
                e.preventDefault();
                column.classList.remove('pl-col-hover');

                if (draggedCard) {
                    column.appendChild(draggedCard);
                    updateCounts();
                }
            });
        });

        /* UPDATE COUNTS */
        function updateCounts() {
            columns.forEach(column => {
                const count = column.querySelectorAll('.deal-card').length;
                const badge = column.querySelector('.column-header small');
                if (badge) badge.textContent = count;
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPipelineDemo);
    } else {
        initPipelineDemo();
    }
})();





/* ------------------------------------------
   NOORYAK SMART INTEGRATIONS ï¿½ JS
   Scroll-reveal for integration cards
------------------------------------------ */
(function () {
    function initIntegrationReveal() {
        var cards = document.querySelectorAll('.nbi-int-card, .nbi-compat-card, .nbi-why-card');
        if (!cards.length || !window.IntersectionObserver) return;
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'translateY(0)';
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });
        cards.forEach(function (c, i) {
            c.style.opacity = '0';
            c.style.transform = 'translateY(18px)';
            c.style.transition = 'opacity .45s ease ' + (i * 0.05) + 's, transform .45s ease ' + (i * 0.05) + 's';
            obs.observe(c);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIntegrationReveal);
    } else {
        initIntegrationReveal();
    }
})();


/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   SCROLL-TRIGGERED COUNTER ANIMATION
   â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */

(function () {
    'use strict';

    if (window.__nyCountersInit) return;
    window.__nyCountersInit = true;

    var SCROLL_IO = { threshold: [0, 0.15, 0.35], rootMargin: '0px 0px -10% 0px' };

    function whenPageReady(cb) {
        if (document.readyState === 'complete') {
            setTimeout(cb, 120);
        } else {
            window.addEventListener('load', function () {
                setTimeout(cb, 120);
            });
        }
    }

    function formatNumber(num, prefix, suffix, decimals) {
        var formatted = decimals > 0 ? num.toFixed(decimals) : Math.floor(num).toString();
        if (num >= 1000 && suffix.indexOf('K') === -1 && suffix.indexOf('M') === -1) {
            formatted = formatted.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        return (prefix || '') + formatted + (suffix || '');
    }

    function resetCounter(element) {
        if (element.getAttribute('data-animated') === '1') return;
        var suffix = element.getAttribute('data-suffix') || '';
        var prefix = element.getAttribute('data-prefix') || '';
        var decimals = parseInt(element.getAttribute('data-decimals'), 10) || 0;
        element.textContent = formatNumber(0, prefix, suffix, decimals);
    }

    function animateCounter(element) {
        if (element.getAttribute('data-animated') === '1') return;
        element.setAttribute('data-animated', '1');

        var target = parseFloat(element.getAttribute('data-target'));
        var suffix = element.getAttribute('data-suffix') || '';
        var prefix = element.getAttribute('data-prefix') || '';
        var decimals = parseInt(element.getAttribute('data-decimals'), 10) || 0;
        if (isNaN(target)) return;

        var duration = 4500;
        var startTime = null;

        function easeOutQuart(t) {
            return 1 - Math.pow(1 - t, 4);
        }

        function updateCounter(currentTime) {
            if (!startTime) startTime = currentTime;
            var progress = Math.min((currentTime - startTime) / duration, 1);
            var currentValue = easeOutQuart(progress) * target;
            element.textContent = formatNumber(currentValue, prefix, suffix, decimals);
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = formatNumber(target, prefix, suffix, decimals);
            }
        }

        requestAnimationFrame(updateCounter);
    }

    function observeCounterSection(section, counters) {
        if (!counters.length) return;

        counters.forEach(resetCounter);

        if (!window.IntersectionObserver) {
            counters.forEach(animateCounter);
            section.classList.add('nbi-inview');
            return;
        }

        var done = false;
        var wasInView = false;
        var obs = new IntersectionObserver(function (entries) {
            var entry = entries[0];
            var inView = entry.isIntersecting && entry.intersectionRatio >= 0.15;
            if (inView && !wasInView && !done) {
                done = true;
                section.classList.add('nbi-inview');
                counters.forEach(animateCounter);
                obs.disconnect();
            }
            wasInView = inView;
        }, SCROLL_IO);
        obs.observe(section);
    }

    function initCounters() {
        var sections = [
            '.nbi-section-business',
            '.nbi-section-pipeline',
            '.ny-stats-band'
        ];

        sections.forEach(function (selector) {
            var section = document.querySelector(selector);
            if (!section) return;
            var counters = section.querySelectorAll('.ny-counter');
            observeCounterSection(section, Array.prototype.slice.call(counters));
        });

        var seenSections = [];
        document.querySelectorAll('.ny-counter').forEach(function (counter) {
            if (counter.closest('.nbi-section-business, .nbi-section-pipeline, .ny-stats-band')) return;
            var host = counter.closest('section');
            if (!host || seenSections.indexOf(host) !== -1) return;
            seenSections.push(host);
            observeCounterSection(host, Array.prototype.slice.call(host.querySelectorAll('.ny-counter')));
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            whenPageReady(initCounters);
        });
    } else {
        whenPageReady(initCounters);
    }
})();


/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   CHART.JS - SALES PERFORMANCE CHART (scroll into .nbi-section-business)
   â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */

(function () {
    'use strict';

    if (window.__nyChartsInit) return;
    window.__nyChartsInit = true;

    var salesChartInstance = null;
    var salesChartReady = false;
    var activityChartReady = false;
    var performanceChartReady = false;
    var engagementChartReady = false;
    var SCROLL_IO = { threshold: [0, 0.15, 0.35], rootMargin: '0px 0px -10% 0px' };

    function whenPageReady(cb) {
        if (document.readyState === 'complete') {
            setTimeout(cb, 120);
        } else {
            window.addEventListener('load', function () {
                setTimeout(cb, 120);
            });
        }
    }

    function initSalesChart() {
        if (salesChartReady || typeof Chart === 'undefined') return;

        var canvas = document.getElementById('salesPerformanceChart');
        if (!canvas) return;

        salesChartReady = true;
        var ctx = canvas.getContext('2d');

        salesChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Leads',
                        data: [55, 45, 65, 75, 60, 85],
                        backgroundColor: ['#FF6B35', '#1A2B4A', '#FF6B35', '#1A2B4A', '#FF6B35', '#1A2B4A'],
                        borderRadius: 4,
                        barThickness: 7,
                        categoryPercentage: 0.72,
                        barPercentage: 0.9
                    },
                    {
                        label: 'Deals',
                        data: [40, 35, 50, 60, 48, 70],
                        backgroundColor: ['#1A2B4A', '#FF6B35', '#1A2B4A', '#FF6B35', '#1A2B4A', '#FF6B35'],
                        borderRadius: 4,
                        barThickness: 7,
                        categoryPercentage: 0.72,
                        barPercentage: 0.9
                    },
                    {
                        label: 'Deals Trend',
                        type: 'line',
                        data: [40, 45, 55, 65, 60, 72],
                        borderColor: '#1A2B4A',
                        backgroundColor: 'transparent',
                        borderWidth: 1.5,
                        borderDash: [4, 3],
                        pointRadius: 2.5,
                        pointBackgroundColor: '#1A2B4A',
                        pointBorderColor: '#1A2B4A',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 3000,
                    easing: 'easeOutQuart'
                },
                legend: { display: false },
                layout: {
                    padding: { left: 0, right: 0, top: 5, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        ticks: { fontSize: 7, fontColor: '#aaa', padding: 2 }
                    }],
                    yAxes: [{
                        gridLines: { color: '#eee', drawBorder: false, lineWidth: 1 },
                        ticks: {
                            fontSize: 7,
                            fontColor: '#aaa',
                            beginAtZero: true,
                            max: 100,
                            stepSize: 25,
                            padding: 5
                        }
                    }]
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    bodyFontSize: 10,
                    titleFontSize: 10
                }
            }
        });
    }

    function bindSectionChartScroll(section, startCharts) {
        if (!section || section.getAttribute('data-chart-bound') === '1') return;
        section.setAttribute('data-chart-bound', '1');

        function runCharts() {
            if (typeof Chart === 'undefined') {
                setTimeout(runCharts, 100);
                return;
            }
            startCharts();
        }

        if (!window.IntersectionObserver) {
            runCharts();
            return;
        }

        var done = false;
        var wasInView = false;
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                var inView = entry.isIntersecting && entry.intersectionRatio >= 0.15;
                if (inView && !wasInView && !done) {
                    done = true;
                    section.classList.add('nbi-inview');
                    runCharts();
                    obs.disconnect();
                }
                wasInView = inView;
            });
        }, SCROLL_IO);
        obs.observe(section);
    }

    function bindBusinessChartScroll() {
        var section = document.querySelector('.nbi-section-business');
        bindSectionChartScroll(section, function () {
            initSalesChart();
            initEngagementChart();
            if (typeof window.__nyRenderGrowthSparkline === 'function') {
                window.__nyRenderGrowthSparkline();
            }
        });
    }

    function bindPipelineChartScroll() {
        var section = document.querySelector('.nbi-section-pipeline');
        bindSectionChartScroll(section, function () {
            initActivityChart();
            initPerformanceChart();
        });
    }

    function bootChart() {
        bindBusinessChartScroll();
        bindPipelineChartScroll();
    }

    function initActivityChart() {
        var canvas = document.getElementById('nbiActivityChart');
        if (!canvas || activityChartReady || typeof Chart === 'undefined') return;
        activityChartReady = true;

        var ctx = canvas.getContext('2d');
        var grad = ctx.createLinearGradient(0, 0, 0, 88);
        grad.addColorStop(0, 'rgba(255, 107, 53, 0.15)');
        grad.addColorStop(1, 'rgba(255, 107, 53, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['May 10', 'May 11', 'May 12', 'May 13', 'May 14', 'May 15', 'May 16'],
                datasets: [{
                    data: [280, 420, 380, 550, 845, 680, 750],
                    borderColor: '#FF6B35',
                    backgroundColor: grad,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#FF6B35',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1.5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 4000,
                    easing: 'easeOutQuart'
                },
                legend: { display: false },
                scales: {
                    xAxes: [{ display: false }],
                    yAxes: [{
                        display: true,
                        gridLines: { color: '#f0f0f0', drawBorder: false },
                        ticks: { fontSize: 6, fontColor: '#aaa', max: 1000, stepSize: 250, beginAtZero: true }
                    }]
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#fff',
                    titleFontColor: '#1A2B4A',
                    bodyFontColor: '#1A2B4A',
                    borderColor: '#eee',
                    borderWidth: 1,
                    callbacks: {
                        label: function (tooltipItem) {
                            return 'Activities: ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        });
    }

    function initPerformanceChart() {
        var canvas = document.getElementById('nbiPerformanceChart');
        if (!canvas || performanceChartReady || typeof Chart === 'undefined') return;
        performanceChartReady = true;

        var ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [86, 14],
                    backgroundColor: ['#22C55E', '#F3F4F6'],
                    borderWidth: 0,
                    hoverBackgroundColor: ['#22C55E', '#F3F4F6']
                }, {
                    data: [60, 20, 20],
                    backgroundColor: ['#3B82F6', '#60A5FA', '#93C5FD'],
                    borderWidth: 0,
                    weight: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 75,
                legend: { display: false },
                tooltips: { enabled: false },
                animation: {
                    animateRotate: true,
                    animateScale: false,
                    duration: 4000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    function initEngagementChart() {
        var canvas = document.getElementById('nbiEngagementChart');
        if (!canvas || engagementChartReady || typeof Chart === 'undefined') return;
        engagementChartReady = true;

        var ctx = canvas.getContext('2d');
        var finalData = [58, 64, 71, 76.8];
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Engagement',
                    data: [0, 0, 0, 0],
                    borderColor: '#22C55E',
                    backgroundColor: 'rgba(34, 197, 94, 0.12)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#22C55E',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1800,
                    easing: 'easeOutQuart',
                    animateRotate: false,
                    animateScale: false
                },
                legend: { display: false },
                layout: { padding: { left: 0, right: 0, top: 5, bottom: 0 } },
                scales: {
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        ticks: { fontSize: 7, fontColor: '#aaa', padding: 2 }
                    }],
                    yAxes: [{
                        gridLines: { color: '#f0f0f0', drawBorder: false, lineWidth: 1 },
                        ticks: {
                            fontSize: 7,
                            fontColor: '#aaa',
                            beginAtZero: true,
                            max: 100,
                            stepSize: 25,
                            padding: 5
                        }
                    }]
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    bodyFontSize: 10,
                    titleFontSize: 10
                }
            }
        });
        chart.data.datasets[0].data = finalData;
        chart.update();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            whenPageReady(bootChart);
        });
    } else {
        whenPageReady(bootChart);
    }
})();


/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   CANVAS SPARKLINES (Business + Pipeline sections)
   â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */

(function () {
    'use strict';

    function drawAreaChart(canvas, values, options, progress) {
        if (!canvas || !values || values.length < 2) return;

        options = options || {};
        progress = typeof progress === 'number' ? Math.max(0, Math.min(1, progress)) : 1;
        var color = options.color || '#22C55E';
        var dpr = window.devicePixelRatio || 1;
        var cssW = canvas.clientWidth || parseInt(canvas.getAttribute('width'), 10) / 2 || 140;
        var cssH = canvas.clientHeight || parseInt(canvas.getAttribute('height'), 10) / 2 || 44;
        cssW = Math.max(cssW, 80);
        cssH = Math.max(cssH, 36);

        canvas.width = Math.round(cssW * dpr);
        canvas.height = Math.round(cssH * dpr);
        canvas.style.width = cssW + 'px';
        canvas.style.height = cssH + 'px';

        var ctx = canvas.getContext('2d');
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.clearRect(0, 0, cssW, cssH);

        var pad = { top: 6, right: 4, bottom: 6, left: 4 };
        var max = Math.max.apply(null, values);
        var min = Math.min.apply(null, values);
        var range = max - min || 1;
        var innerW = cssW - pad.left - pad.right;
        var innerH = cssH - pad.top - pad.bottom;
        var step = innerW / (values.length - 1);

        var baselineY = cssH - pad.bottom;
        var pts = values.map(function (v, i) {
            var targetY = pad.top + innerH * (1 - (v - min) / range);
            return {
                x: pad.left + i * step,
                y: baselineY - ((baselineY - targetY) * progress)
            };
        });

        var clipW = pad.left + innerW * progress;
        ctx.save();
        ctx.beginPath();
        ctx.rect(0, 0, clipW, cssH);
        ctx.clip();

        ctx.beginPath();
        ctx.moveTo(pts[0].x, baselineY);
        pts.forEach(function (p) { ctx.lineTo(p.x, p.y); });
        ctx.lineTo(pts[pts.length - 1].x, baselineY);
        ctx.closePath();
        var grad = ctx.createLinearGradient(0, pad.top, 0, cssH);
        grad.addColorStop(0, 'rgba(34,197,94,0.32)');
        grad.addColorStop(1, 'rgba(34,197,94,0)');
        ctx.fillStyle = grad;
        ctx.fill();

        ctx.beginPath();
        pts.forEach(function (p, i) {
            if (i === 0) ctx.moveTo(p.x, p.y);
            else ctx.lineTo(p.x, p.y);
        });
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.stroke();
        ctx.restore();

        pts.forEach(function (p, i) {
            if (p.x > clipW + 2) return;
            ctx.beginPath();
            ctx.arc(p.x, p.y, 2.5, 0, Math.PI * 2);
            ctx.fillStyle = color;
            ctx.fill();
            if (options.highlightIndex === i) {
                ctx.fillStyle = '#1A2B4A';
                ctx.fillRect(p.x - 16, p.y + 6, 32, 12);
                ctx.fillStyle = '#fff';
                ctx.font = 'bold 7px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(options.tooltip || '845', p.x, p.y + 15);
            }
        });

        if (options.arrowEnd && progress >= 0.98) {
            var last = pts[pts.length - 1];
            ctx.beginPath();
            ctx.moveTo(last.x - 4, last.y - 2);
            ctx.lineTo(last.x + 2, last.y);
            ctx.lineTo(last.x - 4, last.y + 2);
            ctx.closePath();
            ctx.fillStyle = color;
            ctx.fill();
        }
    }

    function animateAreaChart(canvas, values, options) {
        var duration = (options && options.duration) || 1600;
        var start = null;
        function frame(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / duration, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            drawAreaChart(canvas, values, options, eased);
            if (p < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    }

    function renderAll() {
        var growth = document.getElementById('nbiGrowthChart');
        if (growth) {
            animateAreaChart(growth, [4, 8, 14, 20, 26, 30, 36], { color: '#22C55E', arrowEnd: true });
        }
    }

    window.__nyRenderGrowthSparkline = renderAll;
})();

/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   VIDEO SECTION â€“ scroll reveal
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
(function () {
    'use strict';

    if (window.__nyVideoSectionInit) return;
    window.__nyVideoSectionInit = true;

    var SCROLL_IO = { threshold: [0, 0.15, 0.35], rootMargin: '0px 0px -10% 0px' };

    function initVideoSectionReveal() {
        var section = document.querySelector('.nbi-video-section');
        if (!section || section.classList.contains('nbi-inview')) return;

        if (!window.IntersectionObserver) {
            section.classList.add('nbi-inview');
            return;
        }

        var done = false;
        var wasInView = false;
        var obs = new IntersectionObserver(function (entries) {
            var entry = entries[0];
            var inView = entry.isIntersecting && entry.intersectionRatio >= 0.15;
            if (inView && !wasInView && !done) {
                done = true;
                section.classList.add('nbi-inview');
                obs.disconnect();
            }
            wasInView = inView;
        }, SCROLL_IO);
        obs.observe(section);
    }

    function whenPageReady(cb) {
        if (document.readyState === 'complete') {
            setTimeout(cb, 120);
        } else {
            window.addEventListener('load', function () {
                setTimeout(cb, 120);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            whenPageReady(initVideoSectionReveal);
        });
    } else {
        whenPageReady(initVideoSectionReveal);
    }
})();

/* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
   TESTIMONIALS SLIDER â€“ 3 on desktop, auto + manual scroll
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
(function () {
    'use strict';

    if (window.__nyTestimonialsSliderInit) return;

    var AUTO_MS = 5000;
    var SWIPE_PX = 48;

    function getVisibleCount() {
        if (window.matchMedia('(min-width: 992px)').matches) return 3;
        if (window.matchMedia('(min-width: 768px)').matches) return 2;
        return 1;
    }

    function initTestimonialsSlider() {
        if (window.__nyTestimonialsSliderInit) return;
        var container = document.querySelector('.ny-testimonials-slider-container');
        if (!container) return;

        var track = container.querySelector('.ny-testimonials-track');
        var slides = track ? track.querySelectorAll('.ny-slide') : [];
        if (!track || !slides.length) return;

        window.__nyTestimonialsSliderInit = true;

        var prevBtn = container.querySelector('.ny-testimonials-prev');
        var nextBtn = container.querySelector('.ny-testimonials-next');
        var dotsWrap = container.closest('.container')
            ? container.closest('.container').querySelector('.ny-testimonials-dots')
            : document.querySelector('.ny-testimonials-dots');

        var index = 0;
        var visible = getVisibleCount();
        var maxIndex = 0;
        var slideWidth = 0;
        var autoTimer = null;
        var reducedMotion = window.matchMedia
            && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var drag = { active: false, startX: 0, delta: 0, baseOffset: 0 };

        function maxSlideIndex() {
            return Math.max(0, slides.length - visible);
        }

        function buildDots() {
            if (!dotsWrap) return;
            dotsWrap.innerHTML = '';
            for (var i = 0; i < slides.length; i++) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'ny-testimonial-dot' + (i === index ? ' active' : '');
                dot.setAttribute('role', 'tab');
                dot.setAttribute('aria-label', 'Show testimonial ' + (i + 1));
                dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
                (function (slideIndex) {
                    dot.addEventListener('click', function () {
                        goTo(slideIndex, true);
                        restartAuto();
                    });
                })(i);
                dotsWrap.appendChild(dot);
            }
        }

        function updateDots() {
            if (!dotsWrap) return;
            var dots = dotsWrap.querySelectorAll('.ny-testimonial-dot');
            dots.forEach(function (dot, i) {
                var on = i === index;
                dot.classList.toggle('active', on);
                dot.setAttribute('aria-selected', on ? 'true' : 'false');
            });
        }

        function updateNavButtons() {
            if (prevBtn) prevBtn.disabled = index <= 0;
            if (nextBtn) nextBtn.disabled = index >= maxIndex;
        }

        function applyTransform(animate) {
            var offset = index * slideWidth;
            track.style.transition = (animate === false || reducedMotion)
                ? 'none'
                : 'transform 0.55s cubic-bezier(0.16, 1, 0.3, 1)';
            track.style.transform = 'translate3d(-' + offset + 'px, 0, 0)';
        }

        function goTo(nextIndex, animate) {
            index = Math.max(0, Math.min(nextIndex, maxIndex));
            applyTransform(animate !== false);
            updateDots();
            updateNavButtons();
        }

        function step(dir) {
            if (dir > 0 && index >= maxIndex) {
                goTo(0, true);
            } else if (dir < 0 && index <= 0) {
                goTo(maxIndex, true);
            } else {
                goTo(index + dir, true);
            }
        }

        function refreshLayout() {
            visible = getVisibleCount();
            maxIndex = maxSlideIndex();
            slideWidth = container.clientWidth / visible;
            for (var s = 0; s < slides.length; s++) {
                slides[s].style.flex = '0 0 ' + slideWidth + 'px';
                slides[s].style.maxWidth = slideWidth + 'px';
            }
            if (index > maxIndex) index = maxIndex;
            applyTransform(false);
            updateDots();
            updateNavButtons();
        }

        function stopAuto() {
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        }

        function startAuto() {
            stopAuto();
            if (reducedMotion || maxIndex === 0) return;
            autoTimer = setInterval(function () {
                step(1);
            }, AUTO_MS);
        }

        function restartAuto() {
            stopAuto();
            startAuto();
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                step(-1);
                restartAuto();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                step(1);
                restartAuto();
            });
        }

        container.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                step(-1);
                restartAuto();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                step(1);
                restartAuto();
            }
        });

        container.addEventListener('mouseenter', stopAuto);
        container.addEventListener('mouseleave', startAuto);
        container.addEventListener('focusin', stopAuto);
        container.addEventListener('focusout', function (e) {
            if (!container.contains(e.relatedTarget)) startAuto();
        });

        function onPointerDown(e) {
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            drag.active = true;
            drag.startX = e.clientX;
            drag.delta = 0;
            drag.baseOffset = index * slideWidth;
            container.classList.add('is-dragging');
            stopAuto();
            if (track.setPointerCapture && e.pointerId !== undefined) {
                try { track.setPointerCapture(e.pointerId); } catch (err) { /* ignore */ }
            }
        }

        function onPointerMove(e) {
            if (!drag.active) return;
            drag.delta = e.clientX - drag.startX;
            track.style.transition = 'none';
            track.style.transform = 'translate3d(' + (-drag.baseOffset + drag.delta) + 'px, 0, 0)';
        }

        function onPointerUp() {
            if (!drag.active) return;
            drag.active = false;
            container.classList.remove('is-dragging');
            if (drag.delta < -SWIPE_PX) step(1);
            else if (drag.delta > SWIPE_PX) step(-1);
            else applyTransform(true);
            drag.delta = 0;
            restartAuto();
        }

        track.addEventListener('pointerdown', onPointerDown);
        track.addEventListener('pointermove', onPointerMove);
        track.addEventListener('pointerup', onPointerUp);
        track.addEventListener('pointercancel', onPointerUp);
        track.addEventListener('lostpointercapture', onPointerUp);

        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(refreshLayout, 120);
        });

        buildDots();
        refreshLayout();
        container.classList.add('ny-slider-ready');
        startAuto();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTestimonialsSlider);
    } else {
        initTestimonialsSlider();
    }
})();





