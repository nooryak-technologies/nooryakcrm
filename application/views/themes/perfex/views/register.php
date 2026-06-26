<?php defined('BASEPATH') or exit('No direct script access allowed');

$ny_logo_icon_url = base_url('media/master/public/page_builder/pages/softland/assets/landingpage/image/logo_icon.png');
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

/* =====================================================
   RESET & BASE
===================================================== */
*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    width: 100%;
    min-height: 100%;
    overflow-x: hidden;
    background: #f0f0f0;
    font-family: 'Inter', sans-serif;
}

body.customers > .header,
footer.footer {
    display: none !important;
}

#wrapper,
#content,
.container {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
}

/* =====================================================
   PAGE WRAPPER
===================================================== */
.crm-register-wrap {
    width: 100%;
    min-height: 100vh;
    background: #f0f0f0;
}

.crm-register-shell {
    width: 100%;
    min-height: 100vh;
    display: flex;
    align-items: stretch;
        justify-content: space-evenly;
            background: #faf9f7;
}

/* =====================================================
   LEFT PANEL
===================================================== */
.crm-register-visual {
    width: 50%;
    min-width: 50%;
    background: #faf9f7;
    padding: 30px 52px 26px 52px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
}

/* Decorative dot grid — top right */
.crm-register-visual::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 180px;
    height: 180px;
    background-image: radial-gradient(circle, rgba(255,107,53,.18) 1.5px, transparent 1.5px);
    background-size: 18px 18px;
    pointer-events: none;
    z-index: 0;
}

/* Decorative dot grid — bottom left */
.crm-register-visual::after {
    content: '';
    position: absolute;
    bottom: 50px;
    left: 0;
    width: 140px;
    height: 140px;
    background-image: radial-gradient(circle, rgba(255,107,53,.15) 1.5px, transparent 1.5px);
    background-size: 18px 18px;
    pointer-events: none;
    z-index: 0;
}

.crm-register-visual-inner {
    width: 100%;
    position: relative;
    z-index: 1;
}

/* ----- Brand ----- */
.crm-register-brand {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 24px;
}

.crm-brand-mark {
   width: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    line-height: 0;
}

.crm-brand-mark img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.crm-brand-text {
    font-family: 'Bebas Neue', 'Arial Narrow', sans-serif;
    font-size: 28px;
    font-weight: 400;
    letter-spacing: 0.05em;
    line-height: 1;
    text-transform: uppercase;
}

.crm-brand-text .ny-c-o,
.crm-brand-text .ny-c-r,
.crm-brand-text .ny-c-ak,
.crm-brand-text .ny-c-crm {
    color: #0c1629;
}

.crm-brand-text .ny-c-y {
    color: #f38d4e;
}

/* ----- Headline ----- */
.crm-register-visual h2 {
    font-size: 60px;
    line-height: 1.0;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -2px;
    margin-bottom: 14px;
}

.crm-register-visual h2 span {
    color: #ff6b35;
}

.crm-visual-lead {
    max-width: 480px;
    color: #64748b;
    font-size: 15px;
    line-height: 1.75;
    margin-bottom: 22px;
}

/* ----- Illustration ----- */
.crm-register-illus-wrap {
    width: 100%;
    position: relative;
}

.crm-illus-placeholder {
    width: 100%;
    background: #f5f2ed;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    padding: 26px 22px 18px 22px;
    min-height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Floating 10K badge — top right */
.dash-float-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    background: #fff;
    border-radius: 10px;
    padding: 7px 11px;
    box-shadow: 0 4px 14px rgba(15,23,42,.13);
    font-size: 10px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    gap: 2px;
    z-index: 2;
}

.dash-float-badge-top {
    display: flex;
    align-items: center;
    gap: 6px;
}

.dash-float-avatars {
    display: flex;
    align-items: center;
    gap: 0;
}

.dash-av {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7px;
    font-weight: 900;
    color: #fff;
}

