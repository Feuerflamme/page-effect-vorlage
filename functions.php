<?php
$allowedBlocks = [
  'erecht24/impressum',
  'erecht24/datenschutz',
  'erecht24/legal-text',
  'erecht24/cookie-banner',
  'tp/slider'
];


define('THEMEPATH', get_template_directory());
define('FUNCTIONSPATH', THEMEPATH . '/functions/');

// initial settings : add css, Lato font localize, custom post type: theme reference, fontawsome
require_once(FUNCTIONSPATH . 'init.php');
// initial settings : acf block register 
require_once(FUNCTIONSPATH . 'acfblock.php');
// acf block helper functions, Add funktions to build modules
require_once(THEMEPATH . '/acf-blocks/acfblock-helper.php');
// ACF Dynamic Color Variables - Ausgelagert in eigene Datei
require_once(FUNCTIONSPATH . 'acf-color-variables.php');

// Custom Post Types
require_once(FUNCTIONSPATH . 'custom-post-types.php');

/**
 * Register navigation menus
 */

function theme_register_menus()
{
  register_nav_menus(array(
    'primary'   => 'Main',
  ));
}
add_action('after_setup_theme', 'theme_register_menus');


// Add funktions to build templates


// Load navigation functions
require_once(THEMEPATH . '/template-parts/nav.php');

/**
 * remove all default block types from wordpress
 *
 * @link https://rudrastyh.com/gutenberg/remove-default-blocks.html
 */


function allowedBlockTypes($original_allowedBlocks, $post)
{
  global $allowedBlocks;

  // Spezielle Block-Einschränkung für CPT "Aktuelles"
  if (isset($post->post_type) && $post->post_type === 'aktuelles') {
    return array(
      'acf/text',
      'acf/image-text'
    );
  }

  // Standard-WordPress-Blöcke und Theme-Blöcke gemeinsam erlauben.
  if ($original_allowedBlocks === true || $original_allowedBlocks === null) {
    $registry = WP_Block_Type_Registry::get_instance();
    $original_allowedBlocks = array_keys($registry->get_all_registered());
  }

  if (!is_array($original_allowedBlocks)) {
    $original_allowedBlocks = [];
  }

  return array_values(array_unique(array_merge($original_allowedBlocks, $allowedBlocks)));
}

add_filter('allowed_block_types', 'allowedBlockTypes', 10, 2);

// Load ACF Clone Field Helper Functions
require_once(FUNCTIONSPATH . 'acf-clone-helpers.php');

// CSS-Klassen zu erecht24 Blöcken hinzufügen
add_filter('render_block', function ($block_content, $block) {
  if (strpos($block['blockName'], 'erecht24') !== false) {
    return str_replace(
      '<div style="word-wrap: break-word;">',
      '<div class="erecht24-block legal container" style="word-wrap: break-word;">',
      $block_content
    );
  }
  return $block_content;
}, 10, 2);
