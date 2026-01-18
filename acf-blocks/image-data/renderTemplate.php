<?php
/**
 * Block template file: renderTemplate.php
 *
 * Image Data Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'image-data-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'image-data';
if (!empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $classes .= ' align' . $block['align'];
}
?>

<style type="text/css">
<?php echo '#'. $id;

?> {
    /* Add styles that use ACF values here */
}
</style>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($classes); ?>">
    <?php $id_image = get_field('id::image'); ?>

    <div class="image-data__col image-data__col--image">
        <?php if ($id_image): ?>
        <img src="<?php echo esc_url($id_image['url']); ?>" alt="<?php echo esc_attr($id_image['alt']); ?>" />
        <?php endif; ?>
    </div>

    <div class="image-data__col image-data__col--data">
        <?php if (have_rows('id::first')): ?>
        <?php while (have_rows('id::first')):
                the_row(); ?>
        <?php $value = get_sub_field('id::value'); ?>
        <?php $text = get_sub_field('id::text'); ?>
        <div class="image-data__counter" data-target="<?php echo esc_attr($value); ?>">
            <div class="image-data__value 
            <?php the_sub_field('id::color'); ?>">0</div>
            <?php if ($text): ?>
            <div class="image-data__text"><?php echo esc_html($text); ?></div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <div class="image-data__col image-data__col--data">
        <?php if (have_rows('id::second')): ?>
        <?php while (have_rows('id::second')):
                the_row(); ?>
        <?php $value = get_sub_field('id::value'); ?>
        <?php $text = get_sub_field('id::text'); ?>
        <div class="image-data__counter" data-target="<?php echo esc_attr($value); ?>">
            <div class="image-data__value 
            <?php the_sub_field('id::color'); ?>">0</div>
            <?php if ($text): ?>
            <div class="image-data__text"><?php echo esc_html($text); ?></div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>