<?php defined('BASEPATH') or exit('No direct script access allowed');

// ── Data preparation ──────────────────────────────────────────────────────────
$company_name  = get_option('companyname') ?: 'NOORYAK TECHNOLOGIES';
$company_phone = get_option('companyphonenumber') ?: '+91-6374913298';
$company_email = get_option('companyemail') ?: 'admin@nooryak.com';

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
$logo_path = 'C:/xampp/htdocs/crm/assets/images/nooryak_logo.jpeg';
if (!file_exists($logo_path)) $logo_path = FCPATH . 'assets/images/nooryak_logo.jpeg';
$logo_html = file_exists($logo_path) ? '<img src="' . $logo_path . '" style="height:80px;" />' : '';

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

    // Tax
    $tax_display = '';
    if (is_object($item)) {
        $taxname = $item->taxname ?? '';
        $taxrate = (float)($item->taxrate ?? 0);
        if ($taxname && $taxrate > 0) $tax_display = htmlspecialchars($taxname) . '|' . number_format($taxrate, 2) . ' ' . number_format($taxrate, 2) . '%';
    } else {
        $taxes = $item['taxes'] ?? [];
        if (is_array($taxes)) {
            $tp = [];
            foreach ($taxes as $t) {
                $tn = is_object($t) ? ($t->taxname ?? '') : ($t['taxname'] ?? '');
                $tr = is_object($t) ? (float)($t->taxrate ?? 0) : (float)($t['taxrate'] ?? 0);
                if ($tn && $tr > 0) $tp[] = htmlspecialchars($tn) . '|' . number_format($tr, 2) . ' ' . number_format($tr, 2) . '%';
            }
            $tax_display = implode(', ', $tp);
        }
    }

    // Long description bullet lines
    $desc_lines_html = '';
    if ($item_long_desc !== '') {
        $raw = preg_replace('/<br\s*\/?>/i', "\n", $item_long_desc);
        $raw = strip_tags($raw);
        foreach (preg_split('/\r\n|\r|\n/', $raw) as $ln) {
            $ln = trim(preg_replace('/^[\*\-\x{2022}]\s*/u', '', trim($ln)));
            if ($ln !== '') $desc_lines_html .= '&#8226; ' . htmlspecialchars($ln) . '<br>';
        }
    }

    $items_html .= '
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            <td style="width:40%;padding:8px 12px;vertical-align:top;"><span style="font-size:13px;font-weight:700;color:#000000;padding-left:5px;">' . htmlspecialchars($item_description) . '</span>'
                . ($desc_lines_html ? '<div style="height:2px;font-size:2px;line-height:2px;"></div><span style="font-size:12px;color:#000000;line-height:1.6;">' . $desc_lines_html . '</span>' : '') . '</td>
            <td style="width:10%;padding:8px 6px;font-size:13px;font-weight:600;color:#000000;text-align:center;vertical-align:top;">' . $item_qty_display . ($item_unit ? ' ' . htmlspecialchars($item_unit) : '') . '</td>
            <td style="width:15%;padding:8px 6px;font-size:13px;font-weight:600;color:#000000;text-align:center;vertical-align:top;">' . htmlspecialchars($item_duration) . '</td>
            <td style="width:15%;padding:8px 6px;font-size:13px;font-weight:600;color:#000000;text-align:center;vertical-align:top;">' . number_format($item_rate, 2) . '</td>
            <td style="width:20%;padding:8px 12px;font-size:13px;font-weight:700;color:#000000;text-align:right;vertical-align:top;">' . number_format($item_total, 2) . '</td>
        </tr>
    </table>';
}

