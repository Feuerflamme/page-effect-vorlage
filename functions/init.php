<?php
// check if required Plugin installed & activated
add_action('admin_notices', 'showAdminMessages');

function showAdminMessages()
{
  $plugin_messages = array();

  include_once(ABSPATH . 'wp-admin/includes/plugin.php');


  // Acf pro Plugin 
  if (!is_plugin_active('advanced-custom-fields-pro/acf.php')) {
    $plugin_messages[] = 'This theme requires you to install the ACF Plugin Pro , <a href="https://www.advancedcustomfields.com/my-account/view-licenses//">download it from here</a>.';
  }

  if (count($plugin_messages) > 0) {
    echo '<div id="message" class="error">';

    foreach ($plugin_messages as $message) {
      echo '<p><strong>' . $message . '</strong></p>';
    }

    echo '</div>';
  }
}



// Enqueue style and js
function pe_theme_files()
{
  //front end - mit Versioning für Development
  $css_version = WP_DEBUG ? filemtime(get_template_directory() . '/build/index.css') : wp_get_theme()->get('Version');
  $js_version = WP_DEBUG ? filemtime(get_template_directory() . '/build/index.js') : wp_get_theme()->get('Version');
  
  wp_enqueue_style('pe_theme_main_styles', get_theme_file_uri('/build/index.css'), array(), $css_version);
  // Javascript need to be loaded in footer: last variable need to be true
  wp_enqueue_script('pe_theme_js', get_template_directory_uri() . '/build/index.js', array(), $js_version, true);
}
add_action('wp_enqueue_scripts', 'pe_theme_files');

// following section replaced by the pe_theme_feature, add_editor_style
// // Add custom acf block editor(backend) style
// function enqueue_block_editor_custom_files()
// {
//   // apply frontend stlying in backend as well, for preview mode  
//   // wp_enqueue_style('pe_theme_main_styles', get_theme_file_uri('/build/style-index.css'));
//   // additional editor style
//   // wp_enqueue_style('acf-block-editor-style', get_template_directory_uri() . '/css/acf-editor-style.css');
// }
// add_action('enqueue_block_editor_assets', 'enqueue_block_editor_custom_files');

function pe_theme_features()
{
  /*
* Let WordPress manage the document title.
* By adding theme support, we declare that this theme does not use a
* hard-coded <title> tag in the document head, and expect WordPress to
  * provide it for us.
  */
  add_theme_support('title-tag');

  #Image Sizes
  #Mobile
  add_image_size('mobile-full', 390, 276, true);
  add_image_size('mobile-portrait-big', 360, 482, array('center', 'top'));
  add_image_size('mobile-portrait-small', 284, 378, array('center', 'top'));
  add_image_size('map-mobile', 358, 358, true);
  add_image_size('logo-mobile', 124, 76, false);


  #Tablet
  add_image_size('tablet-full', 834, 628, true);


  #Desktop
  add_image_size('full-width', 1642, 800, true);
  add_image_size('header', 1084, 768, true);
  add_image_size('two-column', 668, 500, true);
  add_image_size('portrait-big', 418, 560, array('center', 'top'));
  add_image_size('portrait-small', 308, 410, array('center', 'top'));
  add_image_size('map', 527, 527, true);
  add_image_size('logo', 232, 88, false);





  // add_image_size('professorLandscape', 400, 260, true); -> check image sizes 

  // Editor style. add custom acf-aditor css and front end style https://www.billerickson.net/getting-your-theme-ready-for-gutenberg/
  add_theme_support('editor-styles');
  add_editor_style(get_theme_file_uri('/build/index.css'));

  // create navigation menus
  register_nav_menus(array(
    'primary' => __('Header(main)', 'PE_en'),
  ));
}
add_action('after_setup_theme', 'pe_theme_features');






