<?php

$allowedBlocks = [];

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
//Global Popup Cookie Based
require_once(FUNCTIONSPATH . 'popup-cookie-based.php');

/**
 * Register navigation menus
 */

function theme_register_menus()
{
  register_nav_menus(array(
    'primary'   => 'Main',
    'footer'    => 'Footer'
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

  // if ($post->post_type === 'theme_reference') {
  //   array_push($allowedBlocks, "core/spacer", "core/paragraph", "core/heading", "core/columns");
  // }
  return $allowedBlocks;
}

add_filter('allowed_block_types', 'allowedBlockTypes', 10, 2);

// Load ACF Clone Field Helper Functions
require_once(FUNCTIONSPATH . 'acf-clone-helpers.php');