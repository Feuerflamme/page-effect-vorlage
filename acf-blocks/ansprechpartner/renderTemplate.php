<?php
/**
 * Block template file: renderTemplate.php
 *
 * Ansprechpartner Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'ansprechpartner-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'ansprechpartner';
if ( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $classes .= ' align' . $block['align'];
}
?>


<?php
$headline = get_field('headline');
$subheadline = get_field('subheadline');
$ansprechpartner_button = get_field('button');
$bild = get_field( 'bild' );
$beschreibung = get_field( 'beschreibung' );

?>

<section id="<?php echo esc_attr($id); ?>" class="module <?php echo esc_attr($classes); ?>">
 

    <div class="ansprechpartner__blur" aria-hidden="true"></div>


    <div class="ansprechpartner__overlay container">
      <div class="image">
        
	<?php if ( $bild ) : ?>
		<img src="<?php echo esc_url( $bild['url'] ); ?>" alt="<?php echo esc_attr( $bild['alt'] ); ?>" />
		<?php if ( !empty( $bild['caption'] ) ) : ?>
			<figcaption class="image-caption"><?php echo wp_kses_post( $bild['caption'] ); ?></figcaption>
		<?php endif; ?>
	<?php endif; ?>
      </div>
        <div class="ansprechpartner__content text-wrapper">
            <?php if ($headline): ?>
            <h3 class="headline headline-3">
                <?= wp_kses_post($headline) ?>
            </h3>
            <?php endif; ?>

            <?php if ($subheadline): ?>
            <h4 class="headline headline-4 subheadline">
                <?= wp_kses_post($subheadline) ?>
            </h4>
            <?php endif; ?>
            <?php if ($beschreibung): ?>
            <p class="beschreibung">
                <?= wp_kses_post($beschreibung) ?>
            </p>
            <?php endif; ?>

            <?php if ($ansprechpartner_button): ?>
            <div class="button-wrapper" role="group" aria-label="Actions">
                <a class="btn btn--primary" href="<?php echo esc_url($ansprechpartner_button['url']); ?>"
                    target="<?php echo esc_attr($ansprechpartner_button['target']); ?>"
                    aria-describedby="<?php echo esc_html($ansprechpartner_button['title']); ?>">
                    <span class="btn__text"><?php echo esc_html($ansprechpartner_button['title']); ?></span>
                </a>
                <span id="<?php echo esc_html($ansprechpartner_button['title']); ?>" class="sr-only">(Description for screen
                    readers)</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>