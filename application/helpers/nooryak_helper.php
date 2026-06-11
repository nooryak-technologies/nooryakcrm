<?php

defined('BASEPATH') or exit('No direct script access allowed');

function nooryak_public_nav_data()
{
    $softlandAssets = base_url('media/master/public/page_builder/pages/softland/assets/');

    return [
        'softland_assets' => $softlandAssets,
        'logo_url'        => $softlandAssets . 'landingpage/image/crm_logo.png',
        'home_url'        => site_url(),
        'login_url'       => site_url('authentication/login'),
        'register_url'    => site_url('authentication/register'),
        'company_name'    => get_option('companyname') ?: 'Nooryak CRM',
    ];
}

function nooryak_enqueue_public_nav_assets()
{
    hooks()->add_action('app_customers_head', '_nooryak_public_nav_head_assets');
    hooks()->add_action('app_customers_footer', '_nooryak_public_nav_footer_assets');
}

function _nooryak_public_nav_footer_assets()
{
    $d = nooryak_public_nav_data();
    echo '<script src="' . e($d['softland_assets']) . 'js/nooryak-script.js?v=' . time() . '"></script>' . "\n";
}

function _nooryak_public_nav_head_assets()
{
    $d = nooryak_public_nav_data();
    $navCss = base_url('assets/css/nooryak-public-nav.css?v=' . filemtime(FCPATH . 'assets/css/nooryak-public-nav.css'));

    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">' . "\n";
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">' . "\n";
    echo '<link rel="stylesheet" href="' . e($d['softland_assets']) . 'css/vendor.css">' . "\n";
    echo '<link rel="stylesheet" href="' . e($d['softland_assets']) . 'css/main.css">' . "\n";
    echo '<link rel="stylesheet" href="' . e($d['softland_assets']) . 'css/nooryak-style.css">' . "\n";
    echo '<link rel="stylesheet" href="' . e($navCss) . '">' . "\n";
}
