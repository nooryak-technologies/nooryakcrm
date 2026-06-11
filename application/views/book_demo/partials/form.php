<?php defined('BASEPATH') or exit('No direct script access allowed');

$val = static function ($key) use ($posted) {
    return isset($posted[$key]) ? e($posted[$key]) : '';
};
$invalid = static function ($field) {
    return form_error($field) !== '' ? ' is-invalid' : '';
};
?>
<div class="form-card">
    <div class="form-intro">
        <span class="badge-pill">Request a Demo</span>
        <h1 class="form-heading">Schedule a <span class="highlight">Personalized Demo</span></h1>
        <p class="form-subtext">
            See how Nooryak CRM can help your team manage leads, close deals,
            and grow your business — all in one platform.
        </p>
    </div>

    <h2 class="form-card-title">Request a Personalized Demo</h2>

    <?php if (!empty($success)) { ?>
    <div class="alert-demo alert-demo-success" role="alert">
        Thank you! Your demo request has been submitted. Our team will contact you shortly.
    </div>
    <?php } ?>

    <?php if (!empty($error_message)) { ?>
    <div class="alert-demo alert-demo-danger" role="alert"><?= e($error_message); ?></div>
    <?php } ?>

    <?php if (empty($success)) { ?>
    <?= form_open($form_action, ['id' => 'book-demo-form', 'method' => 'post', 'novalidate' => 'novalidate']); ?>

    <div class="form-row">
        <div class="field-group">
            <label for="full_name">Full Name <span class="req">*</span></label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required autocomplete="name" value="<?= $val('full_name'); ?>" class="<?= trim($invalid('full_name')); ?>">
            <?= form_error('full_name', '<p class="field-error">', '</p>'); ?>
        </div>
        <div class="field-group">
            <label for="email">Business Email <span class="req">*</span></label>
            <input type="email" id="email" name="email" placeholder="Enter your business email" required autocomplete="email" value="<?= $val('email'); ?>" class="<?= trim($invalid('email')); ?>">
            <?= form_error('email', '<p class="field-error">', '</p>'); ?>
        </div>
    </div>

    <div class="form-row">
        <div class="field-group">
            <label for="phone">Phone Number <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required autocomplete="tel" value="<?= $val('phone'); ?>" class="<?= trim($invalid('phone')); ?>">
            <?= form_error('phone', '<p class="field-error">', '</p>'); ?>
        </div>
        <div class="field-group">
            <label for="company">Company Name <span class="req">*</span></label>
            <input type="text" id="company" name="company" placeholder="Enter your company name" required autocomplete="organization" value="<?= $val('company'); ?>" class="<?= trim($invalid('company')); ?>">
            <?= form_error('company', '<p class="field-error">', '</p>'); ?>
        </div>
    </div>

    <div class="form-row form-row--selects">
        <div class="field-group">
            <label for="num_users">Number of Users <span class="req">*</span></label>
            <div class="select-wrap">
                <select id="num_users" name="num_users" required class="<?= trim($invalid('num_users')); ?>">
                    <option value="" disabled <?= empty($posted['num_users']) ? 'selected' : ''; ?>>Select number of users</option>
                    <?php foreach (['1-10' => '1 – 10', '11-50' => '11 – 50', '51-200' => '51 – 200', '201+' => '201+'] as $v => $label) { ?>
                    <option value="<?= e($v); ?>" <?= (isset($posted['num_users']) && $posted['num_users'] === $v) ? 'selected' : ''; ?>><?= e($label); ?></option>
                    <?php } ?>
                </select>
            </div>
            <?= form_error('num_users', '<p class="field-error">', '</p>'); ?>
        </div>
        <div class="field-group">
            <label for="industry">Industry <span class="req">*</span></label>
            <div class="select-wrap">
                <select id="industry" name="industry" required class="<?= trim($invalid('industry')); ?>">
                    <option value="" disabled <?= empty($posted['industry']) ? 'selected' : ''; ?>>Select your industry</option>
                    <?php foreach (['technology' => 'Technology', 'finance' => 'Finance', 'healthcare' => 'Healthcare', 'retail' => 'Retail', 'manufacturing' => 'Manufacturing', 'education' => 'Education', 'other' => 'Other'] as $v => $label) { ?>
                    <option value="<?= e($v); ?>" <?= (isset($posted['industry']) && $posted['industry'] === $v) ? 'selected' : ''; ?>><?= e($label); ?></option>
                    <?php } ?>
                </select>
            </div>
            <?= form_error('industry', '<p class="field-error">', '</p>'); ?>
        </div>
    </div>

    <div class="form-row single">
        <div class="field-group">
            <label for="requirements">Your Requirements <span class="req">*</span></label>
            <textarea id="requirements" name="requirements" placeholder="Tell us what you want to achieve or the challenges you're facing" required class="<?= trim($invalid('requirements')); ?>"><?= isset($posted['requirements']) ? e($posted['requirements']) : ''; ?></textarea>
            <?= form_error('requirements', '<p class="field-error">', '</p>'); ?>
        </div>
    </div>

    <?php if ($show_recaptcha) { ?>
    <div class="recaptcha-wrap">
        <div class="g-recaptcha" data-sitekey="<?= e($recaptcha_site_key); ?>"></div>
        <?= form_error('g-recaptcha-response', '<p class="field-error">', '</p>'); ?>
    </div>
    <?php } ?>

    <button type="submit" class="btn-request">Request Demo</button>
    <?= form_close(); ?>
    <?php } ?>

    <p class="privacy-note">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        Your information is safe with us. We do not share your data with any third party.
    </p>
</div>
