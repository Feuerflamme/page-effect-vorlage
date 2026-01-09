<?php

/**
 * The template for displaying the footer
 *
 */
?>


</main><!-- .site-main -->

<footer class="footer">
    <div class="container top">
        <div class="logo">
            <a href="<?php echo esc_url(home_url('/')); ?>"
                aria-label="Zur Startseite von <?php echo esc_attr(get_bloginfo('name')); ?>"
                title="Zurück zur Startseite">
                <?php $th_logo = get_field('th_logo', 'option'); ?>
                <?php if ($th_logo) : ?>
                <!-- Footer Logo -->
                <img src="<?php echo esc_url($th_logo['url']); ?>"
                    alt="<?php echo !empty($th_logo['alt']) ? esc_attr($th_logo['alt']) : esc_attr(get_bloginfo('name') . ' Logo'); ?>"
                    width="200" height="60" loading="lazy" decoding="async" />
                <?php else : ?>
                <span class="sr-only">Logo nicht verfügbar</span>
                <?php endif; ?>
            </a>

        </div>
        <address class="address-wrapper">
            <div class="address"><?php the_field('adresse', 'option'); ?></div>
            <?php $phone = get_field('phone', 'option'); ?>
            <a class="phone" href="tel:+49<?php echo esc_attr($phone); ?>">
                +49 (0) <?php echo esc_html($phone); ?>
            </a>
            <?php $email = get_field('email', 'option'); ?>
            <a class="mail" href="mailto:<?php echo esc_attr($email); ?>">
                <?php echo esc_html($email); ?>
            </a>
        </address>
        <div class="col-right">
            <div class="some-wrapper">

                <?php if ( have_rows( 'some', 'option' ) ) : ?>
                <?php while ( have_rows( 'some', 'option' ) ) : the_row(); ?>
                <?php $link = get_sub_field( 'profil_link' ); ?>
                <?php $plattform = get_sub_field( 'plattform_auswahlen' ); ?>
                <?php if ( $link && $plattform ) : ?>

                <a class="some <?php echo esc_html($plattform); ?>" aria-hidden="true"
                    href="<?php echo esc_url($link); ?>">
                    <i class="icon icon-<?php echo esc_html($plattform); ?>" aria-hidden="true"></i>
                </a>
                <?php endif; ?>

                <?php endwhile; ?>
            </div>

            <p class="copyright">© <?php the_field('copyright', 'option'); ?></p>
            <?php else : ?>
            <?php // No rows found ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="container bottom-bar">
        <div class="wrapper">
            <?php $impressum = get_field('impressum', 'option'); ?>
            <?php if ($impressum) : ?>
            <a href="<?php echo esc_url($impressum); ?>">Impressum</a>
            <?php else : ?>
            <a class="legal" href="/impressum">Impressum</a>
            <?php endif; ?>
            <?php $wp_privacy_url = get_privacy_policy_url(); ?>
            <?php if ($wp_privacy_url) : ?>
            <a class="privacy legal" href="<?php echo esc_url($wp_privacy_url); ?>">Datenschutz</a>
            <?php else: ?>
            <a class="privacy legal" href="/datenschutz">Datenschutz</a>
            <?php endif; ?>
        </div>
    </div>


</footer>

<?php wp_footer(); ?>

</body>

</html>