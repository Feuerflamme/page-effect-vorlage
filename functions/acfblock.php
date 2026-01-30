<?php
class ACFBlock
{
  public $name;
  function __construct($name)
  {
    $this->name = $name;
    add_action('init', [$this, 'register_acf_blocks']);
    $this->addBlockarray();
  }


  function register_acf_blocks()
  {
    register_block_type(THEMEPATH . "/acf-blocks/{$this->name}");
  }

  function addBlockarray()
  {
    global $allowedBlocks;
    array_push($allowedBlocks, "acf/{$this->name}");
  }
}
new ACFblock("image-text");
new ACFblock("text");
new ACFblock("hero");
new ACFblock("image");
new ACFblock("image-text-btn");
new ACFblock("image-data");
new acfblock("kontakt");
new acfblock("kacheln-leistungen");

/**
 * Add custom styles to WYSIWYG editor for better visual editing
 */
function add_wysiwyg_editor_styles() {
    add_editor_style('editor-style.css');
}
add_action('after_setup_theme', 'add_wysiwyg_editor_styles');

/**
 * Enqueue styles for Gutenberg editor (Block Editor)
 */
function add_gutenberg_editor_styles() {
    wp_enqueue_style(
        'theme-editor-styles',
        get_theme_file_uri('editor-style.css'),
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('enqueue_block_editor_assets', 'add_gutenberg_editor_styles');

/**
 * Add custom styles specifically for ACF WYSIWYG fields
 */
function acf_wysiwyg_editor_styles($toolbars) {
    // Add custom CSS for ACF WYSIWYG fields
    add_action('admin_head', function() {
        echo '<style>
        /* ACF WYSIWYG Field List Styles */
        .acf-editor-wrap .mce-content-body ul {
            list-style-type: disc !important;
            margin-left: 20px !important;
            padding-left: 0 !important;
        }
        
        .acf-editor-wrap .mce-content-body ul li {
            list-style-type: disc !important;
            display: list-item !important;
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        
        .acf-editor-wrap .mce-content-body ol {
            list-style-type: decimal !important;
            margin-left: 20px !important;
            padding-left: 0 !important;
        }
        
        .acf-editor-wrap .mce-content-body ol li {
            list-style-type: decimal !important;
            display: list-item !important;
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        
        /* TinyMCE spezifische Korrekturen */
        .mce-content-body[data-id*="acf"] ul,
        .mce-content-body[data-id*="acf"] ol {
            margin: 1em 0 1em 20px !important;
        }
        
        .mce-content-body[data-id*="acf"] li {
            margin: 0.5em 0 !important;
        }
        </style>';
    });
    
    return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'acf_wysiwyg_editor_styles');