<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $badge = $this->input->post('display_type') === 'badge'; ?>

<?php if (count($invoices_years) > 1 || isset($invoices_total_currencies)) { ?>
<div
    class="tw-inline-flex tw-w-full tw-gap-8 ltr:tw-pr-2.5 tw-justify-items-end tw-items-end [&_.caret]:!tw-top-[9px] [&_.btn]:tw-py-0 [&_.btn]:tw-mr-0 [&_.btn]:tw-h-[24px] [&_.btn]:tw-font-medium [&_select]:tw-left-auto rtl:[&_.filter-option]:!tw-p-[inherit] rtl:[&_.filter-option]:!tw-text-left [&_.dropdown-menu]:tw-mt-1">
    <div class="tw-flex-1"></div>
    <div class="simple-bootstrap-select">
        <select data-width="fit" data-dropdown-align-right="true"
            class="selectpicker tw-w-full tw-min-w-[120px]" name="date_range" onchange="toggle_invoice_total_date_range(); init_invoices_total();">
            <option value=""><?= _l('date_range'); ?></option>
            <option value="today" <?php if ($this->input->post('date_range') == 'today') { echo 'selected'; } ?>>
                <?= _l('today'); ?>
            </option>
            <option value="this_week" <?php if ($this->input->post('date_range') == 'this_week') { echo 'selected'; } ?>>
                <?= _l('this_week'); ?>
            </option>
            <option value="this_month" <?php if ($this->input->post('date_range') == 'this_month') { echo 'selected'; } ?>>
                <?= _l('this_month'); ?>
            </option>
            <option value="custom" <?php if ($this->input->post('date_range') == 'custom') { echo 'selected'; } ?>>
                <?= _l('custom'); ?>
            </option>
        </select>
    </div>
    <div id="invoice-total-custom-date-range" class="tw-flex tw-items-center tw-gap-2"
        style="<?= $this->input->post('date_range') == 'custom' ? '' : 'display:none;'; ?>">
        <input type="date" name="date_from" value="<?= e($this->input->post('date_from')); ?>"
            class="form-control input-sm tw-min-w-[135px]" onchange="init_invoices_total();">
        <span class="tw-text-neutral-500">to</span>
        <input type="date" name="date_to" value="<?= e($this->input->post('date_to')); ?>"
            class="form-control input-sm tw-min-w-[135px]" onchange="init_invoices_total();">
    </div>
    <?php if (isset($invoices_total_currencies)) { ?>
    <div class="simple-bootstrap-select">
        <select data-show-subtext="true" data-width="fit" data-dropdown-align-right="true"
            class="selectpicker tw-w-full tw-min-w-[79px]" name="total_currency" onchange="init_invoices_total();">
            <?php foreach ($invoices_total_currencies as $currency) {
                $selected = '';
                if (! $this->input->post('currency')) {
                    if ($currency['isdefault'] == 1 || isset($_currency) && $_currency == $currency['id']) {
                        $selected = 'selected';
                    }
                } else {
                    if ($this->input->post('currency') == $currency['id']) {
                        $selected = 'selected';
                    }
                } ?>
            <option
                value="<?= e($currency['id']); ?>"
                <?= e($selected); ?>
                data-subtext="<?= e($currency['name']); ?>">
                <?= e($currency['symbol']); ?>
            </option>
            <?php
            } ?>
        </select>
    </div>
    <?php } ?>
    <?php if (count($invoices_years) > 1) { ?>
    <div class="simple-bootstrap-select">
        <select
            data-none-selected-text="<?= date('Y'); ?>"
            data-width="fit" class="selectpicker tw-w-full tw-min-w-[79px]" data-dropdown-align-right="true"
            name="invoices_total_years" onchange="init_invoices_total();" multiple="true" id="invoices_total_years">
            <?php foreach ($invoices_years as $year) { ?>
            <option
                value="<?= e($year['year']); ?>"
                <?php if ($this->input->post('years') && in_array($year['year'], $this->input->post('years')) || ! $this->input->post('years') && date('Y') == $year['year']) {
                    echo ' selected';
                } ?>>
                <?= e($year['year']); ?>
            </option>
            <?php } ?>
        </select>
    </div>
    <?php } ?>
</div>
<?php } ?>

