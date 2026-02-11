<?php
/**
 * Block template file: renderTemplate.php
 *
 * Image Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'image-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-image';
if ( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $classes .= ' align' . $block['align'];
}

// Use direct field access for seamless clone fields
$abstand_halb = get_field('abstand_halb');
if ( $abstand_halb ) :
    $classes .= ' abstand-halb';
endif;

$hintergrundfarbe_bg = get_field('hintergrundfarbe_bg');

if ( $hintergrundfarbe_bg ) :
  $classes .= ' hintergrundfarbe-' . $hintergrundfarbe_bg;
  
  
endif;

if ( $hintergrundfarbe_bg != 'none') :
  $classes .= ' has-background';
  
  
endif;



?>

<style type="text/css">
	<?php echo '#' . $id; ?> {
		/* Add styles that use ACF values here */
	}
</style>
<section id="<?php echo esc_attr($id); ?>"
  class="module <?php echo esc_attr($classes); ?>">

  <div class="container">
          <div class="wrapper">

      <?php
      $image = get_field('einzelnes_bild');
      if ( $image ): ?>
        <figure>
          <!-- Responsive single image -->
          <img
            src="<?= wp_get_attachment_image_url( $image['ID'], 'medium' ) ?>"
            srcset="<?= wp_get_attachment_image_srcset( $image['ID'] ) ?>"
            sizes="100vw"
            alt="<?= esc_attr( $image['alt'] ?: 'Image' ) ?>"
            loading="lazy"
              decoding="async"
              width="<?= $image['width'] ?>"
            height="<?= $image['height'] ?>"
            onload="console.log('Image Block Single loaded:', this.currentSrc || this.src)" />
          <?php if ( !empty( $image['caption'] ) ) : ?>
            <figcaption class="image-caption"><?php echo wp_kses_post( $image['caption'] ); ?></figcaption>
          <?php endif; ?>
        </figure>
      <?php endif; ?>

          </div>
  </div>
</section>