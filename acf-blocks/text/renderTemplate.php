<?php

/**
 * Block template file: renderTemplate.php
 *
 * Text Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'text-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-text';
if (!empty($block['className'])) {
  $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
  $classes .= ' align' . $block['align'];
}

// Use direct field access for seamless clone fields
$hintergrundfarbe_bg = get_field('hintergrundfarbe_bg');
$abstand_halb = get_field('abstand_halb');

if ($hintergrundfarbe_bg):
  $classes .= ' hintergrundfarbe-' . $hintergrundfarbe_bg;
endif;

if ($hintergrundfarbe_bg != 'none'):
  $classes .= ' has-background';
endif;

if ($abstand_halb):
  $classes .= ' abstand-halb';
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
       <?php $is_quote = get_field('quote'); ?>

      <?php if ($is_quote == 'no_quote'): ?>
    <div class="text-wrapper">
   
        <?php 
        $headline = get_field('sektionsuberschrift_h2'); 
        
        // Sichere Einbindung der Funktionen
        if (function_exists('convert_stars_to_strong')):
          // Funktion bereits verfügbar
        else:
          $converter_file = get_theme_file_path('/functions/bold-converter.php');
          if (file_exists($converter_file)):
            require_once $converter_file;
          endif;
        endif;
        
        if (function_exists('convert_hash_to_linebreak')):
          // Funktion bereits verfügbar
        else:
          $linebreak_file = get_theme_file_path('/functions/line-break-converter.php');
          if (file_exists($linebreak_file)):
            require_once $linebreak_file;
          endif;
        endif;
        
        // Headline verarbeiten falls vorhanden und Funktionen verfügbar
        if ($headline && function_exists('convert_stars_to_strong')):
          $headline = convert_stars_to_strong($headline);
        endif;
        if ($headline && function_exists('convert_hash_to_linebreak')):
          $headline = convert_hash_to_linebreak($headline);
        endif;
        ?>

        <?php 
        // Use direct field access (these are not clone fields)
        $subheadline = get_field('sub-headline');
        $textblock = get_field('textblock');
        $button = get_field('button');
        ?>

        <?php if ($headline): ?>
          <h2 class="headline-2 headline">
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

        <?php if ($button): ?>
          <div class="button-wrapper" role="group" aria-label="Actions">
            <!--Class SR-Only describes the action Element for Screen Readers only
            aria label described by is connecting action element and screen 
            reader description with class "sr-only" -->
            <a
              class="btn btn--primary"
              href="<?php echo esc_url($button['url']); ?>"
              aria-describedby="<?php echo esc_html($button['title']); ?>"
              target="<?php echo esc_attr($button['target']); ?>">
              <span class="btn__text"><?php echo esc_html($button['title']); ?></span>
            </a>
            <span id="<?php echo esc_html($button['title']); ?>" class="sr-only">(Description for screen readers)</span>
          </div>
        <?php endif; ?>

      <?php else: // $is_quote == 'is_quote' ?>
        <?php $author = get_field('autor'); ?>
        <?php $quote_content = get_field('quote_content'); ?>

    <div class="text-wrapper is-quote">

        <figure class="quote-wrapper">
          <figcaption>
            <?php if ($author): ?>
              <cite class="author"><?php echo esc_html($author); ?></cite>
            <?php endif; ?>
          </figcaption>
          <?php if ($quote_content): ?>
            <blockquote class="textblock quote-content">
              <?php echo wp_kses_post($quote_content); ?>
            </blockquote>
          <?php endif; ?>
        </figure>
      <?php endif; ?>

    </div>
  </div>
</section>