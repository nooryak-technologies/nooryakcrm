<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This file allow syncing of subscription invoice related to SaaS from stripe to local.
 * It ensure all paid invoices in the subscription is recored on the system for improved reporting.
 */
hooks()->add_action('after_cron_run', function ($manually) {

    if (!$manually && (date('G') != (int)(get_option('invoice_auto_operations_hour') ?: 9))) {
        return;
    }

    class Perfex_saas_stripe_invoice_sync
    {
        protected $CI;
        protected $batchSize = 20;
        protected $lastProcessedOption = 'perfex_saas_last_processed_subscription_id';

        public function __construct()
        {
            $this->CI = &get_instance();

            $this->CI->load->model('payments_model');
            $this->CI->load->model('invoices_model');
            $this->CI->load->model('subscriptions_model');
        }

        /**
         * Run the invoice sync process
         */
        public function run()
        {
            $lastProcessedId = (int) get_option($this->lastProcessedOption);

            // Fetch subscriptions after last processed ID
            $subscriptions = $this->CI->db->select('s.id, p.name')
                ->from(db_prefix() . 'subscriptions AS s')
                ->join(db_prefix() . 'perfex_saas_packages AS p', "p.metadata LIKE CONCAT('%\"', s.stripe_plan_id, '\"%')", 'inner', false)
                ->where('s.id >', $lastProcessedId)
                ->where('s.status', 'active')
                ->where('p.name IS NOT NULL')
                ->order_by('s.id', 'asc')
                ->limit($this->batchSize)
                ->get()
                ->result();


            if (empty($subscriptions)) {
                // Reset for next full cycle
                update_option($this->lastProcessedOption, 0);
                return;
            }

            foreach ($subscriptions as $subscription) {
                $subscription = $this->CI->subscriptions_model->get_by_id($subscription->id);
                $this->processSubscription($subscription);
                $lastProcessedId = $subscription->id;

                // Save last processed ID
                update_option($this->lastProcessedOption, $lastProcessedId);
            }
        }

        /**
         * Process a single subscription: fetch Stripe invoices and store locally
         *
         * @param object $subscription
         * @return void
         */
        protected function processSubscription(object $subscription)
        {
            $stripeSubscriptionId = $subscription->stripe_subscription_id;

            if (!$stripeSubscriptionId) {
                return;
            }

            try {
                $invoices = $this->fetchAllInvoicesFromStripe($stripeSubscriptionId);

                foreach ($invoices as $invoice) {
                    $this->storeInvoiceIfNotExists($subscription, $invoice);
                }
            } catch (Exception $e) {
                log_activity('Stripe invoice sync failed for subscription ID ' . $subscription->id . ': ' . $e->getMessage());
            }
        }

        /**
         * Fetch all invoices from Stripe for a subscription (with pagination)
         *
         * @param string $stripeSubscriptionId
         * @return array
         */
        protected function fetchAllInvoicesFromStripe($stripeSubscriptionId)
        {
            $allInvoices = [];
            $params = [
                'subscription' => $stripeSubscriptionId,
                'limit' => 100,
                'status' => 'paid',
            ];
            $hasMore = true;

            while ($hasMore) {
                $response = \Stripe\Invoice::all($params); // order by created date in desc
                $allInvoices = array_merge($allInvoices, $response->data);

                if ($response->has_more) {
                    $params['starting_after'] = end($response->data)->id;
                } else {
                    $hasMore = false;
                }
            }

            $allInvoices = array_reverse($allInvoices); // Reverse array order to i.e old > to new
            return $allInvoices;
        }

        /**
         * Save invoice to local DB if it does not exist
         *
         * @param object $dbSubscription
         * @param object $invoice The stripe invoice object
         * @return void
         */
        protected function storeInvoiceIfNotExists($dbSubscription, $invoice)
        {
            if (empty($dbSubscription) || empty($invoice)) {
                return null;
            }

            $invoicePayments = Stripe\InvoicePayment::all([
                'invoice' => $invoice->id,
                'status'  => 'paid',
                'limit'   => 100
            ]);

            if (empty($invoicePayments) || empty($invoicePayments->data)) return;

            // Detect duplicate from payment intents for the invoice
            foreach ($invoicePayments->data as $invoicePayment) {
                if ($this->CI->payments_model->transaction_exists($invoicePayment->payment->payment_intent)) {
                    // Invoice transaction logged , return;
                    return;
                }
            }

            $new_invoice_data = create_subscription_invoice_data($dbSubscription, $invoice);

            if (!defined('STRIPE_SUBSCRIPTION_INVOICE')) {
                define('STRIPE_SUBSCRIPTION_INVOICE', true);
            }

            $id = $this->CI->invoices_model->add($new_invoice_data);

            if ($id) {
                $this->CI->db->where('id', $id);
                $this->CI->db->update(db_prefix() . 'invoices', [
                    'addedfrom' => $dbSubscription->created_from,
                ]);

                foreach ($invoicePayments->data as $invoicePayment) {
                    $payment_data['invoiceid']     = $id;
                    $payment_data['paymentmode']   = 'stripe';
                    $payment_data['amount']        = strcasecmp($dbSubscription->currency_name, 'JPY') == 0 ? $invoicePayment->amount_paid : $invoicePayment->amount_paid / 100;
                    $payment_data['transactionid'] = $invoicePayment->payment->payment_intent;
                    $this->CI->payments_model->add($payment_data, $dbSubscription->id);
                }
            }
        }
    }

    (new Perfex_saas_stripe_invoice_sync())->run();
});
