<?php

/**
 * Block template file: renderTemplate.php
 *
 * Image Text Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'image-text-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'image-text';
if (!empty($block['className'])) {
  $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $classes .= ' align' . $block['align'];
}

$has_background = get_field('hintergrund');
$bild_position = get_field('bild_position');
$abstand_halb = get_field('abstand_halb');


if ($has_background):
  $classes .= ' has-background';
endif;


if ($bild_position):
  $classes .= ' ' . $bild_position;
endif;


if ($abstand_halb):
  $classes .= ' abstand-halb';
endif;

$slider = get_field('slider');
if ($slider):
  $classes .= ' slider-true';
else:
  $classes .= ' slider-false';
endif;
?>

<style type="text/css">
<?php echo '#'. $id;

?> {
    /* Add styles that use ACF values here */
}
</style>



<section id="<?php echo esc_attr($id); ?>" class="module <?php echo esc_attr($classes); ?>">
    <div class="container has-columns">
        <div class="column col-image">
            <?php
      if (get_field('slider') == 0):
        # Run Single Image Code
        $image = get_field('einzelnes_bild');
        if ($image): ?>
            <!-- Responsive image -->
            <img src="<?= wp_get_attachment_image_url($image['ID'], 'medium') ?>"
                srcset="<?= wp_get_attachment_image_srcset($image['ID']) ?>" sizes="(min-width: 768px) 50vw, 100vw"
                alt="<?= esc_attr($image['alt'] ?: 'Image') ?>" loading="lazy" decoding="async"
                width="<?= $image['width'] ?>" height="<?= $image['height'] ?>"
                onload="console.log('Image-Text Single loaded:', this.currentSrc || this.src)">
            <?php endif; ?>

            <?php else: ?>
            <?php # Run custom slider code ?>
            <div class="custom-slider">
                <div class="slider-track">
                    <?php $slider_bilder_images = get_field('slider_bilder'); ?>
                    <?php if ($slider_bilder_images): ?>
                    <?php foreach ($slider_bilder_images as $slider_bilder_image): ?>
                    <div class="slide">
                        <!-- Responsive slider image -->
                        <img src="<?= wp_get_attachment_image_url($slider_bilder_image['ID'], 'medium') ?>"
                            srcset="<?= wp_get_attachment_image_srcset($slider_bilder_image['ID']) ?>"
                            sizes="(min-width: 768px) 50vw, 100vw"
                            alt="<?= esc_attr($slider_bilder_image['alt'] ?: 'Slider Image') ?>" loading="lazy"
                            decoding="async" width="<?= $slider_bilder_image['width'] ?>"
                            height="<?= $slider_bilder_image['height'] ?>">
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>


        </div>

        <div class="column col-text">
            <?php
      $headline = get_field('sektionsuberschrift_h2');

      // Sichere Einbindung der Funktionen
      if (function_exists('convert_stars_to_strong')) {
        // Funktion bereits verfügbar
      } else {
        $converter_file = get_theme_file_path('/functions/bold-converter.php');
        if (file_exists($converter_file)) {
          require_once $converter_file;
        }
      }

      if (function_exists('convert_hash_to_linebreak')) {
        // Funktion bereits verfügbar
      } else {
        $linebreak_file = get_theme_file_path('/functions/line-break-converter.php');
        if (file_exists($linebreak_file)) {
          require_once $linebreak_file;
        }
      }

      // Headline verarbeiten falls vorhanden und Funktionen verfügbar
      if ($headline && function_exists('convert_stars_to_strong')) {
        $headline = convert_stars_to_strong($headline);
      }
      if ($headline && function_exists('convert_hash_to_linebreak')) {
        $headline = convert_hash_to_linebreak($headline);
      }
      ?>

            <?php $subheadline = get_field('sub-headline'); ?>
            <?php $textblock = get_field('textblock'); ?>
            <?php $button = get_field('button'); ?>
            <div class="text-wrapper">
                <?php if ($headline): ?>
                <h2 class="headline-3 headline">
                    <?= $headline ?>
                </h2>
                <?php endif; ?>
                <?php if ($subheadline): ?>

                <div class="subheadline">
                    <?= wp_kses_post($subheadline) ?>
                </div>
                <?php endif; ?>
                <?php if ($textblock): ?>
                <div class="textblock">
                    <?= wp_kses_post($textblock) ?>
                </div>
                <?php endif; ?>
                <?php if (have_rows('buttons')): ?>
                <?php while (have_rows('buttons')):
            the_row(); ?>
                <?php $button = get_sub_field('button'); ?>
                <?php if ($button): ?>
                <div class="button-wrapper" role="group" aria-label="Actions">
                    <!--Class SR-Only describes the action Element for Screen Readers only
            aria label described by is connecting action element and screen 
            reader description with class "sr-only" -->
                    <a class="btn btn--primary" href="<?php echo esc_url($button['url']); ?>"
                        aria-describedby="<?php echo esc_html($button['title']); ?>"
                        target="<?php echo esc_attr($button['target']); ?>">
                        <span class="btn__text"><?php echo esc_html($button['title']); ?></span>
                    </a>
                    <span id="<?php echo esc_html($button['title']); ?>" class="sr-only">(Description for screen
                        readers)</span>
                </div>
                <?php endif; ?>
                <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>