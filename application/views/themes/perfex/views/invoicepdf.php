<?php
defined('BASEPATH') or exit('No direct script access allowed');

$dimensions      = $pdf->getPageDimensions();
$company_name    = get_option('companyname');
$company_phone   = get_option('companyphonenumber');
$company_email   = get_option('companyemail');

$organization_info  = '<div style="color:#1f2937; line-height:1.45; font-size:12px; text-align:right;">';
$organization_info .= '<strong>' . nl2br(format_organization_info()) . '</strong>';
$organization_info .= '</div>';

$invoice_no   = $invoice_number;
$invoice_date = _d($invoice->date);
$client_name  = format_customer_info($invoice, 'invoice', 'billing');

$original_price = (float) $invoice->subtotal;
$actual_price   = (float) $invoice->total;
if ($original_price < $actual_price) {
    $original_price = $actual_price;
}

$advance_percentage = get_custom_field_value($invoice->id, 'advance_percentage', 'invoice', false);
$advance_percentage = trim((string) $advance_percentage);
if ($advance_percentage === '') {
    $advance_percentage = '70';
}
$advance_percentage = preg_replace('/[^0-9.]/', '', $advance_percentage);
if ($advance_percentage === '' || ! is_numeric($advance_percentage)) {
    $advance_percentage = '70';
}
$advance_percentage = (float) $advance_percentage;
$advance_amount     = round($actual_price * $advance_percentage / 100, 0);
$balance_amount     = $actual_price - $advance_amount;
$balance_percentage = 100 - $advance_percentage;

if ($invoice->project_id && get_option('show_project_on_invoice') == 1) {
    $project_name = get_project_name_by_id($invoice->project_id);
} else {
    $project_name = $invoice->clientnote ?: $invoice->number;
}

$items      = get_items_table_data($invoice, 'invoice', 'pdf');
$items_html = $items->table();

$note_text = trim((string) $invoice->clientnote);
if ($note_text === '' && ! empty($invoice->terms)) {
    $note_text = $invoice->terms;
}

$html = '
<style>
    .invoice-wrap { font-family: helvetica, arial, sans-serif; color: #0f172a; }
    .invoice-card { background: #ffffff; border-radius: 10px; overflow: hidden; }
    .top-header { width: 100%; border-collapse: collapse; }
    .headline { color: #0f2e44; font-size: 34px; font-weight: 700; margin: 0; line-height: 1; }
    .company { color: #0f2e44; font-size: 12px; line-height: 1.5; }
    .section-title { color: #0f2e44; font-size: 14px; font-weight: 700; margin: 0 0 8px 0; letter-spacing: .3px; }
    .small-copy { font-size: 13px; color: #334155; line-height: 1.45; }
    .big-copy { font-size: 15px; color: #0f172a; line-height: 1.55; }
    .amount-line { font-size: 14px; line-height: 1.5; color: #0f172a; }
    .items-head td { background: #15a6cc; color: #ffffff; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; }
    .note-box { font-size: 11px; color: #334155; line-height: 1.55; }
    .summary td { font-size: 13px; padding: 4px 0; }
    .footer { background: #0f3b4a; color: #ffffff; font-size: 12px; }
</style>

<div class="invoice-wrap">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#f5f7fb; padding:18px;">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-card" style="border:1px solid #e5e7eb;">

                    <!-- HEADER: Logo + Company -->
                    <tr>
                        <td style="padding:26px 28px 18px 28px; vertical-align:top;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="top-header">
                                <tr>
                                    <td width="48%" valign="top">' . pdf_logo_url() . '</td>
                                    <td width="52%" align="right" valign="top">
                                        <div class="headline">INVOICE</div>
                                        <div style="margin-top:10px;" class="company">' . $organization_info . '</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CLIENT INFO + INVOICE DETAILS + NOTE -->
                    <tr>
                        <td style="padding:0 28px 14px 28px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="60%" valign="top" style="padding-right:14px;">
                                        <div class="section-title">CLIENT INFO: <span style="font-weight:700;">' . $client_name . '</span></div>
                                        <div class="amount-line"><strong>INVOICE NO :</strong> ' . $invoice_no . '</div>
                                        <div class="amount-line"><strong>INVOICE DATE:</strong> ' . $invoice_date . '</div>
                                        <div class="amount-line"><strong>AMOUNT:</strong> ' . app_format_money($actual_price, $invoice->currency_name) . ' (Excluding 18% GST)</div>
                                        <div class="amount-line"><strong>ADVANCE :</strong> ' . app_format_money($advance_amount, $invoice->currency_name) . ' (' . $advance_percentage . '%) - Balance ' . $balance_percentage . '% After Website Launch.</div>
                                        <div class="amount-line"><strong>STATUS :</strong> ADVANCE PAID (' . $advance_percentage . '%)</div>
                                    </td>
                                    <td width="40%" valign="top">
                                        <div class="section-title" style="margin-bottom:4px;">' . (! empty($note_text) ? 'NOTE:' : '&nbsp;') . '</div>
                                        <div class="note-box">' . (! empty($note_text) ? nl2br(htmlspecialchars($note_text)) : '&nbsp;') . '</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ITEMS TABLE HEADER -->
                    <tr>
                        <td style="padding:0 18px 0 18px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="13" class="items-head" style="border-collapse:collapse;">
                                <tr>
                                    <td width="46%" align="left">SERVICE DESCRIPTION</td>
                                    <td width="18%" align="center">DURATION</td>
                                    <td width="18%" align="center">PRICE</td>
                                    <td width="18%" align="right">TOTAL</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ITEMS ROWS -->
                    <tr>
                        <td style="padding:18px 28px 8px 28px;">' . $items_html . '</td>
                    </tr>

                    <!-- OFFER + SUMMARY -->
                    <tr>
                        <td style="padding:0 28px 20px 28px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="62%" valign="top" style="padding-right:18px;">
                                        <div style="font-size:13px; color:#0f172a; line-height:1.6; margin-bottom:10px;"><strong>Offer :</strong> Including Yearly Maintenance For 1st Year and 100% website uptime guaranteed once renewed yearly.</div>
                                        <div class="note-box"><strong>NOTE:</strong> We prioritize customer satisfaction. Our team of passionate Developers, graphic designers and digital marketers are dedicated to deliver exceptional service and ensuring your growth and success among competitors in the crowded online market.</div>
                                    </td>
                                    <td width="38%" valign="top">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="4" class="summary">
                                            <tr>
                                                <td width="60%" align="right"><strong>SUB TOTAL</strong></td>
                                                <td width="40%" align="right"><strong>' . app_format_money($invoice->subtotal, $invoice->currency_name) . '</strong></td>
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>TOTAL</strong></td>
                                                <td align="right"><strong>' . app_format_money($actual_price, $invoice->currency_name) . '</strong></td>
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>ADVANCED</strong></td>
                                                <td align="right"><strong>' . app_format_money($advance_amount, $invoice->currency_name) . '</strong></td>
                                            </tr>
                                            <tr>
                                                <td align="right" style="color:#d9534f;"><strong>BALANCE</strong></td>
                                                <td align="right" style="color:#d9534f;"><strong>' . app_format_money($balance_amount, $invoice->currency_name) . '</strong></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding:0 18px 18px 18px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="14" style="background:#0f3b4a; border-radius:8px;">
                                <tr>
                                    <td align="center" class="footer">' . $company_name . ' &nbsp;|&nbsp; ' . $company_phone . ' &nbsp;|&nbsp; ' . $company_email . '</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</div>';

$pdf->writeHTML($html, true, false, true, false, '');
