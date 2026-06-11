<?php defined('BASEPATH') or exit('No direct script access allowed');

$company_name = trim(get_option('companyname')) ?: 'NOORYAKCRM';
$crm_logo     = base_url('img/crm_logo.png');
$logo_icon    = base_url('img/logo_icon.png');
$login_img    = base_url('img/login_image.png');
?>
<div class="ny-login-page">
    <div class="ny-login-left">
        <div class="ny-login-brand-top">
            <a href="<?= site_url(); ?>" class="ny-login-logo-link ny-login-logo-link--brand">
                <img src="<?= e($crm_logo); ?>" alt="<?= e($company_name); ?>" width="auto" height="44" loading="eager">
            </a>
        </div>
        <div class="ny-login-welcome">
            <h2><?= _l('clients_login_welcome_back'); ?></h2>
            <p><?= _l('clients_login_welcome_sub'); ?></p>
            <div class="ny-login-accent-line"></div>
        </div>
        <div class="ny-login-illustration">
            <img src="<?= e($login_img); ?>" alt="<?= e($company_name); ?> dashboard" loading="lazy" width="520" height="360">
        </div>
        <div class="ny-login-features">
            <div class="ny-login-feature">
                <span class="ny-feature-icon-wrap" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </span>
                <div>
                    <h3><?= _l('clients_login_feature_secure_title'); ?></h3>
                    <p><?= _l('clients_login_feature_secure_desc'); ?></p>
                </div>
            </div>
            <div class="ny-login-feature">
                <span class="ny-feature-icon-wrap" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </span>
                <div>
                    <h3><?= _l('clients_login_feature_insights_title'); ?></h3>
                    <p><?= _l('clients_login_feature_insights_desc'); ?></p>
                </div>
            </div>
            <div class="ny-login-feature">
                <span class="ny-feature-icon-wrap" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </span>
                <div>
                    <h3><?= _l('clients_login_feature_focus_title'); ?></h3>
                    <p><?= _l('clients_login_feature_focus_desc'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="ny-login-right">
        <div class="ny-login-card">
            <div class="ny-login-card-logo">
                <img src="<?= e($logo_icon); ?>" alt="<?= e($company_name); ?>" width="48" height="48" loading="eager">
            </div>
            <h1><?= _l('clients_login_card_title'); ?></h1>
            <p class="ny-login-card-sub"><?= _l('clients_login_card_subtitle'); ?></p>

            <?= form_open($this->uri->uri_string(), ['class' => 'login-form ny-login-form']); ?>
            <?php hooks()->do_action('clients_login_form_start'); ?>

            <?php if (! is_language_disabled()) { ?>
            <div class="ny-lang-sr">
                <label for="language" class="sr-only"><?= _l('language'); ?></label>
                <select name="language" id="language" class="form-control"
                    onchange="change_contact_language(this)" tabindex="-1" aria-hidden="true">
                    <?php $selected = (get_contact_language() != '') ? get_contact_language() : get_option('active_language'); ?>
                    <?php foreach ($this->app->get_available_languages() as $availableLanguage) { ?>
                    <option value="<?= e($availableLanguage); ?>"
                        <?= ($availableLanguage == $selected) ? 'selected' : '' ?>>
                        <?= e(ucfirst($availableLanguage)); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>

            <div class="form-group">
                <label for="email"><?= _l('clients_login_email'); ?></label>
                <div class="ny-input-wrap">
                    <span class="ny-input-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </span>
                    <input type="text" autofocus="true" class="form-control" name="email" id="email" placeholder="you@example.com" autocomplete="username">
                </div>
                <?= form_error('email'); ?>
            </div>

            <div class="form-group">
                <label for="password"><?= _l('clients_login_password'); ?></label>
                <div class="ny-input-wrap ny-input-wrap-password">
                    <span class="ny-input-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" autocomplete="current-password">
                    <button type="button" class="ny-pw-toggle" id="ny-toggle-password" aria-label="Toggle password visibility" aria-pressed="false">
                        <svg class="ny-icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg class="ny-icon-eye-off" style="display:none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <?= form_error('password'); ?>
            </div>

            <?php if (show_recaptcha_in_customers_area()) { ?>
            <div class="g-recaptcha tw-mb-4" data-sitekey="<?= get_option('recaptcha_site_key'); ?>"></div>
            <?= form_error('g-recaptcha-response'); ?>
            <?php } ?>

            <div class="ny-login-options">
                <div class="checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember"><?= _l('clients_login_remember'); ?></label>
                </div>
                <a href="<?= site_url('authentication/forgot_password'); ?>" class="ny-link-orange"><?= _l('customer_forgot_password'); ?></a>
            </div>

            <div class="form-group ny-login-submit-wrap">
                <button type="submit" class="btn ny-btn-login btn-block"><?= _l('clients_login_login_string'); ?></button>
            </div>

            <?php if (get_option('allow_registration') == 1) { ?>
            <div class="ny-login-divider"><span><?= _l('clients_login_or_divider'); ?></span></div>
            <div class="ny-login-register-row">
                <?= _l('clients_login_no_account'); ?>
                <a href="<?= site_url('authentication/register'); ?>"><?= _l('clients_login_start_trial'); ?></a>
            </div>
            <?php } ?>

            <?php hooks()->do_action('clients_login_form_end'); ?>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
(function () {
    var btn = document.getElementById('ny-toggle-password');
    var input = document.getElementById('password');
    if (!btn || !input) return;
    var eye = btn.querySelector('.ny-icon-eye');
    var eyeOff = btn.querySelector('.ny-icon-eye-off');
    btn.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-pressed', show ? 'true' : 'false');
        if (eye && eyeOff) {
            eye.style.display = show ? 'none' : '';
            eyeOff.style.display = show ? '' : 'none';
        }
    });
})();
</script>
