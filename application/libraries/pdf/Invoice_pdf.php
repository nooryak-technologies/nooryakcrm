<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Invoice_pdf extends App_pdf
{
    protected $invoice;

    private $invoice_number;

    public function __construct($invoice, $tag = '')
    {
        $this->load_language($invoice->clientid);
        $invoice                = hooks()->apply_filters('invoice_html_pdf_data', $invoice);
        $GLOBALS['invoice_pdf'] = $invoice;

        parent::__construct();

        if (!class_exists('Invoices_model', false)) {
            $this->ci->load->model('invoices_model');
        }

        $this->tag            = $tag;
        $this->invoice        = $invoice;
        $this->invoice_number = format_invoice_number($this->invoice->id);

        $this->SetTitle($this->invoice_number);
    }

    public function prepare()
    {
        $this->with_number_to_word($this->invoice->clientid);

        $this->set_view_vars([
            'status'         => $this->invoice->status,
            'invoice_number' => $this->invoice_number,
            'payment_modes'  => $this->get_payment_modes(),
            'invoice'        => $this->invoice,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'invoice';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_invoicepdf.php';
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/invoicepdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }

    private function get_payment_modes()
    {
        $this->ci->load->model('payment_modes_model');
        $payment_modes = $this->ci->payment_modes_model->get();

        // In case user want to include {invoice_number} or {client_id} in PDF offline mode description
        foreach ($payment_modes as $key => $mode) {
            if (isset($mode['description'])) {
                $payment_modes[$key]['description'] = str_replace('{invoice_number}', $this->invoice_number, $mode['description']);
                $payment_modes[$key]['description'] = str_replace('{client_id}', $this->invoice->clientid, $mode['description']);
            }
        }

        return $payment_modes;
    }

    protected $captured_html = '';

    public function writeHTML($html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '')
    {
        $this->captured_html = $html;
    }

    public function Output($name = 'doc.pdf', $dest = 'I')
    {
        if (!class_exists('Dompdf\Dompdf')) {
            require_once(APPPATH . 'vendor/autoload.php');
        }

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', FCPATH);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($this->captured_html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        if ($dest === 'I') {
            $dompdf->stream($name, ['Attachment' => 0]);
            die;
        } elseif ($dest === 'D') {
            $dompdf->stream($name, ['Attachment' => 1]);
            die;
        } elseif ($dest === 'S') {
            return $dompdf->output();
        } else {
            $dompdf->stream($name, ['Attachment' => 0]);
            die;
        }
    }
}
