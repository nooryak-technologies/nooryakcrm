<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Proxy\Http\Request;
use Proxy\Proxy;
use Proxy\Config;


class Landing extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method to server the active landing page theme
     *
     * @return void
     */
    public function index()
    {
        $this->check_for_redirection();

        return $this->render_local_landing_page();
    }

    /**
     * Method to serve the proxied landing page.
     * Its essensial the proxied adddress runs on same domain to prevent CORS or whitelabeled for this installation domain.
     *
     * @return void
     */
    public function proxy()
    {

        $this->check_for_redirection();

        $url = get_option('perfex_saas_landing_page_url');

        if ($this->should_render_local_landing_page($url)) {
            return $this->render_local_landing_page();
        }

        if ($url && $url !== base_url()) {
            if (get_option('perfex_saas_landing_page_url_mode') === 'redirection') {
                redirect($url);
            }
        }

        //Config::set('url_mode', 2);
        //Config::set('encryption_key', md5(session_id()));

        session_write_close();

        $proxy = new Proxy();

        $proxy->getEventDispatcher()->addListener('request.sent', function ($event) {

            if ($event['response']->getStatusCode() != 200) {
                show_error("Bad status code!", $event['response']->getStatusCode(), "Landing");
            }
        });

        // load plugins
        $plugins = [
            'HeaderRewrite',
            'Stream',
            'Cookie',
            //'Proxify',
        ];
        foreach ($plugins as $plugin) {

            $plugin_class = $plugin . 'Plugin';

            if (class_exists('\\Proxy\\Plugin\\' . $plugin_class)) {

                // does the native plugin from php-proxy package with such name exist?
                $plugin_class = '\\Proxy\\Plugin\\' . $plugin_class;
            }

            $proxy->addSubscriber(new $plugin_class());
        }

        $request = Request::createFromGlobals();
        $request->get->clear();

        if (isset($_GET['q'])) {
            $url = url_decrypt($_GET['q']);
        }

        $response = $proxy->forward($request, $url);

        // send the response back to the client
        $response->send();
    }

    public function show_404()
    {
        // ensure not servable by proxy, then server 404
        show_404();
    }

    /**
     * Public JSON API: returns all active (non-private) packages
     * for use by the frontend landing page pricing section.
     *
     * URL: /perfex_saas/landing/api_packages
     *
     * @return void
     */
    public function api_packages()
    {
        header('Content-Type: application/json');

        $raw_packages = $this->perfex_saas_model->packages();
        $currency     = get_base_currency();
        $output       = [];

        foreach ($raw_packages as $pkg) {
            // Skip private packages
            if (!empty($pkg->is_private)) {
                continue;
            }

            // Determine billing interval label
            $meta          = $pkg->metadata ?? (object)[];
            $invoice_meta  = $meta->invoice ?? (object)[];
            $custom_repeat = ($invoice_meta->recurring ?? '') === 'custom';
            $interval      = $custom_repeat
                ? ($invoice_meta->repeat_every_custom ?? 1)
                : ($invoice_meta->recurring ?? 1);
            $interval_type = $custom_repeat
                ? (($invoice_meta->repeat_type_custom ?? 'month') . 's')
                : 'months';

            $interval_label = '';
            if (!empty($meta->is_liftetime_deal)) {
                $interval_label = 'Lifetime';
            } else {
                $interval_label = ($interval > 1 ? $interval . ' ' : '') . ucfirst(
                    $interval > 1 ? $interval_type : rtrim($interval_type, 's')
                );
            }

            // Determine storage label
            $storage_size  = (int)($meta->storage_limit->size ?? 0);
            $storage_unit  = $meta->storage_limit->unit ?? 'GB';
            $storage_label = $storage_size === -1 ? 'Unlimited' : $storage_size . ' ' . $storage_unit;

            // Decode modules (stored as JSON string)
            $modules = [];
            if (!empty($pkg->modules)) {
                $decoded = is_string($pkg->modules) ? json_decode($pkg->modules, true) : (array)$pkg->modules;
                if (is_array($decoded)) {
                    $modules = array_values($decoded);
                }
            }

            // Build limitations list
            $limitations = (array)($meta->limitations ?? []);

            // Build feature lines for pricing page
            $feature_lines = [];
            $custom_feature_lines = trim($meta->pricing_feature_lines ?? '');
            if (!empty($custom_feature_lines)) {
                foreach (explode("\n", $custom_feature_lines) as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        $feature_lines[] = $line;
                    }
                }
            } else {
                // Fallback: only show unlimited or non-zero quota items
                foreach ($limitations as $feature => $limit) {
                    if ($feature === 'storage' || $limit === '') continue;
                    if ((int)$limit === -1) {
                        $feature_lines[] = 'Unlimited ' . ucwords(str_replace('_', ' ', $feature));
                    } elseif ((int)$limit > 0) {
                        $feature_lines[] = $limit . ' ' . ucwords(str_replace('_', ' ', $feature));
                    }
                }
                if ($storage_size !== 0) {
                    $feature_lines[] = $storage_size === -1 ? 'Unlimited Storage' : $storage_size . ' ' . $storage_unit . ' Storage';
                }
            }

            $output[] = [
                'id'             => (int)$pkg->id,
                'name'           => $pkg->name,
                'slug'           => $pkg->slug,
                'price'          => (float)$pkg->price,
                'price_formatted'=> app_format_money($pkg->price, $currency),
                'currency_symbol'=> $currency->symbol ?? '₹',
                'interval_label' => $interval_label,
                'description'    => strip_tags($pkg->description ?? ''),
                'is_default'     => $pkg->is_default == '1',
                'trial_period'   => (int)($pkg->trial_period ?? 0),
                'modules'        => $modules,
                'limitations'    => $limitations,
                'storage_label'  => $storage_label,
                'feature_lines'  => $feature_lines,
                'user_limit'     => isset($limitations['users']) ? (int) $limitations['users'] : (isset($limitations['staff']) ? (int) $limitations['staff'] : -1),
                'register_url'   => site_url('authentication/register') . '?' . perfex_saas_route_id_prefix('plan') . '=' . $pkg->slug,
            ];
        }

        echo json_encode(['success' => true, 'packages' => $output]);
        exit;
    }

    /**
     * Check if there is an active session and redirect to the dashboard if loggedin.
     *
     * @return void
     */
    private function check_for_redirection()
    {
        if (get_option('perfex_saas_force_redirect_to_dashboard') == "1") {
            if (is_client_logged_in()) {
                return redirect('clients');
            }

            if (is_staff_logged_in()) {
                return redirect('admin');
            }
        }
    }

    /**
     * Render the local landing page theme when the configured URL points back to this installation.
     *
     * @return void
     */
    private function render_local_landing_page()
    {
        $data = [
            'company_name' => get_option('companyname') ?: 'CRM',
            'login_url' => site_url('authentication/login'),
            'register_url' => site_url('authentication/register'),
            'hero_image' => base_url('modules/perfex_saas/views/landingpage/default_themes/softland/assets/img/hero/hero-img.png'),
            'hero_bg' => base_url('modules/perfex_saas/views/landingpage/default_themes/softland/assets/img/hero/hero-bg.svg'),
            'feature_image' => base_url('modules/perfex_saas/views/landingpage/default_themes/softland/assets/img/why-use/why-use-img.jpg'),
            'feature_shape' => base_url('modules/perfex_saas/views/landingpage/default_themes/softland/assets/img/why-use/why-use-shape.png'),
            'packages' => $this->perfex_saas_model->packages(),
        ];

        echo $this->load->view(module_views_path(PERFEX_SAAS_MODULE_NAME, 'landingpage/local'), $data, true);
        exit;
    }

    /**
     * Determine whether the configured landing page URL points to the current installation.
     *
     * @param string $url
     * @return bool
     */
    private function should_render_local_landing_page($url)
    {
        if (empty($url)) {
            return true;
        }

        $normalizedUrl = rtrim($url, '/');
        $normalizedBaseUrl = rtrim(base_url(), '/');

        if ($normalizedUrl === $normalizedBaseUrl) {
            return true;
        }

        $urlParts = parse_url($normalizedUrl);
        $baseParts = parse_url($normalizedBaseUrl);

        if (empty($urlParts['host']) || empty($baseParts['host'])) {
            return false;
        }

        if (strcasecmp($urlParts['host'], $baseParts['host']) !== 0) {
            return false;
        }

        $urlPath = trim($urlParts['path'] ?? '', '/');
        $basePath = trim($baseParts['path'] ?? '', '/');

        return $urlPath === '' || $urlPath === $basePath;
    }
}
