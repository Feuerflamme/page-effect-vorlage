<?php
/**
 * Block template file: renderTemplate.php
 *
 * Kacheln Leistungen Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'kacheln-leistungen-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'kacheln-leistungen';
if (!empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $classes .= ' align' . $block['align'];
}
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?>">
    <div class="container">

        <?php $headline = get_field('kt::uberschrift'); ?>
        <?php if ($headline): ?>
        <h2 class="kacheln-leistungen__headline"><?php echo esc_html($headline); ?></h2>
        <?php endif; ?>

        <?php if (have_rows('kt::leistungen')): ?>
        <div class="kacheln-leistungen__grid">
            <?php while (have_rows('kt::leistungen')): the_row(); ?>
            <div class="kachel-item">
                <article class="kachel">
                    <h3 class="kachel__title"><?php the_sub_field('kt::uberschrift'); ?></h3>
                    <div class="kachel__text"><?php the_sub_field('kt::text'); ?></div>
                </article>

                <?php $kl_mehr_erfahren = get_sub_field('kl::mehr_erfahren'); ?>
                <?php if ($kl_mehr_erfahren): ?>
                <a class="kachel__link" href="<?php echo esc_url($kl_mehr_erfahren['url']); ?>"
                    target="<?php echo esc_attr($kl_mehr_erfahren['target']); ?>">
                    <?php echo esc_html($kl_mehr_erfahren['title']); ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</div>