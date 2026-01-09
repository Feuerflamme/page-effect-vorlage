<?php

/**
 * SEE https://github.com/pondo-1/no-bloat-popups/
 *
 **/

//////--------------Set Cookie: First Page in Session---------------------// 
add_action(
  'init',
  function () {
    $date = new DateTime("now", new DateTimeZone('Europe/Berlin'));
    setcookie('visit_time_popup', $date->format('Y-m-d H:i:s'), 0);
  }
);


// Check if global popup is active
$active = get_field( 'globales_popup_aktivieren', 'option' );

// Check if cookie is not set
if (!isset($_COOKIE["visit_time_popup"]) && $active ) {

  // Testing
  // if ( $active ) {

  /// Add Popup to Footer
  add_action('wp_footer', 'pe_popup');
  
  /// Add popup-active class to body
  add_filter('body_class', 'add_popup_active_body_class');
}

/**
 * Add popup-active class to body when global popup is active
 */
function add_popup_active_body_class($classes) {
  $classes[] = 'popup-active';
  return $classes;
}

function pe_popup()
{
  // Start output buffering to capture HTML content
  ob_start();
  
  // Get global popup settings
  $popup_title = get_field('sektionsuberschrift_h2', 'option');
  $popup_subheadline = get_field('sub-headline', 'option');
  $popup_textblock = get_field('einleitungstext', 'option');
  ?>

<div class="module popup-active global-popup">
    <div class="container popup-container">
        <div class="text-wrapper popup-active">
            <div class="popup-content global-popup" role="dialog" aria-modal="true" aria-labelledby="global-popup-title"
                aria-describedby="global-popup-description">
                <div class="popup-wrapper">
                    <button type="button" class="nav-close" aria-label="Close popup" aria-expanded="true">
                        <span class="nav-close__icon" aria-hidden="true">
                            <i class="icon-cancel" aria-hidden="true"></i>
                        </span>
                        <span class="nav-close__text">Close</span>
                    </button>

                    <div class="pop-text-wrapper">
                        <?php if ($popup_title): ?>
                        <h3 id="global-popup-title" class="headline-4 headline"><?php echo esc_html($popup_title); ?>
                        </h3>
                        <?php endif; ?>

                        <?php if ($popup_subheadline): ?>
                        <div class="subheadline">
                            <?= wp_kses_post($popup_subheadline) ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($popup_textblock): ?>
                        <div id="global-popup-description" class="textblock">
                            <?= wp_kses_post($popup_textblock) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
  // Get the buffered content and store it in $pop variable
  $pop = ob_get_clean();
  
  // Output the popup
  echo $pop;
}