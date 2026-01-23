<?php
/**
 * Block template file: renderTemplate.php
 *
 * Kontakt Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'kontakt-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'kontakt';
if ( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $classes .= ' align' . $block['align'];
}
?>

<style type="text/css">
<?php echo '#'. $id;

?> {
    /* Add styles that use ACF values here */
}
</style>

<?php
$background_image = get_field('kontakt::backgroundimage');
$headline = get_field('kontakt::headline');
$subheadline = get_field('kontakt::subheadline');
$kontakt_button = get_field('kontakt::button');
?>

<section id="<?php echo esc_attr($id); ?>" class="module <?php echo esc_attr($classes); ?>">
    <?php if ($background_image): ?>
    <div class="kontakt__background" aria-hidden="true">
        <img src="<?= esc_url(wp_get_attachment_image_url($background_image['ID'], 'full')) ?>"
            srcset="<?= esc_attr(wp_get_attachment_image_srcset($background_image['ID'])) ?>" sizes="100vw" alt=""
            loading="lazy" decoding="async" />
    </div>
    <?php endif; ?>

    <div class="kontakt__blur" aria-hidden="true"></div>

    <div class="kontakt__overlay container">
        <div class="kontakt__content text-wrapper">
            <?php if ($headline): ?>
            <h2 class="headline headline-2">
                <?= wp_kses_post($headline) ?>
            </h2>
            <?php endif; ?>

            <?php if ($subheadline): ?>
            <div class="headline headline-3">
                <?= wp_kses_post($subheadline) ?>
            </div>
            <?php endif; ?>

            <?php if ($kontakt_button): ?>
            <div class="button-wrapper" role="group" aria-label="Actions">
                <a class="btn btn--primary" href="<?php echo esc_url($kontakt_button['url']); ?>"
                    target="<?php echo esc_attr($kontakt_button['target']); ?>"
                    aria-describedby="<?php echo esc_html($kontakt_button['title']); ?>">
                    <span class="btn__text"><?php echo esc_html($kontakt_button['title']); ?></span>
                </a>
                <span id="<?php echo esc_html($kontakt_button['title']); ?>" class="sr-only">(Description for screen
                    readers)</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>