<?php defined('BASEPATH') or exit('No direct script access allowed');

$page_title = _l('privacy_policy');
$intro = 'This Privacy Policy describes how Nooryak Technologies collects, uses, and protects your personal information when you use Nooryak CRM and our related services.';
$content_partial = $this->load->view('themes/' . active_clients_theme() . '/views/legal/privacy_content', [], true);

$this->load->view('themes/' . active_clients_theme() . '/views/legal/_document_wrapper', [
    'page_title'          => $page_title,
    'intro'               => $intro,
    'content_partial'     => $content_partial,
    'use_custom_content'  => !empty($use_custom_content),
    'custom_content'      => $custom_content ?? '',
    'last_updated'        => $last_updated ?? date('F j, Y'),
    'back_url'            => $back_url ?? site_url('authentication/register'),
    'other_label'         => 'Terms of Service',
    'other_url'           => terms_url(),
]);
