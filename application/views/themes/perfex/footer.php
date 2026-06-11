<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<footer class="footer">
    <div class="container">
        <?php if (isset($bodyclass) && $bodyclass === 'customers_login') { ?>
        <div class="ny-login-footer-bar">
            <p class="ny-login-footer-text">
                &copy; <?= date('Y'); ?> NOORYAKCRM. <?= _l('clients_login_all_rights'); ?>
                <span class="ny-login-footer-sep" aria-hidden="true">|</span>
                <a href="<?= privacy_policy_url(); ?>"><?= _l('clients_login_privacy_policy'); ?></a>
                <span class="ny-login-footer-sep" aria-hidden="true">|</span>
                <a href="<?= terms_url(); ?>"><?= _l('clients_login_terms_of_service'); ?></a>
            </p>
        </div>
        <?php } else { ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <span
                    class="copyright-footer"><?= date('Y'); ?>
                    <?= e(_l('clients_copyright', get_option('companyname'))); ?>
                </span>
                <?php if (is_gdpr() && get_option('gdpr_show_terms_and_conditions_in_footer') == '1') { ?>
                - <a href="<?= terms_url(); ?>"
                    class="terms-and-conditions-footer">
                    <?= _l('terms_and_conditions'); ?>
                </a>
                <?php } ?>
                <?php if (is_gdpr() && is_client_logged_in() && get_option('show_gdpr_link_in_footer') == '1') { ?>
                - <a href="<?= site_url('clients/gdpr'); ?>"
                    class="gdpr-footer">
                    <?= _l('gdpr_short'); ?>
                </a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</footer>
