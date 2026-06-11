<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo render_yes_no_option('allow_flutex_admin_login', 'settings_allow_login_api', 'settings_allow_login_api_help'); ?>
        <?php echo render_textarea('flutex_admin_fcm_service_file_content', 'settings_fcm_service_file_content', get_option('flutex_admin_fcm_service_file_content'), ['rows' => 8, 'style' => 'line-height:20px;']); ?>
    </div>
</div>