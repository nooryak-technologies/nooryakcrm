<?php defined('BASEPATH') or exit('No direct script access allowed');

// ── Data preparation ──────────────────────────────────────────────────────────
// All company fields are read from per-tenant options so every subdomain
// automatically uses its own details set in Settings → Company.
$company_name      = get_option('invoice_company_name') ?: get_option('companyname') ?: 'Nooryak Technologies';
$company_phone     = get_option('invoice_company_phonenumber') ?: get_option('companyphonenumber') ?: '+91-6374913298';
$company_email     = get_option('companyemail') ?: 'admin@nooryak.com';
$company_address   = get_option('invoice_company_address') ?: '';
$company_city      = get_option('invoice_company_city') ?: '';
$company_state     = get_option('company_state') ?: '';
$company_zip       = get_option('invoice_company_postal_code') ?: '';
$company_country   = get_option('invoice_company_country_code') ?: '';
$company_vat       = get_option('company_vat') ?: '';   // GSTIN / VAT number
$company_website   = get_option('companywebsite') ?: 'www.nooryakcrm.com';
if ($company_website === 'www.nooryak.com') {
    $company_website = 'www.nooryakcrm.com';
}

$invoice_no   = $invoice_number;
$invoice_date = _d($invoice->date);

// Client name
$client_name = $invoice->client->company
    ? $invoice->client->company
    : trim($invoice->client->firstname . ' ' . $invoice->client->lastname);

// Client full address
$client_address = '';
if (!empty($invoice->client->address)) $client_address .= $invoice->client->address;
if (!empty($invoice->client->city))    $client_address .= ($client_address ? ', ' : '') . $invoice->client->city;
if (!empty($invoice->client->state))   $client_address .= ($client_address ? ', ' : '') . $invoice->client->state;
if (!empty($invoice->client->country)) {
    $country_name = get_country($invoice->client->country)->short_name ?? '';
    if ($country_name) $client_address .= ($client_address ? ', ' : '') . $country_name;
}
if (!empty($invoice->client->zip)) $client_address .= ($client_address ? ' ' : '') . $invoice->client->zip;

// Prices
$subtotal   = (float) $invoice->subtotal;
$total      = (float) $invoice->total;
$total_tax  = (float) ($invoice->total_tax ?? 0);
$advance    = (float) ($invoice->advance ?? 0);
$balance    = $total - $advance;

$advance_percentage = ($total > 0 && $advance > 0) ? round(($advance / $total) * 100) : 0;
$balance_percentage = 100 - $advance_percentage;

$currency_symbol = $invoice->currency_name ?? get_base_currency()->symbol ?? '&#8377;';
if (strtoupper(trim((string) $currency_symbol)) === 'INR') {
    $currency_symbol = '&#8377;';
}

// Note / terms
$note_text = strip_tags(trim((string) $invoice->terms));
if ($note_text === '') {
    $note_text = 'We prioritize customer satisfaction. Our team of passionate Developers, graphic designers and digital marketers are dedicated to deliver exceptional service and ensuring your growth and success among competitors in the crowded online market.';
}

// Helper: format money
function _inv_money($amount, $symbol) {
    return $symbol . number_format((float)$amount, 2, '.', ',');
}

// Logo
$logo_html = pdf_logo_url();
if ($logo_html == '') {
    // If the company name contains "Nooryak", fall back to the brand logo.
    // For other tenant subdomains, we do not force the Nooryak logo and let it fall back to text.
    if (stripos($company_name, 'Nooryak') !== false) {
        $fallback_logo = FCPATH . 'assets/images/NOORYAK-CRM-LOGO.png';
        if (file_exists($fallback_logo)) {
            $logo_html = '<img src="' . $fallback_logo . '" style="height:48px;" />';
        }
    }
}

// Build the dynamic company address block for the PDF header
$company_address_lines = [];
if ($company_address !== '') $company_address_lines[] = htmlspecialchars($company_address);
$city_state_zip = trim(implode(', ', array_filter([$company_city, $company_state])));
if ($company_zip !== '') $city_state_zip .= ($city_state_zip ? ' ' : '') . $company_zip;
if ($city_state_zip !== '') $company_address_lines[] = htmlspecialchars($city_state_zip);
if ($company_country !== '') $company_address_lines[] = htmlspecialchars($company_country);
$company_address_html = implode('<br>', $company_address_lines);

