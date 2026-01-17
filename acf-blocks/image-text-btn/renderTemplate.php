<?php
/**
 * Block template file: renderTemplate.php
 *
 * Image & Text & Button Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'image-text-btn-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-image-text-btn';
if (!empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $classes .= ' align' . $block['align'];
}

// Get ACF fields
$image = get_field('itb::image');
$headline = get_field('itb::headline');
$buttons = get_field('itb::buttons');

?>
<section id="<?php echo esc_attr($id); ?>" class="module <?php echo esc_attr($classes); ?>">
    <?php if ($image): ?>
    <div class="background-image" aria-hidden="true">
        <img src="<?= esc_url(wp_get_attachment_image_url($image['ID'], 'full')) ?>"
            srcset="<?= esc_attr(wp_get_attachment_image_srcset($image['ID'])) ?>" sizes="100vw" alt="" loading="lazy"
            decoding="async" />
    </div>
    <?php endif; ?>

    <div class="content-overlay">
        <div class="text-wrapper">
            <?php if ($headline): ?>
            <h2 class="headline headline-1">
                <?= esc_html($headline) ?>
            </h2>
            <?php endif; ?>
            <div class="buttons">

                <?php if (have_rows('itb::buttons')): ?>
                <?php while (have_rows('itb::buttons')):
                        the_row(); ?>
                <div class="btn">

                    <?php $itb_button = get_sub_field('itb:button'); ?>
                    <?php if ($itb_button): ?>
                    <a href="<?php echo esc_url($itb_button['url']); ?>"
                        target="<?php echo esc_attr($itb_button['target']); ?>"><?php echo esc_html($itb_button['title']); ?></a>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>