<?php defined('BASEPATH') or exit('No direct script access allowed');

$s = $submission;
$companyName = get_option('companyname') ?: 'Nooryak CRM';
?>
<div style="font-family:'Segoe UI',Helvetica,Arial,sans-serif;background:#f7f7f8;padding:32px 16px;margin:0;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.06);">
        <tr>
            <td style="background:#F15B25;padding:24px 28px;">
                <h1 style="margin:0;font-size:22px;color:#ffffff;font-weight:700;">New Demo Request</h1>
                <p style="margin:8px 0 0;font-size:14px;color:rgba(255,255,255,.9);"><?= e($companyName); ?> — Book a Demo form</p>
            </td>
        </tr>
        <tr>
            <td style="padding:28px;">
                <p style="margin:0 0 20px;font-size:15px;color:#555;line-height:1.5;">
                    A visitor submitted a demo request. Details are below.
                </p>
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:14px;">
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;width:38%;">Full Name</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><?= e($s['full_name']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;">Business Email</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><a href="mailto:<?= e($s['email']); ?>" style="color:#F15B25;"><?= e($s['email']); ?></a></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;">Phone</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><?= e($s['phone']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;">Company</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><?= e($s['company']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;">Number of Users</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><?= e($s['num_users']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;">Industry</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><?= e($s['industry']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;vertical-align:top;">Requirements</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;line-height:1.5;"><?= nl2br(e($s['requirements'])); ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;">Submitted</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><?= e($s['submitted_at']); ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px 12px;border:1px solid #eee;background:#fafafa;font-weight:600;color:#1A1A2E;">IP Address</td>
                        <td style="padding:10px 12px;border:1px solid #eee;color:#333;"><?= e($s['ip_address']); ?></td>
                    </tr>
                </table>
                <p style="margin:24px 0 0;font-size:12px;color:#888;">Reply directly to this email to reach the prospect.</p>
            </td>
        </tr>
    </table>
</div>
