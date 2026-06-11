<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$companyName = html_escape($company_name ?? 'CRM');
$loginUrl = $login_url ?? site_url('authentication/login');
$registerUrl = $register_url ?? site_url('authentication/register');
$heroImage = $hero_image ?? '';
$heroBg = $hero_bg ?? '';
$featureImage = $feature_image ?? '';
$featureShape = $feature_shape ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $companyName; ?></title>
    <style>
        :root {
            --bg: #07121f;
            --bg-soft: #0d1b2a;
            --card: rgba(8, 21, 36, 0.72);
            --card-strong: #0f2238;
            --line: rgba(255, 255, 255, 0.1);
            --text: #edf4ff;
            --muted: rgba(237, 244, 255, 0.72);
            --accent: #27c7a6;
            --accent-2: #f4b860;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background:
                radial-gradient(circle at top left, rgba(39, 199, 166, 0.24), transparent 30%),
                radial-gradient(circle at top right, rgba(244, 184, 96, 0.18), transparent 26%),
                linear-gradient(180deg, #07111d 0%, #091523 55%, #07111d 100%);
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .landing-shell {
            position: relative;
            overflow: hidden;
        }

        .landing-shell::before,
        .landing-shell::after {
            content: "";
            position: absolute;
            inset: auto;
            width: 28rem;
            height: 28rem;
            border-radius: 50%;
            filter: blur(24px);
            opacity: 0.16;
            pointer-events: none;
        }

        .landing-shell::before {
            top: -9rem;
            left: -6rem;
            background: var(--accent);
        }

        .landing-shell::after {
            bottom: -12rem;
            right: -8rem;
            background: var(--accent-2);
        }

        .landing-wrap {
            position: relative;
            z-index: 1;
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 40px;
        }

        .landing-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 10px 0 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(39, 199, 166, 0.95), rgba(20, 129, 230, 0.95));
            box-shadow: 0 16px 40px rgba(39, 199, 166, 0.24);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border-radius: 14px;
            border: 1px solid transparent;
            font-weight: 700;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            font-size: 0.95rem;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.1);
            color: var(--text);
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #1592ff);
            color: #05131f;
            box-shadow: 0 16px 40px rgba(21, 146, 255, 0.22);
            font-weight: 800;
        }

        .btn-primary:hover {
            box-shadow: 0 20px 50px rgba(21, 146, 255, 0.32);
        }

        .hero {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 28px;
            align-items: center;
            padding: 18px 0 40px;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.04);
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 18px;
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(2.6rem, 5vw, 4.9rem);
            line-height: 0.98;
            letter-spacing: -0.05em;
            max-width: 12ch;
        }

        .hero p {
            margin: 18px 0 28px;
            max-width: 56ch;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            max-width: 640px;
        }

        .stat-card {
            padding: 16px 18px;
            border-radius: 20px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(14px);
            transition: transform 0.3s ease, background 0.3s ease, border-color 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(39, 199, 166, 0.4);
        }

        .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.4;
        }

        .hero-visual {
            position: relative;
            min-height: 620px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-panel {
            position: relative;
            width: min(100%, 520px);
            padding: 24px;
            border-radius: 32px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.11), rgba(255, 255, 255, 0.04)),
                var(--card);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(18px);
        }

        .hero-panel img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 24px;
        }

        .floating-card {
            position: absolute;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(9, 18, 30, 0.84);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(14px);
        }

        .floating-card small {
            display: block;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .floating-card strong {
            font-size: 1.1rem;
        }

        .floating-top {
            top: 20px;
            right: -4px;
        }

        .floating-bottom {
            bottom: 16px;
            left: -10px;
        }

        .section {
            padding: 22px 0 18px;
        }

        .section-heading {
            max-width: 680px;
            margin-bottom: 24px;
        }

        .section-heading h2 {
            margin: 0 0 10px;
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1.05;
            letter-spacing: -0.04em;
            font-weight: 900;
            background: linear-gradient(135deg, var(--text) 0%, rgba(237, 244, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-heading p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .feature-card {
            padding: 22px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            min-height: 100%;
            transition: transform 0.3s ease, background 0.3s ease, border-color 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(39, 199, 166, 0.3);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            margin-bottom: 16px;
            background: linear-gradient(135deg, rgba(39, 199, 166, 0.22), rgba(21, 146, 255, 0.18));
            color: #8cf7df;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .feature-card h3 {
            margin: 0 0 10px;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.65;
            font-size: 0.96rem;
            flex-grow: 1;
        }

        .split-section {
            display: grid;
            grid-template-columns: 0.92fr 1.08fr;
            gap: 28px;
            align-items: center;
            padding: 34px 0 8px;
        }

        .media-wrap {
            position: relative;
        }

        .media-wrap .shape {
            position: absolute;
            inset: -22px -20px auto auto;
            width: min(60%, 280px);
            opacity: 0.7;
        }

        .media-wrap img {
            position: relative;
            z-index: 1;
            display: block;
            width: 100%;
            border-radius: 28px;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.32);
        }

        .steps {
            display: grid;
            gap: 14px;
            margin-top: 24px;
        }

        .step {
            display: flex;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 0.3s ease, background 0.3s ease, border-color 0.3s ease;
        }

        .step:hover {
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(39, 199, 166, 0.3);
        }

        .step-num {
            flex: 0 0 auto;
            width: 38px;
            height: 38px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: rgba(39, 199, 166, 0.16);
            color: #7ff1d6;
            font-weight: 800;
            font-size: 1rem;
        }

        .step h4 {
            margin: 0 0 4px;
            font-size: 1rem;
            font-weight: 700;
        }

        .step p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 0.92rem;
        }

        .testimonials-section {
            padding: 48px 0;
        }

        .testimonials-label {
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #ff6b9d;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .testimonial-card {
            padding: 28px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, background 0.3s ease, border-color 0.3s ease;
            position: relative;
        }

        .testimonial-card:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 12px;
            left: 20px;
            font-size: 3.5rem;
            color: rgba(39, 199, 166, 0.2);
            line-height: 0.8;
            font-family: Georgia, serif;
        }

        .testimonial-stars {
            display: flex;
            gap: 4px;
            margin-bottom: 16px;
            font-size: 1rem;
        }

        .testimonial-stars span {
            color: #ffc107;
        }

        .testimonial-text {
            margin-bottom: 24px;
            color: var(--muted);
            line-height: 1.8;
            font-size: 0.95rem;
            flex-grow: 1;
        }

        .testimonial-author {
            display: flex;
            gap: 14px;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .testimonial-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(39, 199, 166, 0.6), rgba(21, 146, 255, 0.6));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
            color: white;
        }

        .testimonial-info h4 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .testimonial-info p {
            margin: 2px 0 0;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .cta-band {
            margin-top: 28px;
            padding: 32px;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(39, 199, 166, 0.16), rgba(21, 146, 255, 0.14));
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 20px 60px rgba(39, 199, 166, 0.1);
        }

        .cta-band strong {
            font-size: 1.3rem;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .cta-band p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .footer-note {
            padding: 22px 0 0;
            color: rgba(237, 244, 255, 0.48);
            font-size: 0.92rem;
        }

        @media (max-width: 1100px) {
            .hero,
            .split-section {
                grid-template-columns: 1fr;
            }

            .hero-visual {
                min-height: auto;
                order: -1;
            }

            .feature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .testimonials-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .landing-wrap {
                width: min(100%, calc(100% - 20px));
                padding-top: 16px;
            }

            .landing-nav {
                align-items: flex-start;
                flex-direction: column;
            }

            .hero-stats,
            .feature-grid,
            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                max-width: none;
            }

            .hero-panel {
                padding: 16px;
            }

            .floating-card {
                max-width: 220px;
            }

            .floating-top {
                right: 8px;
            }

            .floating-bottom {
                left: 8px;
            }

            .cta-band {
                flex-direction: column;
                text-align: center;
            }

            .cta-band .nav-actions {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <main class="landing-shell">
        <div class="landing-wrap">
            <header class="landing-nav">
                <a class="brand" href="<?= site_url(); ?>">
                    <span class="brand-mark">C</span>
                    <span><?= $companyName; ?></span>
                </a>
                <div class="nav-actions">
                    <a class="btn btn-ghost" href="#features">Features</a>
                    <a class="btn btn-ghost" href="#workflow">Workflow</a>
                    <a class="btn btn-primary" href="<?= $registerUrl; ?>">Get Started</a>
                </div>
            </header>

            <section class="hero">
                <div class="hero-copy">
                    <div class="eyebrow">Modern CRM for teams that want clarity, speed, and control</div>
                    <h1>Run your customer pipeline with more confidence.</h1>
                    <p>
                        Keep leads, clients, billing, and follow-ups in one place. This landing experience now loads
                        with the proper local assets, so the page stays polished even on localhost.
                    </p>
                    <div class="hero-actions">
                        <a class="btn btn-primary" href="<?= $registerUrl; ?>">Create Account</a>
                        <a class="btn btn-ghost" href="<?= $loginUrl; ?>">Log In</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-card">
                            <span class="stat-value">01</span>
                            <div class="stat-label">Unified workspace for sales, support, and operations</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value">24/7</span>
                            <div class="stat-label">Client access through a branded self-service portal</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value">100%</span>
                            <div class="stat-label">Responsive landing page with absolute asset paths</div>
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-panel">
                        <img src="<?= $heroImage; ?>" alt="CRM dashboard preview">
                        <div class="floating-card floating-top">
                            <small>Active Pipeline</small>
                            <strong>128 open deals</strong>
                        </div>
                        <div class="floating-card floating-bottom">
                            <small>Weekly growth</small>
                            <strong>+18.4%</strong>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="section">
                <div class="section-heading">
                    <h2>Everything the landing page should have been showing.</h2>
                    <p>
                        The page now uses stable CRM imagery and a clean layout instead of broken relative links or
                        placeholder content.
                    </p>
                </div>

                <div class="feature-grid">
                    <article class="feature-card">
                        <div class="feature-icon">A</div>
                        <h3>Structured sales flow</h3>
                        <p>Track prospects, proposals, and renewals in a layout that stays readable on every screen size.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">B</div>
                        <h3>Client portal ready</h3>
                        <p>Let customers log in, view billing, and manage work without sending them into a broken template.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">C</div>
                        <h3>Fast onboarding</h3>
                        <p>Use a polished registration and onboarding flow with clear calls to action and no wasted space.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">D</div>
                        <h3>Reliable assets</h3>
                        <p>Images and supporting visuals are loaded from module URLs so the design no longer falls apart locally.</p>
                    </article>
                </div>
            </section>

            <section id="workflow" class="split-section">
                <div class="media-wrap">
                    <img src="<?= $featureImage; ?>" alt="Team working in CRM">
                    <img class="shape" src="<?= $featureShape; ?>" alt="Decorative shape">
                </div>

                <div>
                    <div class="section-heading">
                        <h2>Clear workflow. Cleaner home page.</h2>
                        <p>
                            The homepage now reads like a proper product intro instead of an unfinished placeholder.
                            It is designed to work as a local installation and still feel intentional.
                        </p>
                    </div>

                    <div class="steps">
                        <div class="step">
                            <div class="step-num">1</div>
                            <div>
                                <h4>Choose your package</h4>
                                <p>Present a simple entry point for new customers without confusing navigation or broken images.</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-num">2</div>
                            <div>
                                <h4>Create the account</h4>
                                <p>Keep the signup journey consistent with the rest of the CRM and the SaaS onboarding flow.</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-num">3</div>
                            <div>
                                <h4>Start managing clients</h4>
                                <p>Move from the landing page into the portal with a clean transition and working theme assets.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php if (!empty($packages)) : ?>
            <section id="pricing" class="section">
                <div class="section-heading">
                    <div class="eyebrow">Pricing</div>
                    <h2>Choose a plan that suits you. Try for free.</h2>
                    <p>
                        Plans below are loaded from the live SaaS package records, so pricing and package features
                        stay in sync with the admin configuration.
                    </p>
                </div>

                <div class="feature-grid pricing-grid">
                    <?php foreach ($packages as $package) :
                        $custom_repeat = !empty($package->metadata->invoice) && ($package->metadata->invoice->recurring ?? '') === 'custom';
                        $interval = $custom_repeat ? (int)($package->metadata->invoice->repeat_every_custom ?? 1) : (int)($package->metadata->invoice->recurring ?? 1);
                        $interval_label = $interval > 1 ? $interval . ' Months' : 'Month';

                        // Use custom pricing_feature_lines if set, otherwise build from quota (show only unlimited/non-zero)
                        $limit_lines = [];
                        $custom_feature_lines = trim($package->metadata->pricing_feature_lines ?? '');

                        if (!empty($custom_feature_lines)) {
                            // Use admin-defined feature lines (one per line)
                            foreach (explode("\n", $custom_feature_lines) as $line) {
                                $line = trim($line);
                                if ($line !== '') {
                                    $limit_lines[] = $line;
                                }
                            }
                        } else {
                            // Fallback: only show unlimited items (skip 0 values)
                            if (!empty($package->metadata->limitations) && is_object($package->metadata->limitations)) {
                                foreach ((array) $package->metadata->limitations as $feature => $limit) {
                                    if ($limit === '' || $feature === 'storage') {
                                        continue;
                                    }
                                    if ((int) $limit === -1) {
                                        $limit_lines[] = 'Unlimited ' . ucwords(str_replace('_', ' ', $feature));
                                    } elseif ((int) $limit > 0) {
                                        $limit_lines[] = $limit . ' ' . ucwords(str_replace('_', ' ', $feature));
                                    }
                                }
                            }

                            $storage_limit = (int)($package->metadata->storage_limit->size ?? 0);
                            if ($storage_limit !== 0) {
                                $limit_lines[] = $storage_limit === -1 ? 'Unlimited Storage' : $storage_limit . ' ' . strtoupper($package->metadata->storage_limit->unit ?? 'MB') . ' Storage';
                            }
                        }
                        ?>
                    <article class="feature-card pricing-card <?= !empty($package->is_default) ? 'pricing-card-featured' : ''; ?>">
                        <div class="pricing-badge"><?= e($package->name); ?></div>
                        <h3><?= e($package->name); ?></h3>
                        <p class="pricing-value"><?= app_format_money($package->price, get_base_currency()); ?> <span>/ <?= e($interval_label); ?></span></p>
                        <p class="pricing-summary"><?= e($package->description ? strip_tags($package->description) : ''); ?></p>

                        <div class="pricing-features">
                            <?php foreach ($limit_lines as $line) : ?>
                            <div><?= e($line); ?></div>
                            <?php endforeach; ?>
                        </div>

                        <a class="btn btn-primary" href="<?= site_url('authentication/register?' . perfex_saas_route_id_prefix('plan') . '=' . $package->slug); ?>">
                            <?= $package->trial_period > 0 ? 'Try for free' : 'Sign up now'; ?>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <section class="testimonials-section">
                <div class="section-heading">
                    <span class="testimonials-label">Testimonials</span>
                    <h2>What Our Users Say</h2>
                    <p>
                        Hear from teams across industries who've transformed their customer relationships with our CRM solution.
                    </p>
                </div>

                <div class="testimonials-grid">
                    <article class="testimonial-card">
                        <div class="testimonial-stars">
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                        </div>
                        <p class="testimonial-text">
                            "This CRM has completely transformed how we manage our customer relationships. The centralized data, automated workflows, and personalized targeting have significantly improved our sales and marketing efforts. Highly recommended!"
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">JD</div>
                            <div class="testimonial-info">
                                <h4>John Doe</h4>
                                <p>CEO of Company</p>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="testimonial-stars">
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                        </div>
                        <p class="testimonial-text">
                            "We've seen a 35% increase in deal closure rate since implementing this system. The intuitive interface makes it easy for our team to adopt, and the reporting features give us the insights we need to make better decisions."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">SJ</div>
                            <div class="testimonial-info">
                                <h4>Sarah Johnson</h4>
                                <p>Sales Director</p>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="testimonial-stars">
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                        </div>
                        <p class="testimonial-text">
                            "Outstanding customer support and a robust platform that grows with our business. The automation features alone have freed up 15 hours per week for our team. Best investment we've made this year!"
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">MM</div>
                            <div class="testimonial-info">
                                <h4>Michael Martinez</h4>
                                <p>Operations Manager</p>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="testimonial-stars">
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                        </div>
                        <p class="testimonial-text">
                            "The client portal functionality is exceptional. Our customers love the transparency, and it's reduced our support tickets by 40%. We couldn't imagine running our business without it now."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">EC</div>
                            <div class="testimonial-info">
                                <h4>Emma Chen</h4>
                                <p>Client Success Lead</p>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="testimonial-stars">
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                        </div>
                        <p class="testimonial-text">
                            "Seamless integration with our existing tools and rock-solid reliability. The team is always innovating and listening to feedback. This is the kind of vendor partnership we value."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">RP</div>
                            <div class="testimonial-info">
                                <h4>Robert Park</h4>
                                <p>VP of Technology</p>
                            </div>
                        </div>
                    </article>

                    <article class="testimonial-card">
                        <div class="testimonial-stars">
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                            <span>★</span>
                        </div>
                        <p class="testimonial-text">
                            "The ROI has been impressive. From day one, the platform helped us better track our pipeline and identify bottlenecks. Our conversion rates have improved by 28%, and our team couldn't be happier."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">LW</div>
                            <div class="testimonial-info">
                                <h4>Lisa Wilson</h4>
                                <p>Business Development Manager</p>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <section class="cta-band">
                <div>
                    <strong style="font-size: 1.25rem;">Ready to keep the page this clean?</strong>
                    <p>Use the same polished structure for registration, onboarding, and the public homepage.</p>
                </div>
                <div class="nav-actions">
                    <a class="btn btn-ghost" href="#features">Explore features</a>
                    <a class="btn btn-primary" href="<?= $registerUrl; ?>">Start now</a>
                </div>
            </section>

            <div class="footer-note">
                <?= $companyName; ?> CRM landing page.
            </div>
        </div>
    </main>
</body>
</html>