// ── Build items HTML ──────────────────────────────────────────────────────────
$items_html = '';
foreach ($invoice->items as $item) {
    $item_qty_raw     = (string)(is_object($item) ? ($item->qty ?? 1)               : ($item['qty'] ?? 1));
    $item_qty         = (float) $item_qty_raw;
    $item_qty_display = ($item_qty == floor($item_qty)) ? (int) $item_qty : $item_qty_raw;
    $item_rate        = (float)(is_object($item) ? ($item->rate ?? 0)              : ($item['rate'] ?? 0));
    $item_description = (string)(is_object($item) ? ($item->description ?? '')     : ($item['description'] ?? ''));
    $item_long_desc   = (string)(is_object($item) ? ($item->long_description ?? '') : ($item['long_description'] ?? ''));
    
    $item_duration_raw = trim((string)(is_object($item) ? ($item->duration ?? '') : ($item['duration'] ?? '')));
    if ($item_duration_raw !== '') {
        if (is_numeric($item_duration_raw)) {
            $item_duration = $item_duration_raw . ' ' . ($item_duration_raw == 1 ? 'Day' : 'Days');
        } else {
            $item_duration = $item_duration_raw;
        }
    } else {
        $item_duration = '';
    }
    
    $item_unit        = (string)(is_object($item) ? ($item->unit ?? '') : ($item['unit'] ?? ''));
    $item_total       = $item_qty * $item_rate;

    // Long description bullet lines
    $desc_bullets_html = '';
    if ($item_long_desc !== '') {
        $raw = preg_replace('/<br\s*\/?>/i', "\n", $item_long_desc);
        $raw = strip_tags($raw);
        foreach (preg_split('/\r\n|\r|\n/', $raw) as $ln) {
            $ln = trim(preg_replace('/^[\*\-\x{2022}]\s*/u', '', trim($ln)));
            if ($ln !== '') {
                $desc_bullets_html .= '<li>' . htmlspecialchars($ln) . '</li>';
            }
        }
    }

    $items_html .= '
    <tr>
        <td style="width:40%;">
            <div class="item-desc-title">' . htmlspecialchars($item_description) . '</div>'
            . ($desc_bullets_html ? '<ul class="item-bullets">' . $desc_bullets_html . '</ul>' : '') . '
        </td>
        <td class="col-qty" style="width:10%;">' . $item_qty_display . ($item_unit ? ' ' . htmlspecialchars($item_unit) : '') . '</td>
        <td class="col-duration" style="width:15%;">' . htmlspecialchars($item_duration) . '</td>
        <td class="col-rate" style="width:15%;">' . number_format($item_rate, 2) . '</td>
        <td class="col-total" style="width:20%;">' . number_format($item_total, 2) . '</td>
    </tr>';
}

