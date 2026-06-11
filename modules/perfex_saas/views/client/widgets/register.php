<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This widget add subdomain and custom domain input field into Perfex CRM registeration form
 * (i.e /authentiaction/register form) when default package allows it. 
 */
if ($CI->router->fetch_class() !== 'authentication' || $CI->router->fetch_method() !== 'register' || is_client_logged_in()) return;

$package = [];

// Check if we have selected plan in session
$package_slug = $CI->session->{perfex_saas_route_id_prefix('plan')} ?? '';
if (!empty($package_slug)) {
    $CI->db->where('slug', $package_slug);
    $package = $CI->perfex_saas_model->packages()[0] ?? [];
} else {
    // Use the default package
    $CI->db->where('is_default', 1);
    $package = $CI->perfex_saas_model->packages()[0] ?? [];
}

if (empty($package)) return;

$package = (object)$package;
$can_use_subdomain = (int)$package->metadata->enable_subdomain && (int)perfex_saas_get_options('perfex_saas_enable_subdomain_input_on_signup_form');
?>

<?php if ($can_use_subdomain) : ?>

<!-- containter to hold the input fields -->
<div class="form-group register-saas-info-group" style="display: none;">
    <label class="control-label" for="slug"><?= _l('perfex_saas_register_form_subdomain_id'); ?></label>
    <div class="crm-domain-wrap">
        <span class="crm-domain-prefix">https://</span>
        <input type="text" name="slug" id="slug" class="crm-domain-input" 
               placeholder="yourcompany" 
               maxlength="<?= PERFEX_SAAS_MAX_SLUG_LENGTH; ?>"
               value="<?= set_value('slug'); ?>">
        <span class="crm-domain-suffix" id="crm-domain-suffix-text">.localhost</span>
    </div>
    <input type="hidden" value="<?= $package->slug; ?>" name="<?= perfex_saas_route_id_prefix('plan');?>" />
    <?= form_error('slug'); ?>
</div>


<!-- Widget javascript -->
<script>
/**
 * This function modify the register form to include subdomain and custom domain input fields
 *
 * @return void
 */
function bindDomainInputToRegisterationForm() {
    // Insert Domain field before Work Email to pair them in the same row
    $(".register-saas-info-group").prependTo($(".register-email-group").parent());
    $(".register-saas-info-group").show();
    if (typeof bindAndListenToSlugInput === 'function') {
        bindAndListenToSlugInput(".register-saas-info-group", "input[name=company]");
    }
}

// Bind
setTimeout(bindDomainInputToRegisterationForm, 200);

// Backup call incase content not in DOM during the time out call
window.addEventListener("DOMContentLoaded", () => {
    if (!$("form .register-saas-info-group").length) bindDomainInputToRegisterationForm()
});
</script>

<?php endif; ?>