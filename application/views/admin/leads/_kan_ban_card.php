<?php defined('BASEPATH') or exit('No direct script access allowed');
$lead_already_client_tooltip = '';
$lead_is_client              = $lead['is_lead_client'] !== '0';
if ($lead_is_client) {
    $lead_already_client_tooltip = ' data-toggle="tooltip" title="' . _l('lead_have_client_profile') . '"';
}
if ($lead['status'] == $status['id']) { ?>
<li data-lead-id="<?= e($lead['id']); ?>" <?= e($lead_already_client_tooltip); ?>
    class="lead-kan-ban<?= $lead['assigned'] == get_staff_user_id() ? ' current-user-lead' : ''; ?><?= $lead_is_client && get_option('lead_lock_after_convert_to_customer') == 1 && ! is_admin() ? ' not-sortable' : ''; ?><?= isset($card_class) ? $card_class : ''; ?>"
    <?= isset($card_style) ? $card_style : ''; ?>>
    <div class="panel-body lead-body">
        <div class="card-header-row">
            <a href="<?= admin_url('leads/index/' . e($lead['id'])); ?>"
                title="#<?= e($lead['id']) . ' - ' . e($lead['lead_name']); ?>"
                onclick="init_lead(<?= e($lead['id']); ?>);return false;"
                class="lead-title-link">
                #<?= e($lead['id']) . ' - ' . e($lead['lead_name']); ?>
            </a>
            <?php 
            $lead_value = $lead['lead_value'] != 0 ? app_format_money($lead['lead_value'], $base_currency->symbol) : '--';
            ?>
            <span class="lead-value-badge"><?= e($lead_value); ?></span>
        </div>
        
        <div class="card-subtitle-row">
            <?php if (!empty($lead['company'])) { ?>
                <span class="lead-company-text"><?= e($lead['company']); ?></span>
            <?php } else { ?>
                <span class="lead-source-text"><?= _l('leads_canban_source', $lead['source_name']); ?></span>
            <?php } ?>
        </div>

        <div class="card-divider"></div>

        <div class="card-footer-row">
            <div class="card-footer-left">
                <?php if ($lead['assigned'] != 0) { ?>
                    <a href="<?= admin_url('profile/' . $lead['assigned']); ?>"
                        data-placement="right" data-toggle="tooltip"
                        title="<?= e(get_staff_full_name($lead['assigned'])); ?>">
                        <?= staff_profile_image($lead['assigned'], ['staff-profile-image-xs']); ?>
                    </a>
                    <div class="assignee-meta">
                        <span class="assignee-name"><?= e(get_staff_full_name($lead['assigned'])); ?></span>
                        <span class="lead-time-ago"><?= e(time_ago($lead['dateadded'])); ?></span>
                    </div>
                <?php } else { ?>
                    <span class="unassigned-avatar" data-toggle="tooltip" title="Unassigned">
                        <i class="fa-solid fa-user-xmark"></i>
                    </span>
                    <div class="assignee-meta">
                        <span class="assignee-name">Unassigned</span>
                        <span class="lead-time-ago"><?= e(time_ago($lead['dateadded'])); ?></span>
                    </div>
                <?php } ?>
            </div>
            
            <div class="card-footer-right">
                <?php hooks()->do_action('before_leads_kanban_card_icons', $lead); ?>
                <span data-toggle="tooltip" data-placement="left"
                    data-title="<?= _l('leads_canban_notes', $lead['total_notes']); ?>">
                    <i class="fa-regular fa-note-sticky"></i>
                    <?= e($lead['total_notes']); ?>
                </span>
                <span data-placement="left" data-toggle="tooltip"
                    data-title="<?= _l('lead_kan_ban_attachments', $lead['total_files']); ?>">
                    <i class="fa fa-paperclip"></i>
                    <?= e($lead['total_files']); ?>
                </span>
                <?php hooks()->do_action('after_leads_kanban_card_icons', $lead); ?>
            </div>
        </div>

        <?php if ($lead['tags']) { ?>
        <div class="card-tags-row">
            <div class="kanban-tags">
                <?= render_tags($lead['tags']); ?>
            </div>
        </div>
        <?php } ?>
        
        <div class="tw-mt-3 tw-flex tw-justify-end tw-items-center">
            <a href="#" class="text-muted kan-ban-edit-lead"
                onclick="init_lead(<?= e($lead['id']); ?>, true); return false;">
                <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
            </a>
            <a href="#" class="text-muted kan-ban-expand-top"
                onclick="slideToggle('#kan-ban-expand-<?= e($lead['id']); ?>'); return false;">
                <i class="fa fa-expand" aria-hidden="true"></i>
            </a>
        </div>
        
        <div class="clearfix no-margin"></div>
        <div id="kan-ban-expand-<?= e($lead['id']); ?>"
            class="padding-10" style="display:none;">
            <div class="clearfix"></div>
            <hr class="hr-10" />
            <p class="text-muted lead-field-heading">
                <?= _l('lead_title'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= e($lead['title'] != '' ? $lead['title'] : '-') ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_add_edit_email'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= $lead['email'] != '' ? '<a href="mailto:' . e($lead['email']) . '">' . e($lead['email']) . '</a>' : '-' ?>
            </p>
            <p class="text-muted lead-field-heading">
                Interested Service
            </p>
            <p class="bold tw-text-sm">
                <?= $lead['website'] != '' ? '<a href="' . e(maybe_add_http($lead['website'])) . '" target="_blank">' . e($lead['website']) . '</a>' : '-' ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_add_edit_phonenumber'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= $lead['phonenumber'] != '' ? '<a href="tel:' . e($lead['phonenumber']) . '">' . e($lead['phonenumber']) . '</a>' : '-' ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_company'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= e($lead['company'] != '' ? $lead['company'] : '-') ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_address'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= e($lead['address'] != '' ? $lead['address'] : '-') ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_city'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= e($lead['city'] != '' ? $lead['city'] : '-') ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_state'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= e($lead['state'] != '' ? $lead['state'] : '-') ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_country'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= e($lead['country'] != 0 ? get_country($lead['country'])->short_name : '-') ?>
            </p>
            <p class="text-muted lead-field-heading">
                <?= _l('lead_zip'); ?>
            </p>
            <p class="bold tw-text-sm">
                <?= e($lead['zip'] != '' ? $lead['zip'] : '-') ?>
            </p>
        </div>
    </div>
</li>
<?php }
?>