.dash-av + .dash-av { margin-left: -6px; }
.av1 { background: #ff6b35; }
.av2 { background: #3b82f6; }
.av3 { background: #22c55e; }
.av4 { background: #a855f7; }

.dash-float-badge-stars {
    color: #ff6b35;
    font-size: 10px;
    letter-spacing: 1px;
}

.dash-float-badge-text {
    font-size: 9.5px;
    color: #64748b;
    font-weight: 600;
}

/* Inner mock dashboard card */
.dashboard-card {
    background: #fff;
    border-radius: 13px;
    box-shadow: 0 8px 28px rgba(15,23,42,.10);
    padding: 14px 16px;
    width: 90%;
    max-width: 430px;
    position: relative;
    z-index: 1;
}

.dash-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 11px;
}

.dash-win-btns { display: flex; gap: 5px; }
.dash-win-btn  {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    display: inline-block;
}
.dash-win-btn.r { background: #ff5f57; }
.dash-win-btn.y { background: #febc2e; }
.dash-win-btn.g { background: #28c840; }

.dash-title-text { font-size: 11.5px; font-weight: 800; color: #0f172a; }
.dash-overview   { font-size: 10px; color: #94a3b8; }

.dash-kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 7px;
    margin-bottom: 11px;
}

.dash-kpi {
    background: #f8fafc;
    border-radius: 8px;
    padding: 7px 5px;
    text-align: center;
}

.dash-kpi-val { font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.2; }
.dash-kpi-lbl { font-size: 9px; color: #94a3b8; margin-top: 1px; }
.dash-kpi-chg { font-size: 8.5px; color: #22c55e; font-weight: 700; margin-top: 1px; }

.dash-pipeline-title {
    font-size: 10px;
    color: #94a3b8;
    font-weight: 600;
    margin-bottom: 6px;
}

.dash-bar-row   { display: flex; align-items: center; gap: 7px; margin-bottom: 5px; }
.dash-bar-name  { font-size: 9.5px; color: #64748b; width: 60px; white-space: nowrap; font-weight: 500; }
.dash-bar-track { flex: 1; height: 6px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
.dash-bar-fill  { height: 100%; border-radius: 99px; background: #ff6b35; }
.dash-bar-fill.b { background: #0f172a; }
.dash-bar-fill.y { background: #fbbf24; }
.dash-bar-fill.s { background: #cbd5e1; }
.dash-bar-count { font-size: 9px; color: #94a3b8; width: 20px; text-align: right; }

/* People badge bottom-right */
.dash-people-badge {
    position: absolute;
    bottom: 12px;
    right: 14px;
    background: #fff;
    border-radius: 9px;
    padding: 5px 11px;
    box-shadow: 0 4px 12px rgba(15,23,42,.10);
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 2;
}

.dash-people-avs { display: flex; }
.dash-people-av  {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7px;
    font-weight: 900;
    color: #fff;
}
.dash-people-av + .dash-people-av { margin-left: -6px; }
.dp1 { background: #ff6b35; }
.dp2 { background: #3b82f6; }
.dp3 { background: #22c55e; }
.dp4 { background: #a855f7; }

.dash-people-text { font-size: 9.5px; font-weight: 700; color: #0f172a; }

/* ----- Perks row ----- */
.crm-register-perks {
    margin-top: 18px;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    list-style: none;
    padding: 0;
}

.crm-register-perks li {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 15px;
    padding: 15px 8px 12px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #111827;
    line-height: 1.4;
}

.crm-perk-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: #fff7f4;
    border: 1px solid #ffe0d2;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    color: #ff6b35;
    font-size: 15px;
}

.crm-register-perks li small {
    display: block;
    margin-top: 4px;
    font-size: 10px;
    font-weight: 500;
    color: #94a3b8;
}

.crm-mob-field-icon {
    display: none;
}

.crm-register-perks .crm-perk-ssl {
    display: none;
}

/* ----- Stats bar (Trust badges) ----- */
.crm-register-stats {
    margin-top: 24px;
    width: 100%;
    display: grid;
    grid-template-columns: 1.4fr 1fr 1fr 1fr;
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
}

.crm-stat-item {
    padding: 16px 20px;
    border-right: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.crm-stat-item:last-child {
    border-right: none;
}

.crm-stat-rating {
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    gap: 16px;
    text-align: left;
}

.crm-stat-shield {
    width: 44px;
    height: 48px;
    background: #fff7f4;
    border: 1px solid #ffd8c8;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ff6b35;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(255, 107, 53, 0.1);
}

.crm-stat-rating-body {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.crm-stat-rating-text {
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
}

.crm-stat-stars {
    display: flex;
    gap: 2px;
    color: #ff6b35;
    font-size: 11px;
    margin: 2px 0;
}

.crm-stat-source {
    font-size: 11px;
    font-weight: 500;
    color: #64748b;
}

.crm-stat-number {
    display: block;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}

.crm-stat-label {
    display: block;
    font-size: 11px;
    font-weight: 500;
    color: #64748b;
    margin-top: 4px;
}

/* =====================================================
   RIGHT PANEL
===================================================== */
.crm-register-form-col {
   
    display: flex;
    align-items: center;
    justify-content: center;
}

.crm-register-card {
      width: 100%;
    max-width: 660px;
    background: #fff;
    padding: 34px 38px;
    box-shadow: 0 4px 30px rgba(15, 23, 42, .07);
    margin-top: 1rem;
        border-radius: 30px;
}

/* ----- Form header ----- */
.crm-register-form-head {
    text-align: center;
    margin-bottom: 20px;
}

.crm-form-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 16px;
    border-radius: 999px;
    background: #fff4ef;
    color: #ff6b35;
    font-size: 9.5px;
    font-weight: 800;
    letter-spacing: 1.3px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.crm-register-top h1 {
     font-size: 42px;
    line-height: 1.0;
    font-weight: 700;
    letter-spacing: -1.8px;
    color: #0f172a;
    margin-bottom: 8px;
}

.crm-subtitle {
    font-size: 14px;
    color: #64748b;
}

/* ----- Plan summary card ----- */
.crm-register-plan-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    border: 1px solid #ffd0b8;
    border-radius: 16px;
    margin-bottom: 24px;
    background: #fffcfb;
    box-shadow: 0 2px 10px rgba(255, 107, 53, 0.03);
}

.crm-register-plan-summary-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.crm-register-plan-summary-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: #fff5ef;
    border: 1.5px solid #ffe8dd;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ff6b35;
    font-size: 24px;
    flex-shrink: 0;
    position: relative;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.5);
}

.crm-register-plan-summary-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 2px;
}

.crm-register-plan-summary-name {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.crm-register-plan-summary-trial {
    font-size: 13px;
    color: #64748b;
    margin-top: 4px;
    font-weight: 500;
}

.crm-register-plan-summary-right {
    text-align: right;
    flex-shrink: 0;
}

.crm-plan-users-line {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
}

.crm-register-plan-summary-price-wrap {
    display: flex;
    align-items: baseline;
    justify-content: flex-end;
    gap: 6px;
}

.crm-register-plan-summary-price-amount {
    font-size: 19px;
    font-weight: 800;
    color: #ff6b35;
    line-height: 1;
}

.crm-register-plan-summary-price-period {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

/* ----- Form layout grid ----- */
.crm-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 14px;
}

.crm-form-row.full {
    grid-template-columns: 1fr;
}

.form-group {
    margin-bottom: 13px;
}

.control-label {
    display: block;
    margin-bottom: 6px;
    font-size: 12.5px;
    font-weight: 700;
    color: #111827;
}

/* ----- Form controls ----- */
.form-control {
    width: 100% !important;
    height: 50px !important;
    border-radius: 11px !important;
    border: 1.5px solid #e2e5eb !important;
    background: #fff !important;
    padding: 0 14px !important;
    font-size: 13.5px !important;
    box-shadow: none !important;
    color: #0f172a;
    transition: border-color .18s, box-shadow .18s;
    font-family: 'Inter', sans-serif;
}

.form-control:focus {
    border-color: #ff6b35 !important;
    box-shadow: 0 0 0 3.5px rgba(255,107,53,.11) !important;
    outline: none;
}

.form-control::placeholder {
    color: #b8c2cc;
}

/* ----- Domain field (handled by crm-domain-wrap) ----- */
.register-saas-info-group {
    display: block !important;
    width: 100%;
}
.register-saas-info-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #111827;
}
/* Hide default help text */
.register-saas-info-group .text-muted,
.register-saas-info-group .help-block,
.register-saas-info-group small,
.register-saas-info-group .form-text {
    display: none !important;
}

.form-select {
    width: 100% !important;
    height: 50px !important;
    border-radius: 11px !important;
    border: 1.5px solid #e2e5eb !important;
    background: #fff !important;
    padding: 0 14px !important;
    font-size: 13.5px !important;
    box-shadow: none !important;
    color: #0f172a;
    transition: border-color .18s, box-shadow .18s;
    font-family: 'Inter', sans-serif;
    -webkit-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
}

.form-select:focus {
    border-color: #ff6b35 !important;
    box-shadow: 0 0 0 3.5px rgba(255,107,53,.11) !important;
    outline: none;
}

/* ----- Bootstrap selectpicker compat ----- */
.bootstrap-select { 
    width: 100% !important; 
    border: none !important;
    background: transparent !important;
}
.bootstrap-select > .dropdown-toggle {
    width: 100% !important;
    height: 50px !important;
    border-radius: 11px !important;
    border: 1.5px solid #e2e5eb !important;
    background: #fff !important;
    padding: 0 14px !important;
    font-size: 13.5px !important;
    box-shadow: none !important;
    display: flex !important;
    align-items: center !important;
    color: #0f172a !important;
}
.bootstrap-select > .dropdown-toggle:focus {
    border-color: #ff6b35 !important;
    box-shadow: 0 0 0 3.5px rgba(255,107,53,.11) !important;
    outline: none !important;
}
.bootstrap-select .filter-option {
    display: flex;
    align-items: center;
}
.bootstrap-select .bs-caret {
    display: none !important;
}
/* Custom chevron for selectpicker */
.bootstrap-select > .dropdown-toggle::after {
    content: '';
    width: 12px;
    height: 8px;
    margin-left: auto;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2394a3b8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    border: none !important;
}

/* ----- Phone group ----- */
.register-contact-phone-group {
    display: flex;
    gap: 8px;
    width: 100%;
    min-width: 0;
    position: relative;
    z-index: 10;
}

.register-contact-phone-group .form-control {
    flex: 1 1 0;
    min-width: 0;
}

/* Phone country picker — shows flag + dial code + chevron */
.register-contact-phone-country {
    width: 96px;
    min-width: 96px;
    height: 50px;
    border-radius: 11px;
    border: 1.5px solid #e2e5eb;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: visible;
    flex-shrink: 0;
    transition: border-color .18s;
    cursor: pointer;
}

.register-contact-phone-country:focus-within {
    border-color: #ff6b35;
    box-shadow: 0 0 0 3.5px rgba(255,107,53,.11);
    overflow: visible;
}

.register-contact-phone-country.open {
    overflow: visible;
}

.crm-phone-flag-select {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 5;
    pointer-events: auto;
    background: transparent;
    border: none;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.crm-phone-visual {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 0 10px;
    z-index: 1;
    cursor: pointer;
}

.crm-phone-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 280px;
    max-height: 320px;
    overflow-y: auto;
    background: #fff;
    border: 1.5px solid #e2e5eb;
    border-top: none;
    border-radius: 0 0 11px 11px;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: none;
    margin-top: -2px;
}

.crm-phone-dropdown-menu.active {
    display: block;
}

.crm-phone-dropdown-item {
    padding: 10px 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.15s;
    font-size: 13px;
    color: #0f172a;
}

.crm-phone-dropdown-item:last-child {
    border-bottom: none;
}

.crm-phone-dropdown-item:hover {
    background-color: #f8fafc;
}

.crm-phone-dropdown-item.active {
    background-color: #fff7f4;
    border-left: 3px solid #ff6b35;
    padding-left: 9px;
}

.crm-phone-dropdown-flag {
    width: 24px;
    height: 18px;
    border-radius: 2px;
    object-fit: cover;
    flex-shrink: 0;
}

.crm-phone-dropdown-code {
    min-width: 45px;
    font-weight: 600;
    color: #ff6b35;
}

.crm-phone-dropdown-name {
    flex: 1;
    color: #64748b;
}

.crm-phone-flag-img {
    width: 22px;
    height: 16px;
    border-radius: 2px;
    object-fit: cover;
    flex-shrink: 0;
}

.crm-phone-code-text {
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
}

.crm-phone-chevron {
    font-size: 9px;
    color: #94a3b8;
    margin-left: 1px;
}

/* ----- Domain field ----- */
.crm-domain-wrap {
    display: flex;
    align-items: center;
    border: 1.5px solid #e2e5eb;
    border-radius: 11px;
    overflow: hidden;
    background: #fff;
    height: 50px;
    min-width: 0;
    width: 100%;
    transition: border-color .18s, box-shadow .18s;
}

.crm-domain-wrap:focus-within {
    border-color: #ff6b35;
    box-shadow: 0 0 0 3.5px rgba(255,107,53,.11);
}

.crm-domain-prefix,
.crm-domain-suffix {
    padding: 0 11px;
    font-size: 12.5px;
    font-weight: 600;
    color: #94a3b8;
    background: #f8fafc;
    height: 100%;
    display: flex;
    align-items: center;
    white-space: nowrap;
    flex-shrink: 0;
}

.crm-domain-prefix {
    border-right: 1px solid #e2e5eb;
}

.crm-domain-suffix {
    border-left: 1px solid #e2e5eb;
}

.crm-domain-input {
    flex: 1 1 0;
    min-width: 0;
    width: 1%;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    height: 100% !important;
    border-radius: 0 !important;
    padding: 0 10px !important;
    font-size: 13.5px !important;
    background: transparent !important;
    color: #0f172a;
    font-family: 'Inter', sans-serif;
}

.crm-domain-input::placeholder { color: #b8c2cc; }

/* ----- Password ----- */
.crm-password-field { position: relative; }
.crm-password-field .form-control { padding-right: 44px !important; }

.crm-password-toggle {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    border: none;
    background: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    transition: color .18s;
    line-height: 1;
}

.crm-password-toggle:hover { color: #ff6b35; }

/* ----- Terms checkbox ----- */
.crm-terms-row {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-bottom: 16px;
    margin-top: 4px;
}

.crm-checkbox {
    width: 17px;
    height: 17px;
    border-radius: 5px;
    border: 1.5px solid #e2e5eb;
    background: #fff;
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 1px;
    appearance: none;
    -webkit-appearance: none;
    transition: background .18s, border-color .18s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.crm-checkbox:checked {
    background: #ff6b35;
    border-color: #ff6b35;
}

.crm-checkbox:checked::after {
    content: '';
    display: block;
    width: 9px;
    height: 5px;
    border-left: 2px solid #fff;
    border-bottom: 2px solid #fff;
    transform: rotate(-45deg) translateY(-1px);
}

.crm-terms-label {
    font-size: 12.5px;
    color: #64748b;
    line-height: 1.6;
}

.crm-terms-label a {
    color: #ff6b35;
    font-weight: 700;
    text-decoration: none;
}

.crm-terms-label a:hover { text-decoration: underline; }

/* ----- Submit button ----- */
.crm-register-actions { margin-top: 4px; }

.btn-crm-submit {
    width: 100%;
    height: 54px;
    border: none;
    border-radius: 12px;
    background: #ff6b35;
    color: #fff;
    font-size: 15.5px;
    font-weight: 800;
    font-family: 'Inter', sans-serif;
    box-shadow: 0 10px 22px rgba(255,107,53,.26);
    cursor: pointer;
    transition: background .18s, box-shadow .18s, transform .12s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: -0.2px;
}

.btn-crm-submit:hover {
    background: #f45b24;
    box-shadow: 0 14px 28px rgba(255,107,53,.32);
    transform: translateY(-1px);
}

.btn-crm-submit:active { transform: translateY(0); }

/* ----- Login link ----- */
.crm-register-login {
    margin-top: 14px;
    text-align: center;
    color: #64748b;
    font-size: 13.5px;
}

.crm-register-login a {
    color: #ff6b35;
    font-weight: 700;
    text-decoration: none;
}

.crm-register-login a:hover { text-decoration: underline; }

/* ----- Security badges ----- */
.crm-register-secure {
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px solid #ececec;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.crm-secure-item { text-align: center; }

.crm-secure-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 7px;
    color: #64748b;
    font-size: 15px;
}

.crm-secure-item strong {
    display: block;
    font-size: 12.5px;
    color: #111827;
}

.crm-secure-item em {
    font-size: 10.5px;
    color: #94a3b8;
    font-style: normal;
}

/* =====================================================
   FOOTER BAR
===================================================== */
.crm-register-page-foot {
    border-top: 1px solid #e8e8e8;
    background: #fafafa;
    padding: 14px 20px;
    display: flex;
    justify-content: center;
    gap: 36px;
    flex-wrap: wrap;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
}

.crm-register-page-foot span {
    display: flex;
    align-items: center;
    gap: 7px;
}

/* =====================================================
   HONEYPOT
===================================================== */
.honey-element {
    position: absolute;
    left: -9999px;
    opacity: 0;
    height: 0;
    overflow: hidden;
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 1400px) {
    .crm-register-visual  { padding: 26px 40px; }
    .crm-register-visual h2 { font-size: 52px; }
    .crm-register-top h1  { font-size: 38px; }
}

@media (max-width: 1200px) {
    .crm-register-shell { flex-direction: column; }
    .crm-register-visual,
    .crm-register-form-col { width: 100%; min-width: 100%; max-width: 100%; }

    html, body,
    .crm-register-wrap,
    .crm-register-shell {
        background: #fff !important;
    }

    .crm-register-wrap {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0;
    }

    .crm-register-form-col {
        justify-content: flex-start !important;
        align-items: stretch !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .crm-register-form-col form,
    .crm-register-form-col #register-form {
        width: 100%;
        max-width: 100%;
    }

    .crm-register-card {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        box-shadow: none;
        border-radius: 0;
    }
}

/* =====================================================
   TABLET & MOBILE — reference single-column layout
===================================================== */
@media (max-width: 1024px) {
    html, body,
    .crm-register-wrap,
    .crm-register-shell,
    .crm-register-visual,
    .crm-register-form-col {
        background: #fff !important;
    }

    html, body {
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden;
    }

    #wrapper,
    #content,
    .container,
    .container-fluid,
    .row,
    [class*="col-"] {
        width: 100% !important;
        max-width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        float: none !important;
    }

    .crm-register-wrap {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        margin: 0;
        padding: 0;
    }

    .crm-register-shell {
        flex-direction: column;
        display: flex;
        align-items: stretch;
        width: 100%;
        max-width: 100%;
            background: #faf9f7;
    }

    .crm-register-form-col {
        justify-content: flex-start !important;
        align-items: stretch !important;
        align-self: stretch;
    }

    .crm-register-form-col form,
    .crm-register-form-col #register-form {
        width: 100%;
        max-width: 100%;
        display: block;
    }

    /* Flatten hero + form siblings for CSS reorder */
    .crm-register-visual,
    .crm-register-visual-inner {
        display: contents;
    }

    .crm-register-brand {
        order: 1;
        width: 100%;
        padding: 18px 20px 0;
        margin-bottom: 0;
        box-sizing: border-box;
    }

    .crm-brand-text {
        font-family: 'Inter', sans-serif;
        font-size: 17px;
        font-weight: 700;
        letter-spacing: -0.2px;
        text-transform: none;
    }

 
    .crm-register-visual h2 {
        order: 2;
        width: 100%;
        padding: 0 20px;
        box-sizing: border-box;
        text-align: center;
        font-size: 34px;
        letter-spacing: -1.2px;
        margin-bottom: 10px;
        line-height: 1.05;
    }

    .crm-visual-lead {
        order: 3;
        width: 100%;
        max-width: 100%;
        padding: 0 20px;
        box-sizing: border-box;
        text-align: center;
        font-size: 13.5px;
        line-height: 1.65;
        color: #64748b;
        margin-bottom: 16px;
    }

    .crm-register-illus-wrap {
        order: 4;
        width: 100%;
        padding: 0 12px;
        box-sizing: border-box;
        margin-bottom: 4px;
    }

    .crm-register-image img {
        width: 100%;
        max-height: 220px;
        object-fit: contain;
    }

    .crm-register-visual::before,
    .crm-register-visual::after {
        display: none;
    }

    .crm-register-stats {
        display: none !important;
    }

    .crm-register-form-col {
        order: 5;
        width: 100%;
        min-width: 100%;
        max-width: 100%;
        padding: 0 0 8px;
        border: none;
    }

    .crm-register-card {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0 24px;
        background: transparent;
        box-shadow: none;
        border-radius: 0;
    }

    .crm-register-form-head {
        display: none;
    }

    .crm-register-plan-summary {
        flex-direction: row;
        align-items: center;
        padding: 14px 16px;
        margin-bottom: 18px;
        border-radius: 12px;
        border-color: #ffd8c8;
        background: #fffcfb;
    }

    .crm-register-plan-summary-label {
        display: none;
    }

    .crm-register-plan-summary-icon {
        width: 52px;
        height: 52px;
        border-radius: 0;
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        font-size: 20px;
    }

    .crm-register-plan-summary-name {
        font-size: 20px;
        font-weight: 800;
    }

    .crm-register-plan-summary-trial {
        font-size: 12px;
        margin-top: 2px;
    }

    .crm-register-plan-summary-right {
        text-align: right;
        border: none;
        padding: 0;
    }

    .crm-register-plan-summary-price-amount {
        font-size: 22px;
    }

    .crm-register-plan-summary-price-period {
        font-size: 11px;
    }

    .crm-mob-field-icon {
        display: block;
    }

    /* Icon-in-field inputs (reference mockup) */
    .crm-register-card .control-label {
        display: none;
    }

    .crm-register-card .form-group {
        position: relative;
        margin-bottom: 12px;
    }

    .crm-register-card .form-control,
    .crm-register-card .bootstrap-select > .dropdown-toggle,
    .crm-register-card .form-select {
        padding-left: 44px !important;
        height: 48px !important;
        border-radius: 8px !important;
        border-color: #e2e8f0 !important;
        font-size: 13px !important;
    }

    .crm-register-card .form-control::placeholder {
        color: #94a3b8;
    }

    .crm-mob-field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        z-index: 3;
        pointer-events: none;
        line-height: 1;
    }

    .register-contact-phone-group {
        position: relative;
    }

    .register-contact-phone-group .crm-mob-field-icon {
        left: 106px;
        top: 50%;
        transform: translateY(-50%);
    }

    .register-contact-phone-group {
        min-width: 0;
    }

    .register-contact-phone-group .form-control {
        padding-left: 44px !important;
        flex: 1 1 0;
        min-width: 0;
    }

    .register-saas-info-group {
        position: relative;
        min-width: 0;
        overflow: hidden;
    }

    .register-saas-info-group .crm-domain-wrap {
        min-width: 0;
        width: 100%;
    }

    .register-saas-info-group .crm-domain-input {
        min-width: 0 !important;
        flex: 1 1 0;
    }

    .register-saas-info-group::before {
        content: '\f0ac';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        z-index: 3;
        pointer-events: none;
    }

    .register-saas-info-group .form-control,
    .register-saas-info-group input[type="text"] {
        padding-left: 44px !important;
    }

    .register-country-group .bootstrap-select > .dropdown-toggle {
        padding-left: 44px !important;
    }

    .crm-password-field .form-control {
        padding-right: 44px !important;
    }

    .crm-domain-wrap {
        height: 48px;
        border-radius: 8px;
        border-color: #e2e8f0;
    }

    .crm-domain-prefix {
        display: none;
    }

    .crm-domain-input {
        padding-left: 44px !important;
    }

    .crm-domain-wrap .crm-mob-field-icon {
        left: 14px;
    }

    .crm-domain-suffix {
        font-size: 11px;
        padding: 0 10px;
        color: #64748b;
        background: #f8fafc;
    }

    .register-contact-phone-country {
        width: 92px;
        min-width: 92px;
        height: 48px;
        border-radius: 8px;
    }

    .crm-form-row {
        grid-template-columns: 1fr 1fr;
        gap: 0 12px;
    }

    .crm-form-row.full,
    .crm-register-extra-fields .crm-form-row {
        grid-template-columns: 1fr;
    }

    .crm-form-row:has(.register-phone-group),
    .crm-form-row:has(.register-email-group) {
        grid-template-columns: 1fr !important;
    }

    .register-email-group,
    .register-phone-group,
    .register-country-group {
        grid-column: 1 / -1;
    }

    .crm-register-perks .crm-perk-ssl {
        display: list-item;
    }

    .crm-register-secure {
        display: none;
    }

    .crm-register-perks {
        order: 6;
        width: 100%;
        padding: 8px 24px 20px;
        box-sizing: border-box;
        margin-top: 0;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        list-style: none;
    }

    .crm-register-perks li {
        padding: 14px 8px 12px;
        border-radius: 12px;
        border-color: #eef2f6;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.35;
    }

    .crm-register-perks li small {
        display: none;
    }

    .crm-perk-icon {
        width: 36px;
        height: 36px;
        font-size: 14px;
        margin-bottom: 6px;
        border-radius: 10px;
    }

    .crm-form-row:has(.register-password-group) {
        grid-template-columns: 1fr !important;
    }

    .crm-register-page-foot {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        width: 100%;
        max-width: 100%;
        padding: 16px 24px 22px;
        background: #f8fafc;
        border-top: 1px solid #eef2f6;
        font-size: 11.5px;
        color: #64748b;
        box-sizing: border-box;
    }

    .crm-register-page-foot span:nth-child(3) {
        display: none;
    }

    .crm-register-page-foot span i {
        color: #94a3b8;
        font-size: 12px;
    }
}

/* Tablet (e.g. iPad 768–1024): full-width layout, comfortable side padding */
@media (min-width: 641px) and (max-width: 1024px) {
    .crm-register-brand {
        padding: 24px 36px 0;
    }

    .crm-register-visual h2 {
        padding: 0 36px;
        font-size: 38px;
    }

    .crm-visual-lead {
        padding: 0 36px;
        font-size: 14px;
    }

    .crm-register-illus-wrap {
        padding: 0 28px;
    }

    .crm-register-image img {
        max-height: 280px;
    }

    .crm-register-card {
        padding: 0 36px;
    }

    .crm-register-perks {
        padding: 12px 36px 28px;
        gap: 12px;
    }

    .crm-register-page-foot {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px 32px;
        padding: 18px 36px 24px;
    }
}

@media (max-width: 768px) {
    .crm-register-visual h2 {
        font-size: 30px;
    }

    .crm-register-perks {
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .crm-register-perks li {
        font-size: 10px;
        padding: 12px 6px 10px;
    }
}

@media (max-width: 640px) {
    .crm-form-row {
        grid-template-columns: 1fr !important;
        gap: 0 !important;
    }

    .crm-form-row > .form-group {
        min-width: 0;
    }

    .crm-register-visual h2 {
        font-size: 28px;
        letter-spacing: -1px;
    }

    .crm-register-plan-summary {
        padding: 12px 14px;
    }

    .crm-register-plan-summary-name {
        font-size: 18px;
    }

    .crm-register-plan-summary-price-amount {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .crm-register-brand {
        padding: 16px 16px 0;
                justify-content: center;
    }

    .crm-register-visual h2,
    .crm-visual-lead,
    .crm-register-illus-wrap,
    .crm-register-form-col,
    .crm-register-perks {
        padding-left: 16px;
        padding-right: 16px;
    }

    .crm-register-card {
        padding-left: 16px;
        padding-right: 16px;
    }

    .crm-register-form-col {
        padding-bottom: 6px;
    }

    .crm-register-perks {
        gap: 8px;
    }

    .crm-register-perks li {
        font-size: 9.5px;
    }

    .crm-perk-icon {
        width: 32px;
        height: 32px;
        font-size: 13px;
    }
}

/* iPhone SE / narrow phones (≤390px) — prevent phone & domain field overlap */
@media (max-width: 390px) {
    .register-contact-phone-group {
        gap: 6px;
    }

    .register-contact-phone-country {
        width: 80px;
        min-width: 80px;
    }

    .crm-phone-visual {
        padding: 0 6px;
        gap: 3px;
    }

    .crm-phone-flag-img {
        width: 18px;
        height: 13px;
    }

    .crm-phone-code-text {
        font-size: 11px;
    }

    .register-contact-phone-group .crm-mob-field-icon {
        left: 92px;
    }

    .register-contact-phone-group .form-control {
        padding-left: 36px !important;
        font-size: 12px !important;
    }

    .crm-domain-suffix {
        font-size: 9.5px;
        padding: 0 6px;
        max-width: 42%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .crm-domain-input {
        font-size: 12px !important;
        padding-left: 36px !important;
    }

    .register-saas-info-group::before {
        left: 12px;
        font-size: 12px;
    }

    .register-saas-info-group .form-control,
    .register-saas-info-group input[type="text"] {
        padding-left: 36px !important;
    }

    .crm-register-plan-summary {
        flex-wrap: wrap;
        gap: 10px;
    }

    .crm-register-plan-summary-right {
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .crm-plan-users-line {
        justify-content: flex-start;
        margin-bottom: 0;
    }
}

/* =====================================================
   INLINE OTP STYLING
===================================================== */
.btn-phone-verify-trigger {
    height: 50px;
    padding: 0 16px;
    background: #ff6b35;
    color: #fff;
    border: none;
    border-radius: 11px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.btn-phone-verify-trigger:hover {
    background: #f45b24;
}
.btn-phone-verify-trigger:disabled {
    background: #cbd5e1;
    color: #94a3b8;
    cursor: not-allowed;
}
.phone-verified-status {
    color: #22c55e;
    font-weight: 700;
    font-size: 13.5px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    padding: 0 8px;
}
.otp-error-alert {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    color: #ef4444;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 500;
    margin-top: 10px;
    text-align: left;
}
.btn-otp-resend-link {
    background: none;
    border: none;
    color: #ff6b35;
    font-weight: 700;
    cursor: pointer;
    padding: 0;
    font-size: 13.5px;
    text-decoration: none;
}
.btn-otp-resend-link:hover {
    text-decoration: underline;
}
.btn-crm-submit:disabled {
    background: #cbd5e1 !important;
    color: #94a3b8 !important;
    box-shadow: none !important;
    cursor: not-allowed !important;
    transform: none !important;
}
@media (max-width: 1024px) {
    .btn-phone-verify-trigger {
        height: 48px;
        border-radius: 8px;
    }
}

</style>

<?php
/* =====================================================
   PHP DATA
===================================================== */
$crm_register_plan_name  = 'Professional';
$crm_register_plan_price = '₹1,299';
$crm_register_plan_users = '5 Users';
$CI = &get_instance();
if (isset($CI->perfex_saas_model)) {
    $plan_key  = function_exists('perfex_saas_route_id_prefix') ? perfex_saas_route_id_prefix('plan') : 'plan';
    $plan_slug = $CI->session->{$plan_key} ?? '';
    if (!empty($plan_slug)) {
        $CI->db->where('slug', $plan_slug);
    } else {
        $CI->db->where('is_default', 1);
    }
    $plan_row = $CI->perfex_saas_model->packages()[0] ?? null;
    if (!empty($plan_row)) {
        $plan_row = (object) $plan_row;
        $crm_register_plan_name = $plan_row->name;
        if (!empty($plan_row->price) || (float)$plan_row->price === 0.0) {
            $crm_register_plan_price = app_format_money($plan_row->price, get_base_currency());
        }
    }
}
$crm_company_label  = e(get_option('companyname'));
$crm_brand_parts    = preg_split('/\s+/', trim(get_option('companyname')), 2);
$crm_brand_primary  = e($crm_brand_parts[0] ?? 'Nooryak');
$crm_brand_secondary= e($crm_brand_parts[1] ?? 'CRM');
if (stripos($crm_brand_secondary, 'crm') === false && stripos($crm_company_label, 'crm') === false) {
    $crm_brand_secondary = 'CRM';
}
?>

<div class="crm-register-wrap">
    <div class="crm-register-shell">

        <!-- ==========================================
             LEFT PANEL
        =========================================== -->
        <div class="crm-register-visual">
            <div class="crm-register-visual-inner">

                <!-- Brand -->
                <div class="crm-register-brand">
                    <div class="crm-brand-mark" aria-hidden="true">
                        <img src="<?= base_url('media/master/public/page_builder/pages/softland/assets/landingpage/image/crm_logo.png'); ?>" alt="CRM Logo" class="crm-logo-img" />
                    </div>
                   
                </div>

                <!-- Headline -->
                <h2>Create Your Free<br><span>CRM Account</span></h2>
                <p class="crm-visual-lead">Get started in minutes and experience the power of Nooryak CRM. Secure onboarding, effortless setup, and everything you need to grow your business.</p>

                <!-- Illustration -->
                <div class="crm-register-illus-wrap">
                    <div class="crm-register-image">
                        <img
                            src="<?= base_url('media/master/public/page_builder/pages/softland/assets/landingpage/image/registration_leftimage.png'); ?>"
                            alt="CRM dashboard illustration"
                            style="width:100%;display:block;object-fit:contain;"
                            onerror="this.style.display='none';document.getElementById('crm-illus-fallback').style.display='flex';">
                    </div>

                    <!-- Fallback mock dashboard (hidden when real image loads) -->
                    <div class="crm-illus-placeholder" id="crm-illus-fallback" style="display:none;">
                        <!-- floating badge -->
                        <div class="dash-float-badge">
                            <div class="dash-float-badge-top">
                                <div class="dash-float-avatars">
                                    <div class="dash-av av1"></div>
                                    <div class="dash-av av2"></div>
                                    <div class="dash-av av3"></div>
                                </div>
                                <span style="font-size:9.5px;font-weight:700;color:#0f172a;">10K+ Businesses</span>
                            </div>
                            <div>
                                <span class="dash-float-badge-stars">★★★★★</span>
                                <span class="dash-float-badge-text">Trust Nooryak CRM</span>
                            </div>
                        </div>

                        <!-- dashboard mock card -->
                        <div class="dashboard-card">
                            <div class="dash-top">
                                <div class="dash-win-btns">
                                    <span class="dash-win-btn r"></span>
                                    <span class="dash-win-btn y"></span>
                                    <span class="dash-win-btn g"></span>
                                </div>
                                <span class="dash-title-text">Dashboard</span>
                                <span class="dash-overview">Overview</span>
                            </div>
                            <div class="dash-kpis">
                                <div class="dash-kpi">
                                    <div class="dash-kpi-val">1,250</div>
                                    <div class="dash-kpi-lbl">Leads</div>
                                    <div class="dash-kpi-chg">+18.5%</div>
                                </div>
                                <div class="dash-kpi">
                                    <div class="dash-kpi-val">320</div>
                                    <div class="dash-kpi-lbl">Deals</div>
                                    <div class="dash-kpi-chg">+22.3%</div>
                                </div>
                                <div class="dash-kpi">
                                    <div class="dash-kpi-val" style="font-size:11px;">₹34.8L</div>
                                    <div class="dash-kpi-lbl">Revenue</div>
                                    <div class="dash-kpi-chg">+15.7%</div>
                                </div>
                                <div class="dash-kpi">
                                    <div class="dash-kpi-val">89%</div>
                                    <div class="dash-kpi-lbl">Rate</div>
                                    <div class="dash-kpi-chg">+4.2%</div>
                                </div>
                            </div>
                            <div class="dash-pipeline-title">Sales Pipeline</div>
                            <div class="dash-bar-row">
                                <span class="dash-bar-name">Contacted</span>
                                <div class="dash-bar-track"><div class="dash-bar-fill" style="width:80%"></div></div>
                                <span class="dash-bar-count">320</span>
                            </div>
                            <div class="dash-bar-row">
                                <span class="dash-bar-name">Qualified</span>
                                <div class="dash-bar-track"><div class="dash-bar-fill b" style="width:55%"></div></div>
                                <span class="dash-bar-count">130</span>
                            </div>
                            <div class="dash-bar-row">
                                <span class="dash-bar-name">Proposal</span>
                                <div class="dash-bar-track"><div class="dash-bar-fill y" style="width:35%"></div></div>
                                <span class="dash-bar-count">60</span>
                            </div>
                            <div class="dash-bar-row">
                                <span class="dash-bar-name">Won</span>
                                <div class="dash-bar-track"><div class="dash-bar-fill s" style="width:22%"></div></div>
                                <span class="dash-bar-count">42</span>
                            </div>
                        </div>

                        <!-- people badge -->
                        <div class="dash-people-badge">
                            <div class="dash-people-avs">
                                <div class="dash-people-av dp1"></div>
                                <div class="dash-people-av dp2"></div>
                                <div class="dash-people-av dp3"></div>
                                <div class="dash-people-av dp4"></div>
                            </div>
                            <span class="dash-people-text">10,000+ Businesses</span>
                        </div>
                    </div>
                </div>

                <!-- Perks -->
                <ul class="crm-register-perks" aria-label="Key benefits">
                    <li>
                        <div class="crm-perk-icon"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i></div>
                        5-Day Free Trial<small>Explore all features</small>
                    </li>
                    <li>
                        <div class="crm-perk-icon"><i class="fa-regular fa-credit-card" aria-hidden="true"></i></div>
                        No Credit Card Required<small>Start instantly</small>
                    </li>
                    <li>
                        <div class="crm-perk-icon"><i class="fa-regular fa-clock" aria-hidden="true"></i></div>
                        Setup in 5 Minutes<small>Quick &amp; easy</small>
                    </li>
                    <li>
                        <div class="crm-perk-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></div>
                        Secure Registration<small>Your data is safe</small>
                    </li>
                    <li class="crm-perk-ssl">
                        <div class="crm-perk-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></div>
                        SSL Secured<small>256-bit encryption</small>
                    </li>
                    <li>
                        <div class="crm-perk-icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></div>
                        Dedicated Support<small>We're here to help</small>
                    </li>
                </ul>
            </div>

            <div class="crm-register-stats" aria-hidden="true">
                <div class="crm-stat-item crm-stat-rating">
                    <div class="crm-stat-shield">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="crm-stat-rating-body">
                        <span class="crm-stat-rating-text">Rated 4.8/5 by 500+ users</span>
                        <div class="crm-stat-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <span class="crm-stat-source">on G2, Capterra &amp; more</span>
                    </div>
                </div>
                <div class="crm-stat-item">
                    <span class="crm-stat-number">10,000+</span>
                    <span class="crm-stat-label">Active Businesses</span>
                </div>
                <div class="crm-stat-item">
                    <span class="crm-stat-number">99.9%</span>
                    <span class="crm-stat-label">Uptime &amp; Reliability</span>
                </div>
                <div class="crm-stat-item">
                    <span class="crm-stat-number">ISO 27001</span>
                    <span class="crm-stat-label">Certified Security</span>
                </div>
            </div>
        </div>
        <!-- /LEFT PANEL -->

        <!-- ==========================================
             RIGHT PANEL
        =========================================== -->
        <div class="crm-register-form-col">
            <?= form_open('authentication/register', ['id' => 'register-form']); ?>
        <div class="crm-register-card">
            
            <!-- Global validation errors display -->
            <?php if(validation_errors()){ ?>
                <div class="alert alert-danger" style="margin-bottom: 20px; border-radius: 11px; font-size: 13px;">
                    <?= validation_errors(); ?>
                </div>
            <?php } ?>

            <!-- Header -->
                <div class="crm-register-form-head">
                    <span class="crm-form-badge" id="crm_reg_badge">Free Trial Registration</span>
                    <div class="crm-register-top">
                        <h1>Create Your Account</h1>
                    </div>
                    <p class="crm-subtitle">Your selected plan will be activated after registration.</p>
                </div>

                <!-- Plan summary -->
                <div class="crm-register-plan-summary">
                    <div class="crm-register-plan-summary-left">
                        <div class="crm-register-plan-summary-icon">
                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                        </div>
                        <div class="crm-register-plan-summary-content">
                            <div class="crm-register-plan-summary-label">Selected Plan</div>
                            <div class="crm-register-plan-summary-name" id="crm_reg_plan_name"><?= $crm_register_plan_name; ?></div>
                            <div class="crm-register-plan-summary-trial" id="crm_reg_plan_trial">Includes 7-day free trial</div>
                        </div>
                    </div>
                    <div class="crm-register-plan-summary-right">
                        <div class="crm-plan-users-line">
                            <i class="fa-solid fa-users" aria-hidden="true"></i>
                            <span id="crm_reg_plan_users"><?= $crm_register_plan_users; ?></span>
                        </div>
                        <div class="crm-register-plan-summary-price-wrap">
                            <span class="crm-register-plan-summary-price-amount" id="crm_reg_plan_price"><?= $crm_register_plan_price; ?></span>
                            <span class="crm-register-plan-summary-price-period" id="crm_reg_plan_period">/ User / Month</span>
                        </div>
                    </div>
                </div>

                <!-- Form fields -->
                <?php
                $fullNameValue = trim(set_value($fields['firstname']) . ' ' . set_value($fields['lastname']));
                if ($fullNameValue === '') {
                    $fullNameValue = set_value('full_name_display');
                }
                ?>

                <!-- Row 1: Full Name | Company Name -->
                <div class="crm-form-row">
                    <div class="form-group register-fullname-group">
                        <label class="control-label" for="full_name_display">Full Name</label>
                        <i class="fa-regular fa-user crm-mob-field-icon" aria-hidden="true"></i>
                        <input type="text" class="form-control" name="full_name_display" id="full_name_display"
                            value="<?= e($fullNameValue); ?>" placeholder="Full Name">
                        <input type="hidden" name="<?= e($fields['firstname']); ?>" id="<?= e($fields['firstname']); ?>"
                            value="<?= set_value($fields['firstname']); ?>">
                        <input type="hidden" name="<?= e($fields['lastname']); ?>" id="<?= e($fields['lastname']); ?>"
                            value="<?= set_value($fields['lastname']); ?>">
                        <?= form_error($fields['firstname']); ?>
                        <?= form_error($fields['lastname']); ?>
                    </div>
                    <div class="form-group register-company-group">
                        <label class="control-label" for="<?= e($fields['company']); ?>">Company Name</label>
                        <i class="fa-regular fa-building crm-mob-field-icon" aria-hidden="true"></i>
                        <input type="text" class="form-control"
                            name="<?= e($fields['company']); ?>"
                            id="<?= e($fields['company']); ?>"
                            value="<?= set_value($fields['company']); ?>"
                            placeholder="Company Name">
                        <?= form_error($fields['company']); ?>
                    </div>
                </div>

                <!-- Row 2: Work Email | SaaS Domain (Domain injected here by widget) -->
                <div class="crm-form-row">
                    <div class="form-group register-email-group">
                        <label class="control-label" for="<?= e($fields['email']); ?>">Your Email</label>
                        <i class="fa-regular fa-envelope crm-mob-field-icon" aria-hidden="true"></i>
                        <input type="email" class="form-control"
                            name="<?= e($fields['email']); ?>"
                            id="<?= e($fields['email']); ?>"
                            value="<?= set_value($fields['email']); ?>"
                            placeholder="Work Email">
                        <?= form_error($fields['email']); ?>
                    </div>
                </div>

                <!-- Row 3: Phone Number | Country -->
                <div class="crm-form-row">
                    <div class="form-group register-phone-group">
                        <label class="control-label" for="contact_phonenumber">Phone Number</label>
                        <div class="register-contact-phone-group">
                            <div class="register-contact-phone-country" title="Select country code">
                                <div class="crm-phone-visual">
                                    <img src="https://flagcdn.com/w40/in.png" id="crm_phone_flag_img" class="crm-phone-flag-img" alt="IN">
                                    <span class="crm-phone-code-text" id="crm_phone_code_text">+91</span>
                                    <i class="fa-solid fa-chevron-down crm-phone-chevron" aria-hidden="true"></i>
                                </div>
                                <!-- Visible dropdown menu -->
                                <div class="crm-phone-dropdown-menu" id="crm_phone_dropdown_menu">
                                    <!-- Items will be populated by JavaScript -->
                                </div>
                                <select class="crm-phone-flag-select" name="phone_country" id="phone_country" title="Country code" aria-label="Country dial code" style="display: none;">
                                    <option value="+91" data-flag="in" selected>India (+91)</option>
                                </select>
                            </div>
                            <i class="fa-solid fa-phone crm-mob-field-icon" aria-hidden="true"></i>
                            <input type="text" class="form-control" name="contact_phonenumber" id="contact_phonenumber" required
                                value="<?= set_value('contact_phonenumber'); ?>" placeholder="Phone Number">
                            <input type="hidden" name="phonenumber" id="phonenumber" value="<?= set_value('phonenumber'); ?>">
                            <button type="button" class="btn-phone-verify-trigger" id="btn-send-otp-inline">Verify</button>
                            <span id="phone-verified-badge" class="phone-verified-status" style="display: none;">
                                <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Verified
                            </span>
                        </div>
                        <!-- Inline OTP input container -->
                        <div id="inline-otp-container" style="display: none; margin-top: 10px;">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <div style="position: relative; flex: 1;">
                                    <i class="fa-solid fa-shield-halved" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; z-index: 3;" aria-hidden="true"></i>
                                    <input type="text" id="inline_otp_code" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6" style="padding-left: 44px !important;" autocomplete="off" aria-label="6 digit OTP" />
                                </div>
                                <button type="button" class="btn-phone-verify-trigger" id="btn-verify-otp-inline">Verify OTP</button>
                            </div>
                            <div id="inline-otp-cooldown" style="font-size: 12.5px; color: #64748b; margin-top: 6px;">
                                Resend code in <strong id="inline-cooldown-timer">60</strong>s
                            </div>
                            <button type="button" id="btn-inline-resend" class="btn-otp-resend-link" style="display: none; margin-top: 6px;">Resend OTP</button>
                            <div id="inline-otp-error" class="otp-error-alert" style="display: none; margin-top: 8px; margin-bottom: 0;"></div>
                        </div>
                        <?= form_error('contact_phonenumber'); ?>
                        <?= form_error('phonenumber'); ?>
                    </div>
                    <div class="form-group register-country-group">
                        <label class="control-label" for="country">Country</label>
                        <i class="fa-solid fa-globe crm-mob-field-icon" aria-hidden="true"></i>
                        <select
                            data-none-selected-text="Select your country"
                            data-live-search="true"
                            data-width="100%"
                            name="country"
                            class="selectpicker"
                            id="country">
                            <option value=""></option>
                            <?php foreach (get_all_countries() as $country) { ?>
                            <option
                                value="<?= e($country['country_id']); ?>"
                                <?php if (get_option('customer_default_country') == $country['country_id']) { echo ' selected'; } ?>
                                <?= set_select('country', $country['country_id']); ?>>
                                <?= e($country['short_name']); ?>
                            </option>
                            <?php } ?>
                        </select>
                        <?= form_error('country'); ?>
                    </div>
                </div>

                <!-- Row 4: Password | Confirm Password -->
                <div class="crm-form-row">
                    <div class="form-group register-password-group">
                        <label class="control-label" for="password">Password</label>
                        <i class="fa-solid fa-lock crm-mob-field-icon" aria-hidden="true"></i>
                        <div class="crm-password-field">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Create a strong password">
                            <button type="button" class="crm-password-toggle" data-toggle-password="#password" aria-label="Show password">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <?= form_error('password'); ?>
                    </div>
                    <div class="form-group register-password-repeat-group">
                        <label class="control-label" for="passwordr">Confirm Password</label>
                        <i class="fa-solid fa-lock crm-mob-field-icon" aria-hidden="true"></i>
                        <div class="crm-password-field">
                            <input type="password" class="form-control" name="passwordr" id="passwordr" placeholder="Confirm your password">
                            <button type="button" class="crm-password-toggle" data-toggle-password="#passwordr" aria-label="Show password">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <?= form_error('passwordr'); ?>
                    </div>
                </div>

                <!-- Optional extra fields (contact title, VAT, city, zip, state, custom fields) -->
                <?php
                $showExtraContact  = $requiredFields['contact']['contact_title']['is_required'] || set_value('title') != '';
                $showVat           = false; // Forced hidden as per user request
                $showExtraCompany  = $requiredFields['company']['company_city']['is_required']
                                  || $requiredFields['company']['company_zip']['is_required']
                                  || $requiredFields['company']['company_state']['is_required']
                                  || set_value('city') != ''
                                  || set_value('zip') != ''
                                  || set_value('state') != '';
                $contactCustomFieldsHtml  = render_custom_fields('contacts',  '', ['show_on_client_portal' => 1]);
                $customerCustomFieldsHtml = render_custom_fields('customers', '', ['show_on_client_portal' => 1]);
                $hasCustomFields = !empty(trim($contactCustomFieldsHtml)) || !empty(trim($customerCustomFieldsHtml));
                ?>

                <?php if ($showExtraContact || $showVat || $showExtraCompany || $hasCustomFields) : ?>
                <div class="crm-register-extra-fields">
                    <div class="crm-form-row">
                        <?php if ($showExtraContact) : ?>
                        <div class="form-group register-position-group">
                            <label class="control-label" for="title">
                                <?php if ($requiredFields['contact']['contact_title']['is_required']) : ?><span class="text-danger">*</span><?php endif; ?>
                                <?= _l('contact_position'); ?>
                            </label>
                            <input type="text" class="form-control" name="title" id="title" value="<?= set_value('title'); ?>">
                            <?= form_error('title'); ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($showVat) : ?>
                        <div class="form-group register-vat-group" style="grid-column: 1 / -1;">
                            <label class="control-label" for="vat">
                                <?php if ($requiredFields['company']['company_vat']['is_required']) : ?><span class="text-danger">*</span><?php endif; ?>
                                <?= _l('clients_vat'); ?>
                            </label>
                            <input type="text" class="form-control" name="vat" id="vat" value="<?= set_value('vat'); ?>">
                            <?= form_error('vat'); ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($showExtraCompany) : ?>
                        <div class="form-group register-city-group">
                            <label class="control-label" for="city">
                                <?php if ($requiredFields['company']['company_city']['is_required']) : ?><span class="text-danger">*</span><?php endif; ?>
                                <?= _l('clients_city'); ?>
                            </label>
                            <input type="text" class="form-control" name="city" id="city" value="<?= set_value('city'); ?>">
                            <?= form_error('city'); ?>
                        </div>
                        <div class="form-group register-zip-group">
                            <label class="control-label" for="zip">
                                <?php if ($requiredFields['company']['company_zip']['is_required']) : ?><span class="text-danger">*</span><?php endif; ?>
                                <?= _l('clients_zip'); ?>
                            </label>
                            <input type="text" class="form-control" name="zip" id="zip" value="<?= set_value('zip'); ?>">
                            <?= form_error('zip'); ?>
                        </div>
                        <div class="form-group register-state-group">
                            <label class="control-label" for="state">
                                <?php if ($requiredFields['company']['company_state']['is_required']) : ?><span class="text-danger">*</span><?php endif; ?>
                                <?= _l('clients_state'); ?>
                            </label>
                            <input type="text" class="form-control" name="state" id="state" value="<?= set_value('state'); ?>">
                            <?= form_error('state'); ?>
                        </div>
                        <?php endif; ?>

                        <div class="register-contact-custom-fields" style="grid-column: 1 / -1;"><?= $contactCustomFieldsHtml; ?></div>
                        <div class="register-company-custom-fields" style="grid-column: 1 / -1;"><?= $customerCustomFieldsHtml; ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- GDPR Terms -->
                <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions') == 1) : ?>
                <div class="crm-terms-row">
                    <input type="checkbox" class="crm-checkbox" name="accept_terms_and_conditions" id="accept_terms_and_conditions"
                        <?= set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                    <label class="crm-terms-label" for="accept_terms_and_conditions">
                        I agree to Nooryak CRM's <a href="<?= terms_url(); ?>" target="_blank" rel="noopener noreferrer">Terms of Service</a> and <a href="<?= privacy_policy_url(); ?>" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
                    </label>
                </div>
                <?= form_error('accept_terms_and_conditions'); ?>
                <?php else : ?>
                <!-- Always show terms row -->
                <div class="crm-terms-row">
                    <input type="checkbox" class="crm-checkbox" name="accept_terms_and_conditions" id="accept_terms_and_conditions">
                    <label class="crm-terms-label" for="accept_terms_and_conditions">
                        I agree to Nooryak CRM's <a href="<?= terms_url(); ?>" target="_blank" rel="noopener noreferrer">Terms of Service</a> and <a href="<?= privacy_policy_url(); ?>" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
                    </label>
                </div>
                <?php endif; ?>

                <!-- Honeypot -->
                <?php if ($honeypot) : ?>
                <div class="honey-element" aria-hidden="true">
                    <label for="firstname">First Name</label>
                    <input autocomplete="new-password" type="text" id="firstname" name="firstname" placeholder="Your first name here" tabindex="-1">
                    <label for="lastname">Last Name</label>
                    <input autocomplete="new-password" type="text" id="lastname" name="lastname" placeholder="Your last name here" tabindex="-1">
                    <label for="email">Email</label>
                    <input autocomplete="new-password" type="email" id="email" name="email" placeholder="Your e-mail here" tabindex="-1">
                    <label for="company">Company</label>
                    <input autocomplete="new-password" type="text" id="company" name="company" placeholder="Your company here" tabindex="-1">
                </div>
                <?php endif; ?>

                <!-- reCAPTCHA -->
                <?php if (show_recaptcha_in_customers_area()) : ?>
                <div class="register-recaptcha" style="margin-top:10px;">
                    <div class="g-recaptcha" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
                    <?= form_error('g-recaptcha-response'); ?>
                </div>
                <?php endif; ?>

                <!-- Submit -->
                <div class="crm-register-actions">
                    <button type="submit" autocomplete="off"
                        data-loading-text="<?= _l('wait_text'); ?>"
                        class="btn-crm-submit" id="btn-register-submit" disabled>
                        <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                        Create Your Account
                    </button>
                </div>

                <!-- Sign in link -->
                <div class="crm-register-login">
                    Already have an account?
                    <a href="<?= site_url('authentication'); ?>">Sign in</a>
                </div>

                <!-- Security badges -->
                <div class="crm-register-secure" aria-hidden="true">
                    <div class="crm-secure-item">
                        <div class="crm-secure-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <strong>SSL Secured</strong>
                        <em>256-bit encryption</em>
                    </div>
                    <div class="crm-secure-item">
                        <div class="crm-secure-icon"><i class="fa-solid fa-bolt"></i></div>
                        <strong>Instant Setup</strong>
                        <em>Get started right away</em>
                    </div>
                    <div class="crm-secure-item">
                        <div class="crm-secure-icon"><i class="fa-solid fa-headset"></i></div>
                        <strong>Dedicated Support</strong>
                        <em>We're here to help</em>
                    </div>
                </div>

                <!-- Fallback hidden fields to prevent controller errors -->
                <input type="hidden" name="address" value="">
                <input type="hidden" name="city" value="">
                <input type="hidden" name="state" value="">
                <input type="hidden" name="zip" value="">
                <input type="hidden" name="website" value="">
                <input type="hidden" name="title" value="">
                <input type="hidden" name="vat" value="">

            </div><!-- /crm-register-card -->
            <?= form_close(); ?>
        </div>
        <!-- /RIGHT PANEL -->

    </div><!-- /crm-register-shell -->

    <!-- Footer bar -->
    <div class="crm-register-page-foot" aria-hidden="true">
        <span><i class="fa-solid fa-building"></i> Trusted by growing businesses</span>
        <span><i class="fa-solid fa-shield-halved"></i> Secure onboarding</span>
        <span><i class="fa-solid fa-lock"></i> Your data is always protected</span>
    </div>
</div><!-- /crm-register-wrap -->

<script>
(function () {
    'use strict';

    /* ---- Split full name into first/last ---- */
    function splitFullName() {
        var fullNameInput   = document.getElementById('full_name_display');
        var firstNameInput  = document.getElementById('<?= e($fields['firstname']); ?>');
        var lastNameInput   = document.getElementById('<?= e($fields['lastname']); ?>');

        if (!fullNameInput || !firstNameInput || !lastNameInput) { return; }

        var clean = (fullNameInput.value || '').trim().replace(/\s+/g, ' ');
        if (clean.length === 0) {
            firstNameInput.value = '';
            lastNameInput.value  = '';
            return;
        }
        var parts = clean.split(' ');
        firstNameInput.value = parts.shift();
        lastNameInput.value  = parts.join(' ') || '-';
    }

    /* ---- Password visibility toggles ---- */
    function bindPasswordToggles() {
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = document.querySelector(btn.getAttribute('data-toggle-password'));
                if (!target) { return; }
                var isPwd = target.type === 'password';
                target.type = isPwd ? 'text' : 'password';
                var icon = btn.querySelector('i');
                if (icon) { icon.className = isPwd ? 'fa fa-eye-slash' : 'fa fa-eye'; }
                btn.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
            });
        });
    }

    /* ---- Phone country flag & dial code ---- */
    var PHONE_COUNTRIES = [
        { code: '+1', flag: 'us', name: 'United States' },
        { code: '+1', flag: 'ca', name: 'Canada' },
        { code: '+1', flag: 'bs', name: 'Bahamas' },
        { code: '+1', flag: 'bb', name: 'Barbados' },
        { code: '+1', flag: 'bm', name: 'Bermuda' },
        { code: '+1', flag: 'ky', name: 'Cayman Islands' },
        { code: '+1', flag: 'do', name: 'Dominican Republic' },
        { code: '+1', flag: 'gy', name: 'Guyana' },
        { code: '+1', flag: 'jm', name: 'Jamaica' },
        { code: '+1', flag: 'tt', name: 'Trinidad and Tobago' },
        { code: '+7', flag: 'ru', name: 'Russia' },
        { code: '+7', flag: 'kz', name: 'Kazakhstan' },
        { code: '+20', flag: 'eg', name: 'Egypt' },
        { code: '+27', flag: 'za', name: 'South Africa' },
        { code: '+30', flag: 'gr', name: 'Greece' },
        { code: '+31', flag: 'nl', name: 'Netherlands' },
        { code: '+32', flag: 'be', name: 'Belgium' },
        { code: '+33', flag: 'fr', name: 'France' },
        { code: '+34', flag: 'es', name: 'Spain' },
        { code: '+36', flag: 'hu', name: 'Hungary' },
        { code: '+39', flag: 'it', name: 'Italy' },
        { code: '+40', flag: 'ro', name: 'Romania' },
        { code: '+41', flag: 'ch', name: 'Switzerland' },
        { code: '+43', flag: 'at', name: 'Austria' },
        { code: '+44', flag: 'gb', name: 'United Kingdom' },
        { code: '+45', flag: 'dk', name: 'Denmark' },
        { code: '+46', flag: 'se', name: 'Sweden' },
        { code: '+47', flag: 'no', name: 'Norway' },
        { code: '+48', flag: 'pl', name: 'Poland' },
        { code: '+49', flag: 'de', name: 'Germany' },
        { code: '+51', flag: 'pe', name: 'Peru' },
        { code: '+52', flag: 'mx', name: 'Mexico' },
        { code: '+53', flag: 'cu', name: 'Cuba' },
        { code: '+54', flag: 'ar', name: 'Argentina' },
        { code: '+55', flag: 'br', name: 'Brazil' },
        { code: '+56', flag: 'cl', name: 'Chile' },
        { code: '+57', flag: 'co', name: 'Colombia' },
        { code: '+58', flag: 've', name: 'Venezuela' },
        { code: '+60', flag: 'my', name: 'Malaysia' },
        { code: '+61', flag: 'au', name: 'Australia' },
        { code: '+62', flag: 'id', name: 'Indonesia' },
        { code: '+63', flag: 'ph', name: 'Philippines' },
        { code: '+64', flag: 'nz', name: 'New Zealand' },
        { code: '+65', flag: 'sg', name: 'Singapore' },
        { code: '+66', flag: 'th', name: 'Thailand' },
        { code: '+81', flag: 'jp', name: 'Japan' },
        { code: '+82', flag: 'kr', name: 'South Korea' },
        { code: '+84', flag: 'vn', name: 'Vietnam' },
        { code: '+86', flag: 'cn', name: 'China' },
        { code: '+90', flag: 'tr', name: 'Turkey' },
        { code: '+91', flag: 'in', name: 'India' },
        { code: '+92', flag: 'pk', name: 'Pakistan' },
        { code: '+93', flag: 'af', name: 'Afghanistan' },
        { code: '+94', flag: 'lk', name: 'Sri Lanka' },
        { code: '+95', flag: 'mm', name: 'Myanmar' },
        { code: '+98', flag: 'ir', name: 'Iran' },
        { code: '+212', flag: 'ma', name: 'Morocco' },
        { code: '+216', flag: 'tn', name: 'Tunisia' },
        { code: '+220', flag: 'gm', name: 'Gambia' },
        { code: '+221', flag: 'sn', name: 'Senegal' },
        { code: '+222', flag: 'mr', name: 'Mauritania' },
        { code: '+223', flag: 'ml', name: 'Mali' },
        { code: '+224', flag: 'gn', name: 'Guinea' },
        { code: '+225', flag: 'ci', name: 'Ivory Coast' },
        { code: '+226', flag: 'bf', name: 'Burkina Faso' },
        { code: '+227', flag: 'ne', name: 'Niger' },
        { code: '+228', flag: 'tg', name: 'Togo' },
        { code: '+229', flag: 'bj', name: 'Benin' },
        { code: '+230', flag: 'mu', name: 'Mauritius' },
        { code: '+231', flag: 'lr', name: 'Liberia' },
        { code: '+232', flag: 'sl', name: 'Sierra Leone' },
        { code: '+233', flag: 'gh', name: 'Ghana' },
        { code: '+234', flag: 'ng', name: 'Nigeria' },
        { code: '+235', flag: 'td', name: 'Chad' },
        { code: '+236', flag: 'cf', name: 'Central African Republic' },
        { code: '+237', flag: 'cm', name: 'Cameroon' },
        { code: '+238', flag: 'cv', name: 'Cape Verde' },
        { code: '+239', flag: 'st', name: 'São Tomé and Príncipe' },
        { code: '+240', flag: 'gq', name: 'Equatorial Guinea' },
        { code: '+241', flag: 'ga', name: 'Gabon' },
        { code: '+242', flag: 'cg', name: 'Republic of the Congo' },
        { code: '+243', flag: 'cd', name: 'Democratic Republic of the Congo' },
        { code: '+244', flag: 'ao', name: 'Angola' },
        { code: '+245', flag: 'gw', name: 'Guinea-Bissau' },
        { code: '+246', flag: 'io', name: 'British Indian Ocean Territory' },
        { code: '+248', flag: 'sc', name: 'Seychelles' },
        { code: '+249', flag: 'sd', name: 'Sudan' },
        { code: '+250', flag: 'rw', name: 'Rwanda' },
        { code: '+251', flag: 'et', name: 'Ethiopia' },
        { code: '+252', flag: 'so', name: 'Somalia' },
        { code: '+253', flag: 'dj', name: 'Djibouti' },
        { code: '+254', flag: 'ke', name: 'Kenya' },
        { code: '+255', flag: 'tz', name: 'Tanzania' },
        { code: '+256', flag: 'ug', name: 'Uganda' },
        { code: '+257', flag: 'bi', name: 'Burundi' },
        { code: '+258', flag: 'mz', name: 'Mozambique' },
        { code: '+260', flag: 'zm', name: 'Zambia' },
        { code: '+261', flag: 'mg', name: 'Madagascar' },
        { code: '+262', flag: 're', name: 'Réunion' },
        { code: '+263', flag: 'zw', name: 'Zimbabwe' },
        { code: '+264', flag: 'na', name: 'Namibia' },
        { code: '+265', flag: 'mw', name: 'Malawi' },
        { code: '+266', flag: 'ls', name: 'Lesotho' },
        { code: '+267', flag: 'bw', name: 'Botswana' },
        { code: '+268', flag: 'sz', name: 'Eswatini' },
        { code: '+290', flag: 'sh', name: 'Saint Helena' },
        { code: '+291', flag: 'er', name: 'Eritrea' },
        { code: '+297', flag: 'aw', name: 'Aruba' },
        { code: '+298', flag: 'fo', name: 'Faroe Islands' },
        { code: '+299', flag: 'gl', name: 'Greenland' },
        { code: '+350', flag: 'gi', name: 'Gibraltar' },
        { code: '+351', flag: 'pt', name: 'Portugal' },
        { code: '+352', flag: 'lu', name: 'Luxembourg' },
        { code: '+353', flag: 'ie', name: 'Ireland' },
        { code: '+354', flag: 'is', name: 'Iceland' },
        { code: '+355', flag: 'al', name: 'Albania' },
        { code: '+356', flag: 'mt', name: 'Malta' },
        { code: '+357', flag: 'cy', name: 'Cyprus' },
        { code: '+358', flag: 'fi', name: 'Finland' },
        { code: '+359', flag: 'bg', name: 'Bulgaria' },
        { code: '+370', flag: 'lt', name: 'Lithuania' },
        { code: '+371', flag: 'lv', name: 'Latvia' },
        { code: '+372', flag: 'ee', name: 'Estonia' },
        { code: '+373', flag: 'md', name: 'Moldova' },
        { code: '+374', flag: 'am', name: 'Armenia' },
        { code: '+375', flag: 'by', name: 'Belarus' },
        { code: '+376', flag: 'ad', name: 'Andorra' },
        { code: '+377', flag: 'mc', name: 'Monaco' },
        { code: '+378', flag: 'sm', name: 'San Marino' },
        { code: '+380', flag: 'ua', name: 'Ukraine' },
        { code: '+381', flag: 'rs', name: 'Serbia' },
        { code: '+382', flag: 'me', name: 'Montenegro' },
        { code: '+383', flag: 'xk', name: 'Kosovo' },
        { code: '+385', flag: 'hr', name: 'Croatia' },
        { code: '+386', flag: 'si', name: 'Slovenia' },
        { code: '+387', flag: 'ba', name: 'Bosnia and Herzegovina' },
        { code: '+389', flag: 'mk', name: 'Macedonia' },
        { code: '+420', flag: 'cz', name: 'Czech Republic' },
        { code: '+421', flag: 'sk', name: 'Slovakia' },
        { code: '+423', flag: 'li', name: 'Liechtenstein' },
        { code: '+500', flag: 'fk', name: 'Falkland Islands' },
        { code: '+501', flag: 'bz', name: 'Belize' },
        { code: '+502', flag: 'gt', name: 'Guatemala' },
        { code: '+503', flag: 'sv', name: 'El Salvador' },
        { code: '+504', flag: 'hn', name: 'Honduras' },
        { code: '+505', flag: 'ni', name: 'Nicaragua' },
        { code: '+506', flag: 'cr', name: 'Costa Rica' },
        { code: '+507', flag: 'pa', name: 'Panama' },
        { code: '+508', flag: 'pm', name: 'Saint Pierre and Miquelon' },
        { code: '+509', flag: 'ht', name: 'Haiti' },
        { code: '+590', flag: 'gp', name: 'Guadeloupe' },
        { code: '+591', flag: 'bo', name: 'Bolivia' },
        { code: '+592', flag: 'gy', name: 'Guyana' },
        { code: '+593', flag: 'ec', name: 'Ecuador' },
        { code: '+594', flag: 'gf', name: 'French Guiana' },
        { code: '+595', flag: 'py', name: 'Paraguay' },
        { code: '+596', flag: 'mq', name: 'Martinique' },
        { code: '+597', flag: 'sr', name: 'Suriname' },
        { code: '+598', flag: 'uy', name: 'Uruguay' },
        { code: '+599', flag: 'cw', name: 'Curaçao' },
        { code: '+670', flag: 'tl', name: 'East Timor' },
        { code: '+672', flag: 'nf', name: 'Norfolk Island' },
        { code: '+673', flag: 'bn', name: 'Brunei' },
        { code: '+674', flag: 'nr', name: 'Nauru' },
        { code: '+675', flag: 'pg', name: 'Papua New Guinea' },
        { code: '+676', flag: 'to', name: 'Tonga' },
        { code: '+677', flag: 'sb', name: 'Solomon Islands' },
        { code: '+678', flag: 'vu', name: 'Vanuatu' },
        { code: '+679', flag: 'fj', name: 'Fiji' },
        { code: '+680', flag: 'pw', name: 'Palau' },
        { code: '+681', flag: 'wf', name: 'Wallis and Futuna' },
        { code: '+682', flag: 'ck', name: 'Cook Islands' },
        { code: '+683', flag: 'nu', name: 'Niue' },
        { code: '+684', flag: 'as', name: 'American Samoa' },
        { code: '+685', flag: 'ws', name: 'Samoa' },
        { code: '+686', flag: 'ki', name: 'Kiribati' },
        { code: '+687', flag: 'nc', name: 'New Caledonia' },
        { code: '+688', flag: 'tv', name: 'Tuvalu' },
        { code: '+689', flag: 'pf', name: 'French Polynesia' },
        { code: '+690', flag: 'tk', name: 'Tokelau' },
        { code: '+691', flag: 'fm', name: 'Micronesia' },
        { code: '+692', flag: 'mh', name: 'Marshall Islands' },
        { code: '+850', flag: 'kp', name: 'North Korea' },
        { code: '+852', flag: 'hk', name: 'Hong Kong' },
        { code: '+853', flag: 'mo', name: 'Macau' },
        { code: '+855', flag: 'kh', name: 'Cambodia' },
        { code: '+856', flag: 'la', name: 'Laos' },
        { code: '+880', flag: 'bd', name: 'Bangladesh' },
        { code: '+886', flag: 'tw', name: 'Taiwan' },
        { code: '+960', flag: 'mv', name: 'Maldives' },
        { code: '+961', flag: 'lb', name: 'Lebanon' },
        { code: '+962', flag: 'jo', name: 'Jordan' },
        { code: '+963', flag: 'sy', name: 'Syria' },
        { code: '+964', flag: 'iq', name: 'Iraq' },
        { code: '+965', flag: 'kw', name: 'Kuwait' },
        { code: '+966', flag: 'sa', name: 'Saudi Arabia' },
        { code: '+967', flag: 'ye', name: 'Yemen' },
        { code: '+968', flag: 'om', name: 'Oman' },
        { code: '+970', flag: 'ps', name: 'Palestine' },
        { code: '+971', flag: 'ae', name: 'UAE' },
        { code: '+972', flag: 'il', name: 'Israel' },
        { code: '+973', flag: 'bh', name: 'Bahrain' },
        { code: '+974', flag: 'qa', name: 'Qatar' },
        { code: '+975', flag: 'bt', name: 'Bhutan' },
        { code: '+976', flag: 'mn', name: 'Mongolia' },
        { code: '+977', flag: 'np', name: 'Nepal' },
    ];

    function getPhoneDialCode() {
        var sel = document.getElementById('phone_country');
        if (!sel) { return '+91'; }
        return sel.value || '+91';
    }

    function getPhoneCountryFlag() {
        var sel = document.getElementById('phone_country');
        if (!sel) { return 'in'; }
        var opt = sel.options[sel.selectedIndex];
        return (opt && opt.getAttribute('data-flag')) || sel.getAttribute('data-flag') || 'in';
    }

    function ensurePhoneCountryOption(code, flag) {
        var sel = document.getElementById('phone_country');
        if (!sel) { return; }

        var option = Array.prototype.slice.call(sel.options).find(function (opt) {
            return opt.value === code;
        });

        if (!option) {
            option = document.createElement('option');
            option.value = code;
            option.text = code;
            option.setAttribute('data-flag', flag);
            sel.appendChild(option);
        } else {
            option.setAttribute('data-flag', flag);
        }

        sel.value = code;
    }

    function updatePhoneFlag() {
        var flagImg  = document.getElementById('crm_phone_flag_img');
        var codeText = document.getElementById('crm_phone_code_text');
        var dialCode = getPhoneDialCode();
        var flagCode = getPhoneCountryFlag();
        if (flagImg)  { flagImg.src = 'https://flagcdn.com/w40/' + flagCode + '.png'; flagImg.alt = flagCode.toUpperCase(); }
        if (codeText) { codeText.textContent = dialCode; }
    }

    function renderPhoneDropdown() {
        var dropdown = document.getElementById('crm_phone_dropdown_menu');
        if (!dropdown) return;
        
        dropdown.innerHTML = '';
        var currentCode = getPhoneDialCode();
        
        PHONE_COUNTRIES.forEach(function(country) {
            var item = document.createElement('div');
            item.className = 'crm-phone-dropdown-item';
            if (country.code === currentCode && country.flag === getPhoneCountryFlag()) {
                item.classList.add('active');
            }
            
            var flagImg = document.createElement('img');
            flagImg.src = 'https://flagcdn.com/w40/' + country.flag + '.png';
            flagImg.className = 'crm-phone-dropdown-flag';
            flagImg.alt = country.flag.toUpperCase();
            
            var codeSpan = document.createElement('span');
            codeSpan.className = 'crm-phone-dropdown-code';
            codeSpan.textContent = country.code;
            
            var nameSpan = document.createElement('span');
            nameSpan.className = 'crm-phone-dropdown-name';
            nameSpan.textContent = country.name;
            
            item.appendChild(flagImg);
            item.appendChild(codeSpan);
            item.appendChild(nameSpan);
            
            item.addEventListener('click', function() {
                selectPhoneCountry(country.code, country.flag);
            });
            
            dropdown.appendChild(item);
        });
    }

    function selectPhoneCountry(code, flag) {
        var sel = document.getElementById('phone_country');
        if (sel) {
            ensurePhoneCountryOption(code, flag);
            sel.setAttribute('data-flag', flag);
        }
        updatePhoneFlag();
        syncPhoneInputs();
        closePhoneDropdown();
        renderPhoneDropdown();
    }

    function togglePhoneDropdown() {
        var dropdown = document.getElementById('crm_phone_dropdown_menu');
        var phoneCountryBtn = document.querySelector('.register-contact-phone-country');
        if (!dropdown) return;
        dropdown.classList.toggle('active');
        if (phoneCountryBtn) {
            phoneCountryBtn.classList.toggle('open');
        }
    }

    function closePhoneDropdown() {
        var dropdown = document.getElementById('crm_phone_dropdown_menu');
        var phoneCountryBtn = document.querySelector('.register-contact-phone-country');
        if (dropdown) {
            dropdown.classList.remove('active');
            if (phoneCountryBtn) phoneCountryBtn.classList.remove('open');
        }
    }

    function syncPhoneInputs() {
        var contactPhoneInput = document.getElementById('contact_phonenumber');
        var hiddenPhoneInput  = document.getElementById('phonenumber');
        if (!contactPhoneInput || !hiddenPhoneInput) { return; }

        var rawValue = (contactPhoneInput.value || '').trim();
        if (rawValue.length === 0) {
            hiddenPhoneInput.value = '';
            return;
        }

        var dialCode = getPhoneDialCode();
        var normalized = rawValue.replace(/[^\d+]/g, '');

        if (normalized.indexOf('+') === 0) {
            hiddenPhoneInput.value = normalized;
        } else {
            hiddenPhoneInput.value = dialCode + normalized.replace(/^\+/, '');
        }
    }

    /* ---- Bootstrap selectpicker for Country ---- */
    function initCountrySelect() {
        if (typeof jQuery === 'undefined' || !jQuery.fn.selectpicker) { return; }
        var $c = jQuery('#country');
        if (!$c.length) { return; }
        if (!$c.parent().hasClass('bootstrap-select')) {
            $c.selectpicker({ width: '100%' });
        } else {
            $c.selectpicker('refresh');
        }
        $c.closest('.register-country-group').find('.bootstrap-select, .dropdown-toggle').css({
            width: '100%', maxWidth: '100%', display: 'block'
        });
    }

    /* ---- Update domain suffix dynamically ---- */
    function updateDomainSuffix() {
        var suffixEl = document.getElementById('crm-domain-suffix-text');
        if (!suffixEl) { return; }
        
        var domain = window.location.hostname;
        if (domain === 'localhost' || domain === '127.0.0.1') {
            domain = 'localhost';
        } else {
            var parts = domain.split('.');
            if (parts.length > 2) {
                domain = parts.slice(-2).join('.');
            }
        }
        suffixEl.textContent = '.' + domain;
    }

    /* ---- Init ---- */
    var form                 = document.getElementById('register-form');
    var fullNameInput        = document.getElementById('full_name_display');
    var contactPhoneInput    = document.getElementById('contact_phonenumber');
    var phoneCountry         = document.getElementById('phone_country');
    var phoneVisual          = document.querySelector('.crm-phone-visual');
    var phoneCountryBtn      = document.querySelector('.register-contact-phone-country');

    if (fullNameInput) {
        fullNameInput.addEventListener('blur', splitFullName);
    }

    if (contactPhoneInput) {
        contactPhoneInput.addEventListener('input', function () {
            splitFullName();
            syncPhoneInputs();
            
            // Reset verification state if they edit the phone number
            otpVerified = false;
            if (btnSendOtp) btnSendOtp.style.display = 'inline-block';
            if (phoneVerifiedBadge) phoneVerifiedBadge.style.display = 'none';
            if (btnRegisterSubmit) btnRegisterSubmit.disabled = true;
            if (inlineOtpContainer) inlineOtpContainer.style.display = 'none';
            
            var errorEl = document.getElementById('inline-otp-error');
            if (errorEl) errorEl.style.display = 'none';
        });
    }

    /* ---- OTP Verification Flow ---- */
    var otpVerified = <?= ($this->session->userdata('mobile_otp_verified') === true) ? 'true' : 'false'; ?>;
    var otpCooldownTimer = null;
    var siteUrl = '<?= site_url(); ?>';
    var isSendingOtp = false;
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    var btnSendOtp = document.getElementById('btn-send-otp-inline');
    var btnVerifyOtp = document.getElementById('btn-verify-otp-inline');
    var btnRegisterSubmit = document.getElementById('btn-register-submit');
    var phoneVerifiedBadge = document.getElementById('phone-verified-badge');
    var inlineOtpContainer = document.getElementById('inline-otp-container');

    function checkInitialVerificationState() {
        if (otpVerified) {
            if (btnSendOtp) btnSendOtp.style.display = 'none';
            if (phoneVerifiedBadge) phoneVerifiedBadge.style.display = 'inline-flex';
            if (btnRegisterSubmit) btnRegisterSubmit.disabled = false;
        }
    }
    checkInitialVerificationState();

    if (btnSendOtp) {
        btnSendOtp.addEventListener('click', function(e) {
            e.preventDefault();
            sendOtpCodeInline();
        });
    }

    function sendOtpCodeInline() {
        if (isSendingOtp) return;
        var phoneInput = document.getElementById('phonenumber');
        var phoneVal = phoneInput ? phoneInput.value : '';
        var errorEl = document.getElementById('inline-otp-error');
        
        if (!phoneVal || phoneVal.replace(/[^\d]/g, '').length < 7) {
            alert('Please enter a valid phone number first.');
            return;
        }

        isSendingOtp = true;
        btnSendOtp.disabled = true;
        btnSendOtp.textContent = 'Sending...';
        if (errorEl) errorEl.style.display = 'none';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', siteUrl + 'authentication/send_otp', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            isSendingOtp = false;
            btnSendOtp.disabled = false;
            btnSendOtp.textContent = 'Verify';
            try {
                var res = JSON.parse(xhr.responseText);
                if (res.success) {
                    if (inlineOtpContainer) inlineOtpContainer.style.display = 'block';
                    var codeInput = document.getElementById('inline_otp_code');
                    if (codeInput) {
                        codeInput.focus();
                        codeInput.value = '';
                    }
                    startOtpCooldownInline();
                } else {
                    if (errorEl) {
                        errorEl.textContent = res.message || 'Failed to send OTP.';
                        errorEl.style.display = 'block';
                    } else {
                        alert(res.message || 'Failed to send OTP. Please try again.');
                    }
                }
            } catch (e) {
                console.error(e);
                alert('An error occurred. Please try again.');
            }
        };
        xhr.onerror = function() {
            isSendingOtp = false;
            btnSendOtp.disabled = false;
            btnSendOtp.textContent = 'Verify';
            alert('An error occurred. Please try again.');
        };

        var params = 'phone=' + encodeURIComponent(phoneVal);
        if (csrfName && csrfHash) {
            params += '&' + csrfName + '=' + encodeURIComponent(csrfHash);
        }
        xhr.send(params);
    }

    function startOtpCooldownInline() {
        var timerRow = document.getElementById('inline-otp-cooldown');
        var resendBtn = document.getElementById('btn-inline-resend');
        var cooldownEl = document.getElementById('inline-cooldown-timer');
        
        if (timerRow) timerRow.style.display = 'block';
        if (resendBtn) resendBtn.style.display = 'none';
        
        var seconds = 60;
        if (cooldownEl) cooldownEl.textContent = seconds;
        
        if (otpCooldownTimer) clearInterval(otpCooldownTimer);
        otpCooldownTimer = setInterval(function() {
            seconds--;
            if (cooldownEl) cooldownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(otpCooldownTimer);
                if (timerRow) timerRow.style.display = 'none';
                if (resendBtn) resendBtn.style.display = 'inline-block';
            }
        }, 1000);
    }

    var resendBtn = document.getElementById('btn-inline-resend');
    if (resendBtn) {
        resendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sendOtpCodeInline();
        });
    }

    if (btnVerifyOtp) {
        btnVerifyOtp.addEventListener('click', function(e) {
            e.preventDefault();
            var codeInput = document.getElementById('inline_otp_code');
            var code = codeInput ? codeInput.value.trim() : '';
            var phoneVal = document.getElementById('phonenumber').value;
            var errorEl = document.getElementById('inline-otp-error');
            
            if (code.length !== 6) {
                if (errorEl) {
                    errorEl.textContent = 'Please enter all 6 digits of the OTP code.';
                    errorEl.style.display = 'block';
                }
                return;
            }

            btnVerifyOtp.disabled = true;
            btnVerifyOtp.textContent = 'Verifying...';
            if (errorEl) errorEl.style.display = 'none';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', siteUrl + 'authentication/verify_otp', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                btnVerifyOtp.disabled = false;
                btnVerifyOtp.textContent = 'Verify OTP';
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        otpVerified = true;
                        if (inlineOtpContainer) inlineOtpContainer.style.display = 'none';
                        if (btnSendOtp) btnSendOtp.style.display = 'none';
                        if (phoneVerifiedBadge) phoneVerifiedBadge.style.display = 'inline-flex';
                        if (btnRegisterSubmit) btnRegisterSubmit.disabled = false;
                    } else {
                        if (errorEl) {
                            errorEl.textContent = res.message || 'Invalid OTP code. Please try again.';
                            errorEl.style.display = 'block';
                        }
                    }
                } catch (e) {
                    console.error(e);
                    if (errorEl) {
                        errorEl.textContent = 'An error occurred. Please try again.';
                        errorEl.style.display = 'block';
                    }
                }
            };
            xhr.onerror = function() {
                btnVerifyOtp.disabled = false;
                btnVerifyOtp.textContent = 'Verify OTP';
                if (errorEl) {
                    errorEl.textContent = 'An error occurred. Please try again.';
                    errorEl.style.display = 'block';
                }
            };

            var params = 'phone=' + encodeURIComponent(phoneVal) + '&otp=' + encodeURIComponent(code);
            if (csrfName && csrfHash) {
                params += '&' + csrfName + '=' + encodeURIComponent(csrfHash);
            }
            xhr.send(params);
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            splitFullName();
            syncPhoneInputs();
            if (!otpVerified) {
                e.preventDefault();
                alert('Please verify your mobile number first.');
            }
        });
    }

    if (phoneCountry) {
        phoneCountry.addEventListener('change', function () {
            updatePhoneFlag();
            syncPhoneInputs();
        });
    }

    // Toggle dropdown on visual click
    if (phoneVisual) {
        phoneVisual.addEventListener('click', function(e) {
            e.stopPropagation();
            togglePhoneDropdown();
        });
    }

    // Also add click handler to the phone country button
    if (phoneCountryBtn) {
        phoneCountryBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            togglePhoneDropdown();
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        var dropdown = document.getElementById('crm_phone_dropdown_menu');
        if (dropdown && phoneCountryBtn) {
            if (!phoneCountryBtn.contains(e.target)) {
                closePhoneDropdown();
            }
        }
    });

    updatePhoneFlag();
    renderPhoneDropdown();
    syncPhoneInputs();
    bindPasswordToggles();
    splitFullName();
    initCountrySelect();
    updateDomainSuffix();

    /* ---- Sync Selection from LocalStorage (Landing Page) ---- */
    function syncSelectionData() {
        var raw = localStorage.getItem('nooryak_selected_plan');
        if (!raw) return;
        
        try {
            var data = JSON.parse(raw);
            
            /* Only use if it was saved in the last 10 minutes */
            if (Date.now() - data.timestamp > 600000) return;

            var badge = document.getElementById('crm_reg_badge');
            var nameEl = document.getElementById('crm_reg_plan_name');
            var priceEl = document.getElementById('crm_reg_plan_price');
            var usersEl = document.getElementById('crm_reg_plan_users');
            var trialEl = document.getElementById('crm_reg_plan_trial');
            var periodEl = document.getElementById('crm_reg_plan_period');

            if (nameEl) nameEl.textContent = data.name;
            if (priceEl) priceEl.textContent = data.price;
            if (usersEl) usersEl.textContent = data.teamSize + (parseInt(data.teamSize) === 1 ? ' User' : ' Users');
            
            /* Handle badge and price suffix visibility */
            if (badge) {
                badge.style.display = data.isFree ? 'inline-flex' : 'none';
            }
            
            if (trialEl) {
                trialEl.style.display = data.isFree ? 'block' : 'block'; // Keep trial line for now as per design
            }

            if (periodEl) {
                if (data.isFree) {
                    periodEl.style.display = 'none';
                } else {
                    periodEl.style.display = 'inline';
                    periodEl.textContent = data.interval === 'year' ? '/ User / Year' : '/ User / Month';
                }
            }

        } catch (e) { console.warn('Failed to parse plan selection', e); }
    }

    syncSelectionData();

    /* Retry country select init (plugin may load late) */
    setTimeout(initCountrySelect, 400);
    setTimeout(initCountrySelect, 1000);
    setTimeout(updateDomainSuffix, 500);

})();
</script>