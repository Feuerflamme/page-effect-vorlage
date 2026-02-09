<?php

/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>

    <?php
  wp_body_open(); //do_action( 'wp_body_open' ); ex add_action( 'wp_body_open', 'google_tags_manager_body_open_scripts' );
  ?>

    <header class="header">
        <div class=container>
            <div class="header-main-group">
                <div class="header-logos">
                    <a class="logo-main" href="<?php echo esc_url(home_url('/')); ?>"
                        aria-label="Zur Startseite von <?php echo esc_attr(get_bloginfo('name')); ?>"
                        title="Zurück zur Startseite">
                        <?php $logo = get_field('logo', 'option'); ?>
                        <?php if ($logo) : ?>
                        <!-- Logo Image -->
                        <img src="<?php echo esc_url($logo['url']); ?>"
                            alt="<?php echo !empty($logo['alt']) ? esc_attr($logo['alt']) : esc_attr(get_bloginfo('name') . ' Logo'); ?>"
                            width="200" height="60" loading="lazy" decoding="async" />
                        <?php else : ?>
                        <span class="sr-only">Logo nicht verfügbar</span>
                        <?php endif; ?>
                    </a>
                  
                </div>
                <?php theme_display_menu('primary'); ?>
            </div>
            <!-- <div class="contact-wrapper">
                <?php // $email = get_field('email', 'option'); ?>
                <a class="mail" href="mailto:<?php // echo esc_attr($email); ?>">
                    <i class="icon icon-mail-light" aria-hidden="true"></i>
                    <?php // echo esc_html($email); ?>
                </a>
                <?php 
                // $phone = get_field('phone', 'option');
                 ?> 
                <a class="phone" href="tel:+49<?php //echo esc_attr($phone); ?>">
                    <i class="icon icon-phone-light" aria-hidden="true"></i>
                    +49 (0) <?php // echo esc_html($phone); ?>
                </a>
            </div> -->
            <!-- <div class="burger-navigation-helper" aria-hidden="true"></div> -->

            <button type="button" class="btn burger-navigation" aria-label="Menu öffnen" aria-expanded="false">
                <div class="wrapper">
                    <span class="burger-bars"></span>
                    <span class="burger-bars"></span>
                    <span class="burger-bars"></span>
                </div>
            </button>
        </div>
    </header>

    <!-- main  -->
    <main class="main"></main>