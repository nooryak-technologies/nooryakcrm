<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Invoice Builder PDF Bridge
 *
 * Loaded by app_pdf() after the 'invoice_pdf_class_path' filter swaps the
 * path to this file. The filter only swaps when a built invoice exists, so
 * this class is guaranteed to always have a valid built invoice to work with.
 *
 * Because app_pdf() derives the class name from the ORIGINAL path before the
 * filter runs, it always calls  new Invoice_pdf($invoice, $tag)->prepare().
 * This file therefore defines a class named Invoice_pdf that delegates all
 * work to Built_invoice_pdf.
 *
 * The class_exists guard prevents a fatal redeclaration if Invoice_pdf was
 * somehow already loaded earlier in the same request (edge case on bulk export).
 */

include_once(APP_MODULES_PATH . 'invoices_builder/libraries/pdf/Built_invoice_pdf.php');

if (!class_exists('Invoice_pdf', false)) {

    class Invoice_pdf
    {
        /** @var Built_invoice_pdf */
        private $delegate;

        public function __construct($invoice, $tag = '')
        {
            $CI = &get_instance();

            if (!class_exists('Invoices_builder_model', false)) {
                $CI->load->model('invoices_builder/invoices_builder_model');
            }

            // The filter already confirmed a built invoice exists for this ID,
            // so we fetch it directly — most recent one wins.
            $CI->db->where('invoice_id', (int) $invoice->id);
            $CI->db->order_by('id', 'desc');
            $CI->db->limit(1);
            $built_invoice = $CI->db->get(db_prefix() . 'ib_invoices_built')->row();

            $template = $CI->invoices_builder_model->get_template($built_invoice->template_id);

            $this->delegate = new Built_invoice_pdf($invoice, $template, $built_invoice->id, $tag);
        }

        public function prepare()
        {
            return $this->delegate->prepare();
        }

        public function Output($name = 'doc.pdf', $dest = 'I')
        {
            return $this->delegate->Output($name, $dest);
        }

        public function __call($name, $arguments)
        {
            return call_user_func_array([$this->delegate, $name], $arguments);
        }
    }

}
