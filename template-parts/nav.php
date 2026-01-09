<?php

/**
 * Navigation Functions
 * Functions for displaying and managing WordPress menus
 */

/**
 * Custom Walker for adding dropdown icons to menu items
 */
class theme_Walker_Nav_Menu extends Walker_Nav_Menu {
  
  /**
   * Start the list before the elements are added.
   */
  function start_lvl(&$output, $depth = 0, $args = null) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"sub-menu\">\n";
  }

  /**
   * End the list after the elements are added.
   */
  function end_lvl(&$output, $depth = 0, $args = null) {
    $indent = str_repeat("\t", $depth);
    $output .= "$indent</ul>\n";
  }

  /**
   * Start the element output.
   */
  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $indent = ($depth) ? str_repeat("\t", $depth) : '';
    
    $classes = empty($item->classes) ? array() : (array) $item->classes;
    $classes[] = 'menu-item-' . $item->ID;
    
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
    
    $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
    $id = $id ? ' id="' . esc_attr($id) . '"' : '';
    
    $output .= $indent . '<li' . $id . $class_names .'>';
    
    $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
    
    $item_output = isset($args->before) ? $args->before : '';
    $item_output .= '<a' . $attributes . '>';
    $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
    $item_output .= '</a>';
    
    // Add dropdown icon for menu items with children
    if (in_array('menu-item-has-children', $classes)) {
      $item_output .= '<button class="submenu-toggle" aria-label="Toggle submenu" data-menu-item="' . esc_attr($item->ID) . '">';
      $item_output .= '<i class="icon-down" aria-hidden="true"></i>';
      $item_output .= '</button>';
    }
    
    $item_output .= isset($args->after) ? $args->after : '';
    
    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  /**
   * End the element output.
   */
  function end_el(&$output, $item, $depth = 0, $args = null) {
    $output .= "</li>\n";
  }
}




/**
 * Display a specific menu if it exists
 * 
 * @param string $location Menu location or menu name/ID
 * @param array $args Additional wp_nav_menu arguments
 */
function theme_display_menu($location = 'primary', $args = array())
{
  // Default arguments
  $defaults = array(
    'theme_location'  => $location,
    'menu_class'      => 'nav-menu',
    'container'       => 'nav',
    'container_class' => 'navigation',
    'fallback_cb'     => 'theme_menu_fallback',
    'walker'          => new theme_Walker_Nav_Menu(),
    'depth'           => 0,
  );

  $args = wp_parse_args($args, $defaults);

  // Start navigation container
  echo '<nav class="' . esc_attr($args['container_class']) . '">';

  // Add header elements inside navigation
  echo '<div class="nav-wrapper">';
  echo '<div class="nav-header">';
  echo '<h3 class="nav-title">Menü</h3>';
  echo '<button type="button" class="nav-close" aria-label="Close navigation menu" aria-expanded="true">';
  echo '<span class="nav-close__icon" aria-hidden="true">';
  echo '<i class="icon-cancel" aria-hidden="true"></i>';
  echo '</span>';
  echo '<span class="nav-close__text">Close</span>';
  echo '</button>';
  echo '</div>';


  // Update args to not create another container
  $args['container'] = false;
  $args['container_class'] = '';

  // Check if menu exists at location
  if (has_nav_menu($location)) {
    wp_nav_menu($args);
  } else {
    // Call fallback function
    call_user_func($args['fallback_cb'], $args);
  }

  // Close navigation container

  echo '<div class="contact-wrapper">';
  $email = get_field('email', 'option');
  $phone = get_field('phone', 'option');
  echo '<a class="mail" href="mailto:' . esc_attr($email) . '">';
  echo '<i class="icon-mail-light" aria-hidden="true"></i>';
  echo esc_html($email) . '</a>';
  echo '<a class="phone" href="tel:+49' . esc_attr($phone) . '">';
  echo '<i class="icon-phone-light" aria-hidden="true"></i>';
  echo '+49 (0)' . esc_html($phone) . '</a>';
  echo '</div>';
  
  // Social Media Links - analog zum Footer
  if ( have_rows( 'some', 'option' ) ) :
    echo '<div class="some-wrapper">';
    while ( have_rows( 'some', 'option' ) ) : the_row();
      $link = get_sub_field( 'profil_link' );
      $plattform = get_sub_field( 'plattform_auswahlen' );
      
      if ( $link && $plattform ) :
        echo '<a class="some ' . esc_attr($plattform) . '" ';
        echo 'href="' . esc_url($link) . '" ';
        echo 'aria-label="' . esc_attr($plattform) . ' Profil besuchen" ';
        echo 'target="_blank" rel="noopener noreferrer">';
        echo '<i class="icon icon-' . esc_attr($plattform) . '" aria-hidden="true"></i>';
        echo '</a>';
      endif;
    endwhile;
    echo '</div>';
  endif;

  echo '</nav>';
  echo '</div>';
}

/**
 * Fallback function when no menu is assigned
 * 
 * @param array $args Menu arguments
 */
function theme_menu_fallback($args)
{
  // Simple fallback - just show homepage link with proper menu structure
  echo '<ul class="' . esc_attr($args['menu_class']) . ' fallback-menu">';
  echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
  echo '<li><em>No menu assigned. Please create a menu in WordPress admin.</em></li>';
  echo '</ul>';
}