// ── HTML ──────────────────────────────────────────────────────────────────────
$html = '
<style>
body{font-family:"Arial","Helvetica",sans-serif;color:#000000;margin:0;padding:0;}
.page-container{background:#ffffff;position:relative;padding:0;margin:0;}
.curve-design{position:absolute;top:0;right:0;width:190px;height:280px;background:#17a2b8;border-bottom-left-radius:190px;z-index:1;}
</style>

<div class="page-container">
<div class="curve-design"></div>

<!-- HEADER & CLIENT INFO -->
<table style="width:100%;border-collapse:collapse;position:relative;z-index:2;">
    <tr>
        <td style="width:55%;vertical-align:top;padding:28px 0 0 40px;">
            ' . $logo_html . '
            
            <div style="height:30px;"></div>
            
            <div style="font-size:11px;color:#000000;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 3px 0;"><strong>CLIENT INFO:</strong></div>
            <div style="font-size:22px;font-weight:700;color:#000000;line-height:1;margin:0;padding:0;">' . htmlspecialchars($client_name) . '</div>
            ' . (!empty($client_address) ? '<div style="font-size:12px;color:#000000;line-height:1.2;margin:0;padding:0;">' . htmlspecialchars($client_address) . '</div>' : '') . '
            
            <div style="height:15px;"></div>
            
          <table style="width:100%; border-collapse:collapse;">
    <tr>
        <td style="padding:4px 0; width:150px; vertical-align:top;">
            <strong style="font-size:14px; font-weight:800; color:#000000; text-transform:uppercase; letter-spacing:0.5px;">INVOICE NO :</strong>
        </td>
        <td style="font-size:14px; font-weight:600; color:#000000; padding:4px 0; vertical-align:top;">
            ' . htmlspecialchars($invoice_no) . '
        </td>
    </tr>
    <tr>
        <td style="padding:4px 0; vertical-align:top;">
            <strong style="font-size:14px; font-weight:800; color:#000000; text-transform:uppercase; letter-spacing:0.5px;">INVOICE DATE:</strong>
        </td>
        <td style="font-size:14px; font-weight:600; color:#000000; padding:4px 0; vertical-align:top;">
            ' . htmlspecialchars($invoice_date) . '
        </td>
    </tr>
    <tr>
        <td style="padding:4px 0; vertical-align:top;">
            <strong style="font-size:14px; font-weight:800; color:#000000; text-transform:uppercase; letter-spacing:0.5px;">TOTAL AMOUNT:</strong>
        </td>
        <td style="font-size:14px; font-weight:800; color:#000000; padding:4px 0; vertical-align:top;">
            ' . _inv_money($total, $currency_symbol) . ' 
            ' . ($total_tax > 0 ? '<span style="font-size:11px; font-weight:400; text-transform:none; color:#000000;">(Including ' . _inv_money($total_tax, $currency_symbol) . ' GST)</span>' : '') . '
        </td>
    </tr>'
    . ($advance > 0 ? '
    <tr>
        <td style="padding:4px 0; vertical-align:top;">
            <strong style="font-size:14px; font-weight:800; color:#000000; text-transform:uppercase; letter-spacing:0.5px;">ADVANCE :</strong>
        </td>
        <td style="font-size:14px; font-weight:700; color:#000000; padding:4px 0; vertical-align:top;">
            ' . _inv_money($advance, $currency_symbol) . ' (' . $advance_percentage . '%) 
            <span style="font-size:12px; font-weight:400; text-transform:none; color:#000000;">- Balance ' . $balance_percentage . '% </span>
        </td>
    </tr>
    <tr>
        <td style="padding:4px 0; vertical-align:top;">
            <strong style="font-size:14px; font-weight:800; color:#000000; text-transform:uppercase; letter-spacing:0.5px;">STATUS :</strong>
        </td>
        <td style="padding:4px 0; vertical-align:top;">
            <span style="font-size:14px; font-weight:800; color:#000000; text-transform:uppercase;">ADVANCE PAID (' . $advance_percentage . '%)</span>
        </td>
    </tr>' : '') . '
</table>
        </td>
        <td style="width:45%;vertical-align:top;text-align:right;padding:28px 40px 0 0;">
            <div style="font-size:62px;font-weight:700;color:#000000;letter-spacing:3px;line-height:1;margin:0;">INVOICE</div>
            <div style="font-size:13px;font-weight:700;color:#000000;margin:10px 0 2px 0;text-transform:uppercase;line-height:1.4;"><strong>NOORYAK TECHNOLOGIES</strong><br><strong>DEVELOPMENT AGENCY</strong></div>
            <div style="font-size:13px;color:#000000;line-height:1.5;">
                
                Floor 1, Door, Shafi Tower, Khana Bagh Street,<br>
                Triplicane, Chennai, Tamil Nadu 600005<br>
                GSTIN : 33FMFPM6147A1ZB<br>
                Ph: ' . htmlspecialchars($company_phone) . ' &nbsp;<strong>www.nooryak.com</strong>
            </div>
        </td>
    </tr>
</table>

<!-- GAP BEFORE SERVICE DESCRIPTION -->
<div style="height:30px;"></div>

<!-- ITEMS TABLE HEADER -->
<div style="position:relative;width:100%;">
    <table style="background-color:#17a2b8;width:100%;padding: 15px;border-radius:10px;position:relative;z-index:1;">
    <tr>
        <td style="width:40%;padding:10px 12px;font-size:12px;font-weight:700;color:#ffffff;text-align:left;text-transform:uppercase;">SERVICE DESCRIPTION</td>
        <td style="width:10%;padding:10px 6px;font-size:12px;font-weight:700;color:#ffffff;text-align:center;text-transform:uppercase;white-space:nowrap;">QTY</td>
        <td style="width:15%;padding:10px 6px;font-size:12px;font-weight:700;color:#ffffff;text-align:center;text-transform:uppercase;white-space:nowrap;">DURATION</td>
        <td style="width:15%;padding:10px 6px;font-size:12px;font-weight:700;color:#ffffff;text-align:center;text-transform:uppercase;">RATE</td>
        <td style="width:20%;padding:10px 12px;font-size:12px;font-weight:700;color:#ffffff;text-align:right;text-transform:uppercase;">AMOUNT</td>
    </tr>
    </table>
    <!-- white curved cutout to create a rounded edge on the right -->
    <div style="position:absolute;right:0;top:0;width:70px;height:70px;background:#ffffff;border-bottom-left-radius:70px;z-index:2;"></div>
</div>

' . $items_html . '

<!-- SUMMARY -->
<table style="width:100%;border-collapse:collapse;margin-top:25px;">
    <tr>
        <td style="width:55%;vertical-align:top;padding:8px 12px 8px 40px;">
            <div style="font-size:13px;font-weight:700;margin-bottom:3px;">Offer :</div>
            <div style="font-size:12px;line-height:1.4;margin-bottom:15px;">Including Yearly Maintenance For 1<sup>st</sup> Year and 101% website uptime guaranteed once Renewed Yearly.</div>
            <div style="font-size:13px;font-weight:700;margin-bottom:3px;">NOTE:</div>
            <div style="font-size:12px;line-height:1.4;">' . strip_tags(nl2br($note_text)) . '</div>
        </td>
        <td style="width:45%;vertical-align:top;padding:8px 40px 8px 12px;">
            <table style="width:100%;border-collapse:collapse;margin-top:0;">
                <tr>
                    <td style="text-align:right;font-size:13px;font-weight:700;padding:5px 15px;"><strong>SUB TOTAL</strong></td>
                    <td style="text-align:right;font-size:13px;font-weight:700;padding:5px 0;">' . _inv_money($subtotal, $currency_symbol) . '</td>
                </tr>'
                . ($total_tax > 0 ? '
                <tr>
                    <td style="text-align:right;font-size:13px;font-weight:700;padding:5px 15px;"><strong>GST</strong></td>
                    <td style="text-align:right;font-size:13px;font-weight:700;padding:5px 0;">' . _inv_money($total_tax, $currency_symbol) . '</td>
                </tr>
                <tr>
                    <td style="text-align:right;font-size:13px;font-weight:700;padding:5px 15px;"><strong>TOTAL</strong></td>
                    <td style="text-align:right;font-size:13px;font-weight:700;padding:5px 0;">' . _inv_money($total, $currency_symbol) . '</td>
                </tr>' : '')
                . ($advance > 0 ? '
                <tr>
                    <td style="text-align:right;font-size:13px;font-weight:700;color:#008000;padding:5px 15px;"><strong>ADVANCE PAID</strong></td>
                    <td style="text-align:right;font-size:13px;font-weight:700;padding:5px 0;">' . _inv_money($advance, $currency_symbol) . '</td>
                </tr>' : '') . '
                <tr>
                    <td style="text-align:right;font-size:16px;font-weight:700;color:#cc0000;padding:10px 15px;"><strong>BALANCE</strong></td>
                    <td style="text-align:right;font-size:16px;font-weight:700;color:#cc0000;padding:10px 0;">' . _inv_money($balance, $currency_symbol) . '</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- FOOTER BAR -->
<div style="height:7px;"></div>
<div style="position:relative;width:100%;margin-top:40px;">
    <table style="background-color:#17a2b8;width:100%;padding:15px;border-radius:10px;position:relative;z-index:1;">
 <tr style="background-color: #17a2b8;">
    <td style="padding: 15px 20px; font-size: 14px; color: #ffffff; text-align: left; background-color: #17a2b8;">
        <strong style="font-weight: 800; letter-spacing: 0.5px;">www.nooryak.com</strong>
    </td>
    <td style="padding: 15px 6px; font-size: 14px; color: #ffffff; text-align: center; background-color: #17a2b8;">
        <strong style="font-weight: 800;">' . htmlspecialchars($company_phone) . '</strong>
    </td>
    <td style="padding: 15px 20px; font-size: 14px; color: #ffffff; text-align: right; background-color: #17a2b8;">
        <strong style="font-weight: 800;">' . htmlspecialchars($company_email) . '</strong>
    </td>
</tr>
    </table>
    <div style="position:absolute;right:0;top:0;width:70px;height:70px;background:#ffffff;border-bottom-left-radius:70px;z-index:2;"></div>
</div>

</div>';

$pdf->writeHTML($html, true, false, true, false, '');
