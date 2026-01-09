
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

$slider = get_field('slider');
if ( $slider ) :
    $classes .= ' slider-true';

    else :
    $classes .= ' slider-false';
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

    <?php if ( $slider == 0 ) : ?>
      <?php
      $image = get_field('einzelnes_bild');
      if ( $image ): ?>
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
      <?php endif; ?>
    <?php else : // Custom Slider ?> 
      <div class="custom-slider">
        <div class="slider-track">

          <?php $slider_bilder_images = get_field( 'slider_bilder' ); ?>
          <?php if ( $slider_bilder_images ): ?>
            <?php foreach ( $slider_bilder_images as $slider_bilder_image ): ?>
              <div class="slide">
                  <!-- Responsive slider image -->
                  <img
                    src="<?= wp_get_attachment_image_url( $slider_bilder_image['ID'], 'medium' ) ?>"
                    srcset="<?= wp_get_attachment_image_srcset( $slider_bilder_image['ID'] ) ?>"
                    sizes="100vw"
                    alt="<?= esc_attr( $slider_bilder_image['alt'] ?: 'Slider Image' ) ?>"
                    loading="lazy"
                    decoding="async"
                    width="<?= $slider_bilder_image['width'] ?>"
                    height="<?= $slider_bilder_image['height'] ?>" />
                </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>
    <?php endif; ?>
          </div>
  </div>
</section>