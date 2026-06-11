<?php defined('BASEPATH') or exit('No direct script access allowed');

$page_title = _l('terms_and_conditions');
$intro = 'Please read these Terms of Service carefully before using Nooryak CRM. These terms constitute a legally binding agreement between you and Nooryak Technologies.';
$content_partial = $this->load->view('themes/' . active_clients_theme() . '/views/legal/terms_content', [], true);

$this->load->view('themes/' . active_clients_theme() . '/views/legal/_document_wrapper', [
    'page_title'          => $page_title,
    'intro'               => $intro,
    'content_partial'     => $content_partial,
    'use_custom_content'  => !empty($use_custom_content),
    'custom_content'      => $custom_content ?? '',
    'last_updated'        => $last_updated ?? date('F j, Y'),
    'back_url'            => $back_url ?? site_url('authentication/register'),
    'other_label'         => 'Privacy Policy',
    'other_url'           => privacy_policy_url(),
]);
