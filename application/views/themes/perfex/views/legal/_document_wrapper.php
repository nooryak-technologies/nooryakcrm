<?php defined('BASEPATH') or exit('No direct script access allowed');
$other_label = isset($other_label) ? $other_label : '';
$other_url = isset($other_url) ? $other_url : '';
$this->load->helper('nooryak');
$this->load->view('partials/nooryak_public_header', nooryak_public_nav_data());
?>
<style id="ny-legal-layout-fix">
body.customers_legal.nooryak-public-nav { padding-top: 0 !important; }
body.customers_legal .nooryak-public-header .nooryak-nav.navbar-area,
body.customers_legal .nooryak-public-header .nooryak-nav.navbar-area.sticky {
    top: 8px !important; padding: 0 !important; margin: 0 auto !important;
}
body.customers_legal .nooryak-public-header .navbar-area .navbar.navbar-expand-lg,
body.customers_legal .nooryak-public-header .navbar-area.sticky .navbar {
    padding: 6px 14px !important; min-height: 0 !important; box-shadow: none !important;
    background: transparent !important; width: 100% !important; max-width: 100% !important;
}

body.customers_legal .ny-legal-page { padding-top: 120px !important; }
body.customers_legal .ny-legal-card { margin-top: 32px !important; }
@media (max-width: 767px) {
    body.customers_legal .ny-legal-page { padding-top: 100px !important; }
    body.customers_legal .ny-legal-card { margin-top: 24px !important; }
}
.navbar{
    margin-bottom:0px !important;
}
body.customers_legal .ny-legal-card {
    max-width: 1100px !important;
}
</style>
<div class="ny-legal-page">
    <main class="ny-legal-main">
        <article class="ny-legal-card">
            <h1><?= e($page_title); ?></h1>
            <p class="ny-legal-updated">Last updated: <?= e($last_updated ?? date('F j, Y')); ?></p>

            <?php if (!empty($use_custom_content) && !empty($custom_content)) { ?>
            <div class="ny-legal-custom-content">
                <?= $custom_content; ?>
            </div>
            <?php } else { ?>
            <?php if (!empty($intro)) { ?>
            <p class="ny-legal-intro"><?= e($intro); ?></p>
            <?php } ?>
            <?= $content_partial ?? ''; ?>
            <?php } ?>
        </article>
    </main>

    <footer class="ny-legal-footer-bar">
        <p>
            &copy; <?= date('Y'); ?> NOORYAKCRM. All rights reserved.
            <?php if ($other_url !== '') { ?>
            <span aria-hidden="true">|</span>
            <a href="<?= e($other_url); ?>"><?= e($other_label); ?></a>
            <?php } ?>
            <span aria-hidden="true">|</span>
            <a href="mailto:sales@nooryakcrm.com">Contact Us</a>
        </p>
    </footer>

    <!-- Scroll To Top -->
    <button id="scrollTopBtn" aria-label="Scroll to top">
        <svg width="20" height="20" viewBox="0 0 24 24"><path d="M18 15l-6-6-6 6"></path></svg>
    </button>
</div>