// ── HTML ──────────────────────────────────────────────────────────────────────
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica", "Arial", sans-serif;
            color: #17171a;
            background-color: #ffffff;
        }
        
        .invoice-sheet {
            position: relative;
            width: 100%;
            background: #ffffff;
        }
        
        .curve-decor {
            position: absolute;
            top: -40px;
            right: -40px;
            width: 230px;
            height: 300px;
            background: #17a2b8;
            border-bottom-left-radius: 230px;
            z-index: -100;
        }
        
        .header-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: top;
        }
        
        .logo-block {
            text-align: left;
        }
        
        .logo-text-main {
            font-size: 22px;
            font-weight: 800;
            color: #17171a;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        
        .logo-text-sub {
            font-size: 10px;
            font-weight: 700;
            color: #17a2b8;
            letter-spacing: 1.5px;
            margin-top: 2px;
        }
        
        .header-right {
            text-align: right;
            padding-right: 10px;
        }
        
        .invoice-title {
            font-size: 54px;
            font-weight: 800;
            letter-spacing: 3px;
            color: #17171a;
            line-height: 1;
            margin: 0 0 8px 0;
        }
        
        .company-name-block {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.4;
            margin-bottom: 2px;
        }
        
        .company-details {
            font-size: 12.5px;
            color: #4a4a4a;
            line-height: 1.5;
        }
        
        .info-section {
            margin-top: 40px;
            width: 65%;
        }
        
        .client-info-line {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.4;
            margin-bottom: 12px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 4px 0;
            font-size: 13px;
            vertical-align: top;
        }
        
        .info-table td.label {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            width: 130px;
        }
        
        .info-table td.value {
            font-weight: normal;
        }
        
        .amount-strike {
            text-decoration: line-through;
            font-weight: 400;
            color: #8a8a8a;
        }
        
        .amount-final {
            font-weight: normal;
        }
        
        .amount-note {
            font-size: 11px;
            font-weight: 400;
            text-transform: none;
            color: #4a4a4a;
        }
        
        .balance-note {
            font-size: 12px;
            font-weight: 400;
            text-transform: none;
            color: #4a4a4a;
        }
        
        .status-paid {
            font-weight: normal;
            text-transform: uppercase;
            color: #0e8092;
        }
        
        .table-header-wrapper {
            background-color: #17a2b8;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 34px;
        }
        
        .items-header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-header-table th {
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 14px;
            text-align: left;
        }
        
        .items-header-table th.col-qty,
        .items-header-table th.col-duration,
        .items-header-table th.col-rate {
            text-align: center;
        }
        
        .items-header-table th.col-total {
            text-align: right;
        }
        
        .items-body-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-body-table td {
            padding: 12px 14px;
            font-size: 13px;
            vertical-align: top;
            border-bottom: 1px solid #eef4f5;
        }
        
        .items-body-table tr:nth-child(even) td {
            background-color: #f8fcfd;
        }
        
        .item-desc-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .item-bullets {
            margin: 0;
            padding: 0 0 0 15px;
            font-size: 11.5px;
            color: #4a4a4a;
            line-height: 1.75;
        }
        
        .col-qty, .col-duration, .col-rate {
            text-align: center;
            font-weight: 600;
        }
        
        .col-total {
            text-align: right;
            font-weight: 700;
        }
        
        .bottom-table {
            width: 100%;
            margin-top: 28px;
            border-collapse: collapse;
        }
        
        .bottom-table td {
            vertical-align: top;
        }
        
        .notes-col {
            font-size: 12px;
            line-height: 1.5;
            color: #4a4a4a;
            padding-right: 30px;
        }
        
        .notes-col .label {
            font-size: 13px;
            font-weight: 700;
            color: #17171a;
            margin-bottom: 3px;
        }
        
        .notes-col .block {
            margin-bottom: 16px;
        }
        
        .summary-col {
            width: 300px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 6px 0;
            font-size: 13px;
            font-weight: 700;
        }
        
        .summary-table td.amt {
            text-align: right;
        }
        
        .summary-table tr.advanced td {
            color: #1a7d1a;
        }
        
        .summary-table tr.balance td {
            font-size: 17px;
            color: #cc0000;
            padding-top: 12px;
            border-top: 1px solid #dcecee;
        }
        
        .footer-table {
            width: 100%;
            margin-top: 40px;
            background-color: #17a2b8;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .footer-table td {
            padding: 16px 20px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="invoice-sheet">
    <div class="curve-decor"></div>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <div class="logo-block">
                    ' . ($logo_html !== '' ? $logo_html : '
                    <div class="logo-text-main">' . htmlspecialchars($company_name) . '</div>
                    ' . (stripos($company_name, 'Nooryak') !== false ? '<div class="logo-text-sub">— TECHNOLOGIES</div>' : '') . '
                    ') . '
                </div>
            </td>
            <td style="width: 50%;">
                <div class="header-right">
                    <div class="invoice-title">INVOICE</div>
                    <div class="company-name-block">' . htmlspecialchars($company_name) . '</div>
                    <div class="company-details">
                        ' . ($company_address_html !== '' ? $company_address_html . '<br>' : '') . '
                        ' . ($company_vat !== '' ? 'GSTIN : ' . htmlspecialchars($company_vat) . '<br>' : '') . '
                        ' . ($company_phone !== '' ? 'Ph: ' . htmlspecialchars($company_phone) . ($company_website !== '' ? ' &nbsp;<strong>' . htmlspecialchars($company_website) . '</strong>' : '') : ($company_website !== '' ? '<strong>' . htmlspecialchars($company_website) . '</strong>' : '')) . '
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- CLIENT & INVOICE INFO -->
    <div class="info-section">
        <div class="client-info-line">CLIENT INFO: ' . htmlspecialchars($client_name) . '</div>

        <table class="info-table">
            <tr>
                <td class="label">INVOICE NO :</td>
                <td class="value">' . htmlspecialchars($invoice_no) . '</td>
            </tr>
            <tr>
                <td class="label">INVOICE DATE:</td>
                <td class="value">' . htmlspecialchars($invoice_date) . '</td>
            </tr>
            <tr>
                <td class="label">AMOUNT:</td>
                <td class="value">
                    <span class="amount-final">' . _inv_money($total, $currency_symbol) . '</span>
                    ' . ($total_tax > 0 ? '<span class="amount-note">(Including ' . _inv_money($total_tax, $currency_symbol) . ' GST)</span>' : '') . '
                </td>
            </tr>
            ' . ($advance > 0 ? '
            <tr>
                <td class="label">ADVANCE :</td>
                <td class="value">
                    <strong>' . _inv_money($advance, $currency_symbol) . ' (' . $advance_percentage . '%)</strong>
                    <span class="balance-note">- Balance ' . $balance_percentage . '%</span>
                </td>
            </tr>
            <tr>
                <td class="label">STATUS :</td>
                <td class="value"><span class="status-paid">ADVANCE PAID (' . $advance_percentage . '%)</span></td>
            </tr>' : '') . '
        </table>
    </div>

    <!-- ITEMS TABLE -->
    <div class="table-header-wrapper">
        <table class="items-header-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Service Description</th>
                    <th class="col-qty" style="width: 10%;">Qty</th>
                    <th class="col-duration" style="width: 15%;">Duration</th>
                    <th class="col-rate" style="width: 15%;">Rate</th>
                    <th class="col-total" style="width: 20%;">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <table class="items-body-table">
        <tbody>
            ' . $items_html . '
        </tbody>
    </table>

    <!-- BOTTOM SECTION -->
    <table class="bottom-table">
        <tr>
            <td style="width: 55%;">
                <div class="notes-col">
                    <div class="block">
                        <div class="label">Offer :</div>
                        Including Yearly Maintenance For 1<sup>st</sup> Year and 101% website uptime guaranteed once Renewed Yearly.
                    </div>
                    <div class="block">
                        <div class="label">NOTE:</div>
                        ' . strip_tags(nl2br($note_text)) . '
                    </div>
                </div>
            </td>
            <td style="width: 45%;">
                <div class="summary-col" style="float: right;">
                    <table class="summary-table">
                        <tr>
                            <td>SUB TOTAL</td>
                            <td class="amt">' . _inv_money($subtotal, $currency_symbol) . '</td>
                        </tr>
                        ' . ($total_tax > 0 ? '
                        <tr>
                            <td>GST</td>
                            <td class="amt">' . _inv_money($total_tax, $currency_symbol) . '</td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td class="amt">' . _inv_money($total, $currency_symbol) . '</td>
                        </tr>' : '') . '
                        ' . ($advance > 0 ? '
                        <tr class="advanced">
                            <td>ADVANCED PAID</td>
                            <td class="amt">' . _inv_money($advance, $currency_symbol) . '</td>
                        </tr>' : '') . '
                        <tr class="balance">
                            <td>BALANCE</td>
                            <td class="amt">' . _inv_money($balance, $currency_symbol) . '</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <table class="footer-table">
        <tr>
            <td style="width: 33.33%; text-align: left;">
                ' . ($company_website !== '' ? htmlspecialchars($company_website) : '') . '
            </td>
            <td style="width: 33.33%; text-align: center;">
                ' . ($company_phone !== '' ? htmlspecialchars($company_phone) : '') . '
            </td>
            <td style="width: 33.33%; text-align: right;">
                ' . ($company_email !== '' ? htmlspecialchars($company_email) : '') . '
            </td>
        </tr>
    </table>

</div>

</body>
</html>';

$pdf->writeHTML($html, true, false, true, false, '');

