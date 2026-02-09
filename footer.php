<?php

/**
 * The template for displaying the footer
 *
 */
?>


<?php // Static Kontakt-Sektion before Footer Starts ?>
<section class="module kontakt">

    <div class="kontakt__background" aria-hidden="true">
        <img src="/wp-content/uploads/2026/02/2024_10_23_StMELF_FORSTSCHULE_TECHNIKER_1439-scaled.jpg"
            srcset="/wp-content/uploads/2026/02/2024_10_23_StMELF_FORSTSCHULE_TECHNIKER_1439-scaled.jpg" sizes="100vw" alt=""
            loading="lazy" decoding="async" />
    </div>

    <div class="kontakt__blur" aria-hidden="true"></div>

    <div class="kontakt__overlay container">
        <div class="kontakt__content text-wrapper">
            <h2 class="headline headline-2">
                Sie haben Fragen oder möchten uns etwas mitteilen?
            </h2>

            <div class="headline headline-3">

Nehmen Sie gerne Kontakt auf
            </div>
<?php $mail = get_field( 'email', 'option' ); ?>
            <?php if ($mail): ?>
            <div class="button-wrapper" role="group" aria-label="Actions">
                <a class="btn btn--primary" href="mailto:<?php echo esc_url($mail); ?>">
                    
                   
                    <span class="btn__text">Email: <?php echo esc_html($mail); ?></span>
                </a>
                <span id="<?php echo esc_html($kontakt_button['title']); ?>" class="sr-only">(Description for screen
                    readers)</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
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
                <?php endif; ?>
            </div>
            <p class="copyright">© <?php the_field('copyright', 'option'); ?></p>
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