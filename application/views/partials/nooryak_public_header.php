<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<header class="header nooryak-public-header">
    <div class="navbar-area sticky nooryak-nav">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand ny-brand" href="<?= e($home_url); ?>">
                            <span class="ny-brand-icon ny-brand-icon--lg">
                                <img src="<?= e($logo_url); ?>" alt="<?= e($company_name); ?>" width="auto" height="42" loading="eager" />
                            </span>
                        </a>
                        <a class="theme-btn nooryak-public-header-cta-mobile d-lg-none" href="<?= e($register_url); ?>">Start Free Trial</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#nooryakPublicNavbar" aria-controls="nooryakPublicNavbar"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse sub-menu-bar" id="nooryakPublicNavbar">
                            <ul id="nav" class="navbar-nav mx-lg-auto align-items-lg-center">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= e($home_url); ?>#features">Features</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= e($home_url); ?>#pricing">Pricing</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= e($home_url); ?>#integrations">Integrations</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navCompany" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Company</a>
                                    <div class="dropdown-menu" aria-labelledby="navCompany">
                                        <a class="dropdown-item" href="<?= e($home_url); ?>">About us</a>
                                        <a class="dropdown-item" href="<?= e($home_url); ?>">Careers</a>
                                        <a class="dropdown-item" href="<?= e($home_url); ?>">Contact</a>
                                    </div>
                                </li>
                            </ul>
                            <ul class="navbar-nav ml-lg-auto align-items-lg-center">
                                <li class="nav-item d-none d-lg-block">
                                    <a class="nav-link font-weight-bold" href="<?= e($login_url); ?>">Login</a>
                                </li>
                                <li class="nav-item pl-lg-2 d-none d-lg-block">
                                    <a class="theme-btn d-inline-block text-center ny-free-trial-btn" href="<?= e($register_url); ?>">Start Free Trial</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