<?php if ($badge === false) { ?>

<dl class="tw-grid tw-grid-cols-2 md:tw-grid-cols-4 tw-gap-2 tw-mb-0">
    <!-- <div class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-md tw-bg-white">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-font-normal text-warning">
                <?= _l('outstanding_invoices'); ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-font-semibold tw-text-neutral-600">
                    <?= e(app_format_money($total_result['due'], $total_result['currency'])); ?>
                </div>
            </dd>
        </div>
    </div>
    <div class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-md tw-bg-white">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-font-normal text-muted">
                <?= _l('past_due_invoices'); ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-font-semibold tw-text-neutral-600">
                    <?= e(app_format_money($total_result['overdue'], $total_result['currency'])); ?>
                </div>
            </dd>
        </div>
    </div>

    <div
        class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-md tw-bg-white last:tw-col-span-2 md:last:tw-col-auto">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-font-normal text-success">
                <?= _l('paid_invoices'); ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-font-semibold tw-text-neutral-600">
                    <?= e(app_format_money($total_result['paid'], $total_result['currency'])); ?>
                </div>
            </dd>
        </div>
    </div> -->
    <a href="<?= admin_url('invoices/list_invoices?advance_paid=1'); ?>" class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-md tw-bg-white hover:tw-shadow-sm tw-transition">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-font-normal text-info" style="color:#0ea5e9;">
                <?= _l('advance_paid'); ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-font-semibold tw-text-neutral-600">
                    <?= e(app_format_money($total_result['advance_paid'], $total_result['currency'])); ?>
                </div>
                <div class="tw-text-xs tw-text-neutral-500 tw-font-medium">
                    <?= (int) ($total_result['advance_paid_count'] ?? 0); ?>
                </div>
            </dd>
        </div>
    </a>
    <a href="<?= admin_url('invoices/list_invoices?pending_payment=1'); ?>" class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-md tw-bg-white hover:tw-shadow-sm tw-transition">
        <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
            <dt class="tw-font-normal text-danger">
                <?= _l('pending_payment'); ?>
            </dt>
            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                <div class="tw-flex tw-items-baseline tw-font-semibold tw-text-neutral-600">
                    <?= e(app_format_money($total_result['pending_payment'], $total_result['currency'])); ?>
                </div>
                <div class="tw-text-xs tw-text-neutral-500 tw-font-medium">
                    <?= (int) ($total_result['pending_payment_count'] ?? 0); ?>
                </div>
            </dd>
        </div>
    </a>
</dl>
<?php } else { ?>
<div class="tw-relative">
    <div class="md:tw-flex md:tw-items-center tw-space-y-1 md:tw-space-y-0 md:tw-gap-1 tw-mb-0">

        <div class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-lg tw-shrink-0 tw-bg-white">
            <div class="tw-px-2 md:tw-px-1.5 tw-py-1.5 md:tw-py-0.5 tw-text-sm tw-inline-flex tw-gap-2">
                <div class="text-success">
                    <?= _l('paid_invoices'); ?>
                </div>
                <div class="tw-font-medium">
                    <?= e(app_format_money($total_result['paid'], $total_result['currency'])); ?>
                </div>
            </div>
        </div>
        <div class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-lg tw-shrink-0 tw-bg-white">
            <div class="tw-px-2 md:tw-px-1.5 tw-py-1.5 md:tw-py-0.5 tw-text-sm tw-inline-flex tw-gap-2">
                <div class="text-danger">
                    <?= _l('past_due_invoices'); ?>
                </div>
                <div class="tw-font-medium">
                    <?= e(app_format_money($total_result['overdue'], $total_result['currency'])); ?>
                </div>
            </div>
        </div>
        <div class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-lg tw-shrink-0 tw-bg-white">
            <div class="tw-px-2 md:tw-px-1.5 tw-py-1.5 md:tw-py-0.5 tw-text-sm tw-inline-flex tw-gap-2">
                <div class="text-warning">
                    <?= _l('outstanding_invoices'); ?>
                </div>
                <div class="tw-font-medium">
                    <?= e(app_format_money($total_result['due'], $total_result['currency'])); ?>
                </div>
            </div>
        </div>
        <a href="<?= admin_url('invoices/list_invoices?advance_paid=1'); ?>" class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-lg tw-shrink-0 tw-bg-white hover:tw-shadow-sm tw-transition">
            <div class="tw-px-2 md:tw-px-1.5 tw-py-1.5 md:tw-py-0.5 tw-text-sm tw-inline-flex tw-gap-2">
                <div style="color:#0ea5e9;">
                    <?= _l('advance_paid'); ?>
                </div>
                <div class="tw-font-medium">
                    <?= e(app_format_money($total_result['advance_paid'], $total_result['currency'])); ?>
                </div>
                <div class="tw-text-neutral-500">
                    <?= (int) ($total_result['advance_paid_count'] ?? 0); ?>
                </div>
            </div>
        </a>
        <a href="<?= admin_url('invoices/list_invoices?pending_payment=1'); ?>" class="tw-border tw-border-solid tw-border-neutral-300/80 tw-rounded-lg tw-shrink-0 tw-bg-white hover:tw-shadow-sm tw-transition">
            <div class="tw-px-2 md:tw-px-1.5 tw-py-1.5 md:tw-py-0.5 tw-text-sm tw-inline-flex tw-gap-2">
                <div class="text-danger">
                    <?= _l('pending_payment'); ?>
                </div>
                <div class="tw-font-medium">
                    <?= e(app_format_money($total_result['pending_payment'], $total_result['currency'])); ?>
                </div>
                <div class="tw-text-neutral-500">
                    <?= (int) ($total_result['pending_payment_count'] ?? 0); ?>
                </div>
            </div>
        </a>
    </div>
</div>
<?php } ?>
<script>
    (function() {
        if (typeof(init_selectpicker) == 'function') {
            init_selectpicker();
        }
        toggle_invoice_total_date_range();
    })();

    function toggle_invoice_total_date_range() {
        var range = $('select[name="date_range"]').val();
        var customRange = $('#invoice-total-custom-date-range');

        if (range === 'custom') {
            customRange.show();
        } else {
            customRange.hide();
        }
    }
</script>