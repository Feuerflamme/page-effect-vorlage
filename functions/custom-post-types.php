<?php

/**
 * Register Custom Post Types
 */

function register_custom_post_types() {
    
    // Custom Post Type: Aktuelles
    $labels_aktuelles = array(
        'name'                  => _x('Aktuelles', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Aktueller Beitrag', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Aktuelles', 'textdomain'),
        'name_admin_bar'        => __('Aktueller Beitrag', 'textdomain'),
        'archives'              => __('Aktuelles Archiv', 'textdomain'),
        'attributes'            => __('Beitrag Attribute', 'textdomain'),
        'parent_item_colon'     => __('Übergeordneter Beitrag:', 'textdomain'),
        'all_items'             => __('Alle Beiträge', 'textdomain'),
        'add_new_item'          => __('Neuen Beitrag hinzufügen', 'textdomain'),
        'add_new'               => __('Neu hinzufügen', 'textdomain'),
        'new_item'              => __('Neuer Beitrag', 'textdomain'),
        'edit_item'             => __('Beitrag bearbeiten', 'textdomain'),
        'update_item'           => __('Beitrag aktualisieren', 'textdomain'),
        'view_item'             => __('Beitrag anzeigen', 'textdomain'),
        'view_items'            => __('Beiträge anzeigen', 'textdomain'),
        'search_items'          => __('Beiträge suchen', 'textdomain'),
        'not_found'             => __('Nicht gefunden', 'textdomain'),
        'not_found_in_trash'    => __('Nicht im Papierkorb gefunden', 'textdomain'),
        'featured_image'        => __('Beitragsbild', 'textdomain'),
        'set_featured_image'    => __('Beitragsbild festlegen', 'textdomain'),
        'remove_featured_image' => __('Beitragsbild entfernen', 'textdomain'),
        'use_featured_image'    => __('Als Beitragsbild verwenden', 'textdomain'),
        'insert_into_item'      => __('In Beitrag einfügen', 'textdomain'),
        'uploaded_to_this_item' => __('Zu diesem Beitrag hochgeladen', 'textdomain'),
        'items_list'            => __('Beiträge Liste', 'textdomain'),
        'items_list_navigation' => __('Beiträge Listen Navigation', 'textdomain'),
        'filter_items_list'     => __('Beiträge Liste filtern', 'textdomain'),
    );

    $args_aktuelles = array(
        'label'                 => __('Aktueller Beitrag', 'textdomain'),
        'description'           => __('Aktuelle Nachrichten und Meldungen', 'textdomain'),
        'labels'                => $labels_aktuelles,
        'supports'              => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'taxonomies'            => array('category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-megaphone',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array(
            'slug' => 'aktuelles',
            'with_front' => false,
        ),
    );

    register_post_type('aktuelles', $args_aktuelles);
}

add_action('init', 'register_custom_post_types', 0);

/**
 * Flush rewrite rules on theme activation
 */
function flush_rewrite_rules_on_activation() {
    register_custom_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');