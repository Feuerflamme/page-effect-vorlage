<?php
/**
 * ACF Clone Field Helper Functions
 * 
 * This file contains utility functions for safely accessing and handling
 * Advanced Custom Fields (ACF) Clone Fields in WordPress block templates.
 * 
 * IMPORTANT NOTE: The theme uses SEAMLESS clone fields ("display": "seamless").
 * This means clone fields can be accessed directly with get_field() and the_field()
 * without needing these helper functions:
 * 
 * SEAMLESS CLONE USAGE (RECOMMENDED):
 * - get_field('sub-headline') - Direct access, no array needed
 * - the_field('einleitungstext') - Direct output, no helper needed
 * - get_field('abstand_halb') - Direct access to clone field
 * 
 * These helper functions are only needed for GROUP clone fields ("display": "group").
 * They provide:
 * - Safe array access with null checks (for group clones)
 * - Type validation and sanitization
 * - Consistent error handling
 * - Clean template code for complex clone structures
 * 
 * @package Page Effect
 * @author Thomas Pondelek
 * @version 1.0.0
 * @deprecated Most functions not needed for seamless clone fields
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load converter functions if not already available
if (!function_exists('convert_stars_to_strong')) {
    $converter_file = get_theme_file_path('/functions/bold-converter.php');
    if (file_exists($converter_file)) {
        require_once $converter_file;
    }
}

if (!function_exists('convert_hash_to_linebreak')) {
    $linebreak_file = get_theme_file_path('/functions/line-break-converter.php');
    if (file_exists($linebreak_file)) {
        require_once $linebreak_file;
    }
}

/**
 * Safely retrieve a field value from an ACF clone field
 * 
 * This is the core function that handles all the complexity of accessing
 * clone fields safely. It performs multiple validation checks:
 * 1. Retrieves the clone field data using get_field()
 * 2. Validates that the result is an array (clone fields return arrays)
 * 3. Checks if the requested field exists within the clone array
 * 4. Validates that the field has content (not empty)
 * 5. Optionally sanitizes the output for safe HTML rendering
 * 
 * @param string $clone_name    The name of the ACF clone field (e.g., 'einleitungstext_no_konflikt')
 * @param string $field_name    The name of the specific field within the clone (e.g., 'sub-headline')
 * @param bool   $sanitize      Whether to sanitize the output with wp_kses_post() [default: true]
 * @param mixed  $block_id      Optional block ID for context-specific field retrieval [default: null]
 * 
 * @return string|false The field value if found and valid, false otherwise
 * 
 * @example
 * // Get a subheadline from the einleitungstext clone field
 * $subheadline = get_clone_field_safe('einleitungstext_no_konflikt', 'sub-headline', true, $block['id']);
 * if ($subheadline) {
 *     echo '<h3>' . $subheadline . '</h3>';
 * }
 * 
 * @since 1.0.0
 */
function get_clone_field_safe($clone_name, $field_name, $sanitize = true, $block_id = null) {
    // Step 1: Retrieve the clone field data
    // If block_id is provided, get field with context, otherwise get global field
    $clone_data = $block_id ? get_field($clone_name, $block_id) : get_field($clone_name);
    
    // TEMPORARY DEBUG - Remove after testing
    if (WP_DEBUG && current_user_can('administrator')) {
        error_log("DEBUG get_clone_field_safe: clone_name=$clone_name, field_name=$field_name");
        error_log("DEBUG clone_data: " . print_r($clone_data, true));
    }
    
    // Step 2: Validate clone field data
    // Clone fields should return arrays, not strings or null
    if (!$clone_data || !is_array($clone_data)) {
        return false;
    }
    
    // Step 3: Check if the specific field exists within the clone
    if (!isset($clone_data[$field_name])) {
        return false;
    }
    
    // Step 4: Check if the field has actual content
    // Use more lenient check - only reject if null or completely empty string
    if ($clone_data[$field_name] === null || $clone_data[$field_name] === '') {
        return false;
    }
    
    // Step 5: Get the field value
    $value = $clone_data[$field_name];
    
    // Step 6: Sanitize if requested (default behavior for security)
    // wp_kses_post() allows safe HTML tags while stripping dangerous content
    return $sanitize ? wp_kses_post($value) : $value;
}

