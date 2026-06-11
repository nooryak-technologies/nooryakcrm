<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?= form_open($this->uri->uri_string(), ['id' => 'invoice-form', 'class' => '_transaction_form invoice-form']); ?>
            <?php if (isset($invoice)) {
                echo form_hidden('isedit');
            } ?>
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                        <?= e(isset($invoice) ? format_invoice_number($invoice) : _l('create_new_invoice')); ?>
                    </span>
                    <?= isset($invoice) ? format_invoice_status($invoice->status) : ''; ?>
                </h4>
                <?php $this->load->view('admin/invoices/invoice_template'); ?>
            </div>
            <?= form_close(); ?>
            <?php $this->load->view('admin/invoice_items/item'); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        validate_invoice_form();
        // Init accountacy currency symbol
        init_currency();
        // Project ajax search
        init_ajax_project_search_by_customer_id();
        // Maybe items ajax search
        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');

        // Advance & Balance Due calculation
        function update_advance_balance() {
            var symbol = $('input[name="currency"]').closest('.bootstrap-select').find('.filter-option-inner-inner').text().match(/\(([^)]+)\)/);
            var currencySymbol = symbol ? symbol[1] : '';
            var totalText = $('.total').text().replace(/[^0-9.,-]/g, '').replace(',', '');
            var total = parseFloat(totalText) || 0;
            var advance = parseFloat($('input[name="advance"]').val()) || 0;
            var balance = total - advance;
            if (advance > 0) {
                $('.advance-display').text('-' + currencySymbol + accounting.formatNumber(advance, 2));
                $('.balance-due').text(currencySymbol + accounting.formatNumber(balance, 2));
                $('#balance_due_row').show();
            } else {
                $('.advance-display').text('');
                $('.balance-due').text('');
                $('#balance_due_row').hide();
            }
        }

        // Run on advance input change
        $(document).on('input change', 'input[name="advance"]', update_advance_balance);

        // Hook into existing calculate_total via MutationObserver on .total cell
        var totalObserver = new MutationObserver(update_advance_balance);
        var totalCell = document.querySelector('.total');
        if (totalCell) {
            totalObserver.observe(totalCell, { childList: true, subtree: true, characterData: true });
        }

        // Initial run
        update_advance_balance();
    });
</script>
</body>

</html>