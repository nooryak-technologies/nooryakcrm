<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= e($locale); ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?= !empty($title) ? $title : 'Nooryak CRM | ALL-IN-ONE CRM PLATFORM'; ?></title>
	<meta name="description" content="Manage Leads. Close Deals. Grow Faster.">
	<meta property="og:title" content="<?= !empty($title) ? $title : 'Nooryak CRM | ALL-IN-ONE CRM PLATFORM'; ?>">
	<meta property="og:description" content="Manage Leads. Close Deals. Grow Faster.">
	<meta property="og:image" content="<?= base_url('assets/images/crm_sharingicon.png'); ?>">
	<meta property="og:image:secure_url" content="<?= base_url('assets/images/crm_sharingicon.png'); ?>">
	<meta property="og:image:type" content="image/png">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="Nooryak CRM">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?= !empty($title) ? $title : 'Nooryak CRM | ALL-IN-ONE CRM PLATFORM'; ?>">
	<meta name="twitter:description" content="Manage Leads. Close Deals. Grow Faster.">
	<meta name="twitter:image" content="<?= base_url('assets/images/crm_sharingicon.png'); ?>">
	<?= compile_theme_css(); ?>
	<script
		src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>">
	</script>
	<?php app_customers_head(); ?>
</head>

<body
	class="customers <?= strtolower($this->agent->browser()); ?><?= is_mobile() ? ' mobile' : ''; ?><?= isset($bodyclass) ? ' ' . $bodyclass : ''; ?>"
	<?= $isRTL == 'true' ? 'dir="rtl"' : ''; ?>>

	<?php hooks()->do_action('customers_after_body_start'); ?>