/**
 * Safely output a clone field value with HTML wrapper
 * 
 * This function combines field retrieval and HTML output in one step.
 * It's perfect for template files where you want to output a field
 * only if it has content, wrapped in specific HTML tags.
 * 
 * The function will only output HTML if the field has content,
 * preventing empty HTML elements in your markup.
 * 
 * @param string $clone_name    The name of the ACF clone field
 * @param string $field_name    The name of the specific field within the clone
 * @param string $wrapper_tag   HTML tag to wrap the content [default: 'div']
 * @param string $css_class     CSS class to add to the wrapper [default: '']
 * @param mixed  $block_id      Optional block ID for context [default: null]
 * 
 * @return void Outputs HTML directly or nothing if field is empty
 * 
 * @example
 * // Output a subheadline wrapped in a div with CSS class
 * the_clone_field_safe('einleitungstext_no_konflikt', 'sub-headline', 'div', 'subheadline', $block['id']);
 * // Outputs: <div class="subheadline">Your subheadline text</div>
 * 
 * // Output in a custom HTML tag
 * the_clone_field_safe('einleitungstext_no_konflikt', 'sektionsuberschrift_h2', 'h2', 'main-headline', $block['id']);
 * // Outputs: <h2 class="main-headline">Your headline</h2>
 * 
 * @since 1.0.0
 */
function the_clone_field_safe($clone_name, $field_name, $wrapper_tag = 'div', $css_class = '', $block_id = null) {
    // Use the safe getter function to retrieve the field value
    $value = get_clone_field_safe($clone_name, $field_name, true, $block_id);
    
    // Only output HTML if we have content
    if ($value) {
        // Build the class attribute if a CSS class is provided
        $class_attr = $css_class ? ' class="' . esc_attr($css_class) . '"' : '';
        
        // Output the complete HTML element
        echo "<{$wrapper_tag}{$class_attr}>{$value}</{$wrapper_tag}>";
    }
}

/**
 * Retrieve a field from the 'einleitungstext_no_konflikt' clone safely
 * 
 * This is a specialized helper for the most commonly used clone field
 * in the theme. The 'einleitungstext_no_konflikt' clone contains
 * standard section introduction fields used across multiple blocks.
 * 
 * Available fields in einleitungstext_no_konflikt clone:
 * - 'sektionsuberschrift_h2': Main section headline (H2)
 * - 'sub-headline': Secondary headline/subheadline
 * - 'einleitungstext': Introduction text/paragraph content
 * - 'button': Button/link data (if applicable)
 * 
 * @param string $field_name    The field name within the einleitungstext clone
 * @param mixed  $block_id      Optional block ID for context [default: null]
 * 
 * @return string|false The field value if found and valid, false otherwise
 * 
 * @example
 * // Get the main headline from einleitungstext clone
 * $headline = get_einleitungstext_field('sektionsuberschrift_h2', $block['id']);
 * 
 * // Get the subheadline
 * $subheadline = get_einleitungstext_field('sub-headline', $block['id']);
 * 
 * // Get the introduction text
 * $intro_text = get_einleitungstext_field('einleitungstext', $block['id']);
 * 
 * @since 1.0.0
 */
function get_einleitungstext_field($field_name, $block_id = null) {
    return get_clone_field_safe('einleitungstext_no_konflikt', $field_name, true, $block_id);
}

/**
 * Output a complete einleitungstext section with proper HTML structure
 * 
 * This function handles the entire introduction section output for blocks
 * that use the einleitungstext clone field. It outputs a complete HTML
 * structure with proper semantic tags and CSS classes.
 * 
 * The function automatically:
 * - Applies star-to-strong conversion for headlines (if function is available)
 * - Uses proper semantic HTML tags (h2, div)
 * - Applies consistent CSS classes for styling
 * - Only outputs HTML for fields that have content
 * - Wraps everything in a 'text-wrapper' container
 * 
 * @param mixed $block_id Optional block ID for context [default: null]
 * 
 * @return void Outputs complete HTML structure or nothing if no fields have content
 * 
 * @example
 * // In a block template, output the complete intro section
 * <?php the_einleitungstext_section($block['id']); ?>
*
* // This might output:
* // <div class="text-wrapper">
    * // <h2 class="headline-2 headline">Your Main Headline</h2>
    * // <div class="subheadline">Your subheadline</div>
    * // <div class="textblock">Your introduction text paragraph...</div>
    * // </div>
*
* @since 1.0.0
*/
function the_einleitungstext_section($block_id = null) {
// Get all the einleitungstext fields
$headline = get_einleitungstext_field('sektionsuberschrift_h2', $block_id);
$subheadline = get_einleitungstext_field('sub-headline', $block_id);
$textblock = get_einleitungstext_field('einleitungstext', $block_id);

// Only output the wrapper if we have at least one field with content
if ($headline || $subheadline || $textblock) {
echo '<div class="text-wrapper">';

    // Output headline with star-to-strong and hash-to-linebreak conversion if available
    if ($headline) {
    // Check if conversion functions are available and apply them
    if (function_exists('convert_stars_to_strong')) {
    $headline = convert_stars_to_strong($headline);
    }
    if (function_exists('convert_hash_to_linebreak')) {
    $headline = convert_hash_to_linebreak($headline);
    }
    echo '<h2 class="headline-2 headline">' . $headline . '</h2>';
    }

    // Output subheadline if present
    if ($subheadline) {
    echo '<div class="subheadline">' . $subheadline . '</div>';
    }

    // Output text block if present
    if ($textblock) {
    echo '<div class="textblock">' . $textblock . '</div>';
    }

    echo '</div>';
}
}

