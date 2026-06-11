<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($title); ?></title>
    <link rel="icon" type="image/png" href="<?= e($favicon_url); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="<?= e($softland_assets); ?>css/vendor.css" />
    <link rel="stylesheet" href="<?= e($softland_assets); ?>css/main.css" />
    <link rel="stylesheet" href="<?= e($softland_assets); ?>css/nooryak-style.css" />
    <link rel="stylesheet" href="<?= base_url('assets/css/nooryak-public-nav.css?v=' . filemtime(FCPATH . 'assets/css/nooryak-public-nav.css')); ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/book-demo.css?v=' . filemtime(FCPATH . 'assets/css/book-demo.css')); ?>" />
    <?php if ($show_recaptcha) { ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php } ?>
</head>
<body class="book-demo-page nooryak-public-nav">

<?php $this->load->view('book_demo/partials/header'); ?>

<div class="demo-wrapper">
    <?php $this->load->view('book_demo/partials/mobile_hero'); ?>

    <div class="demo-grid">
        <div class="demo-col-form">
            <?php $this->load->view('book_demo/partials/form'); ?>
        </div>
        <div class="demo-col-info">
            <?php $this->load->view('book_demo/partials/info_panel'); ?>
        </div>
    </div>

    <?php $this->load->view('book_demo/partials/trust_bar'); ?>

    <div class="bottom-bar">
        <div class="bottom-left">
            <div class="bottom-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.07 11.5a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3 .82h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9a16 16 0 0 0 6.29 6.29l1.56-1.56a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
            </div>
            <div>
                <div class="bottom-assist-title">Need Immediate Assistance?</div>
                <div class="bottom-assist-sub">Talk to our sales team directly.</div>
            </div>
        </div>
        <div class="bottom-contacts">
            <a class="contact-pill" href="tel:+918754540844">
                <span class="contact-pill-icon phone">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.19 18.85A19.5 19.5 0 0 1 4.07 11.5a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3 .82h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9a16 16 0 0 0 6.29 6.29l1.56-1.56a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                </span>
                +91 87545 40844
            </a>
            <a class="contact-pill" href="mailto:sales@nooryakcrm.com">
                <span class="contact-pill-icon email">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                sales@nooryakcrm.com
            </a>
        </div>
    </div>
</div>

<script src="<?= e($softland_assets); ?>js/vendor.js" defer></script>
<script src="<?= e($softland_assets); ?>js/main.js" defer></script>
</body>
</html>