/**
* Safely retrieve module spacing value from clone field
*
* Module spacing is commonly used across blocks to add consistent
* spacing between sections. This function tries multiple possible
* field names to maintain compatibility with different clone field
* structures.
*
* Common spacing field names:
* - 'abstand_halb': Half spacing option
* - 'moduleabstand': Module spacing option
* - 'modulabstand': Alternative spelling variant
*
* @param mixed $block_id Optional block ID for context [default: null]
*
* @return string|false The spacing value if found, false otherwise
*
* @example
* // Get module spacing and apply to CSS classes
* $spacing = get_module_spacing($block['id']);
* if ($spacing) {
* $css_classes .= ' ' . $spacing;
* }
*
* @since 1.0.0
*/
function get_module_spacing($block_id = null) {
// Try the most common field name first
$spacing = get_clone_field_safe('moduleabstand', 'abstand_halb', false, $block_id);

// If not found, try alternative field name
if (!$spacing) {
$spacing = get_clone_field_safe('modulabstand', 'abstand_halb', false, $block_id);
}

return $spacing;
}

/**
* Safely retrieve background color value from clone field
*
* Background colors are managed through clone fields to ensure
* consistency across all blocks. This function retrieves the
* background color value safely with proper validation.
*
* Note: Function name includes 'clone_' prefix to avoid conflicts
* with WordPress core get_background_color() function.
*
* @param mixed $block_id Optional block ID for context [default: null]
*
* @return string|false The background color value if found, false otherwise
*
* @example
* // Get background color and apply to CSS classes
* $bg_color = get_clone_background_color($block['id']);
* if ($bg_color && $bg_color !== 'none') {
* $css_classes .= ' hintergrundfarbe-' . $bg_color;
* $css_classes .= ' has-background';
* }
*
* @since 1.0.0
*/
function get_clone_background_color($block_id = null) {
return get_clone_field_safe('hintergrundfarbe', 'hintergrundfarbe_bg', false, $block_id);
}

/**
* Safely retrieve complete clone field data as array
*
* Sometimes you need access to all fields within a clone at once.
* This function retrieves the entire clone field array with
* validation to ensure it's usable data.
*
* This is useful when you need to:
* - Process multiple fields from the same clone
* - Check what fields are available in a clone
* - Pass clone data to other functions
* - Perform custom validation on clone contents
*
* @param string $clone_name The name of the ACF clone field
* @param mixed $block_id Optional block ID for context [default: null]
*
* @return array|false Complete clone field array if valid, false otherwise
*
* @example
* // Get all einleitungstext data at once
* $intro_data = get_clone_data_safe('einleitungstext_no_konflikt', $block['id']);
* if ($intro_data) {
* foreach ($intro_data as $field_name => $field_value) {
* if (!empty($field_value)) {
* echo "<p><strong>{$field_name}:</strong> {$field_value}</p>";
* }
* }
* }
*
* // Check if specific fields exist in the clone
* $clone_data = get_clone_data_safe('my_clone_field', $block['id']);
* if ($clone_data && isset($clone_data['my_field'])) {
* // Field exists, proceed with processing
* }
*
* @since 1.0.0
*/
function get_clone_data_safe($clone_name, $block_id = null) {
// Retrieve the clone field data with context if provided
$clone_data = $block_id ? get_field($clone_name, $block_id) : get_field($clone_name);

// Validate that we have an array with content
return (is_array($clone_data) && !empty($clone_data)) ? $clone_data : false;
}

/**
* Debug helper: Display clone field structure for development
*
* This function is useful during development to understand the structure
* of clone fields and debug issues. It outputs the clone field data
* in a readable format, but only for users with appropriate permissions.
*
* WARNING: This function should only be used during development and
* should be removed or disabled in production environments.
*
* @param string $clone_name The name of the ACF clone field to debug
* @param mixed $block_id Optional block ID for context [default: null]
*
* @return void Outputs debug information or nothing in production
*
* @example
* // Debug einleitungstext clone structure (development only)
* debug_clone_field('einleitungstext_no_konflikt', $block['id']);
*
* @since 1.0.0
*/
function debug_clone_field($clone_name, $block_id = null) {
// Only show debug info to administrators and in development
if (!current_user_can('administrator') || !WP_DEBUG) {
return;
}

$clone_data = get_clone_data_safe($clone_name, $block_id);

echo '<div style="background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;">';
    echo '<h4>Debug: Clone Field "' . esc_html($clone_name) . '"</h4>';

    if ($clone_data) {
    echo '
    <pre>' . print_r($clone_data, true) . '</pre>';
    } else {
    echo '<p style="color: red;">Clone field is empty or invalid</p>';
    }

    echo '
</div>';
}

/* End of file acf-clone-helpers.php */