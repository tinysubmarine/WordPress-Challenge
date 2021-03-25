<?php

// Define path and URL to the ACF plugin.
define('MY_ACF_PATH', get_stylesheet_directory() . '/includes/acf/');
define('MY_ACF_URL', get_stylesheet_directory_uri() . '/includes/acf/');

// Include the ACF plugin.
include_once(MY_ACF_PATH . 'acf.php');

/**
 * Update Asset URLs to use included path
 *
 * @since 1.0
 */
add_filter('acf/settings/url', function ($url) {
    return MY_ACF_URL;
});

/**
 * Disable ACF for Users
 *
 * @since 1.0
 */
add_filter('acf/settings/show_admin', function ($show_admin) {
    return false;
});

/**
 * Include ACF field
 *
 * @since 1.0
 */
add_action('acf/init', function () {

    acf_add_local_field_group(array(
        'key' => 'group_605bef263111c',
        'title' => 'Resource Fields',
        'fields' => array(
            array(
                'key' => 'field_605bef79f2f89',
                'label' => 'Download URL',
                'name' => 'download_url',
                'type' => 'url',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'resource',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
});

/**
 * Register Resource Post Type with Taxonomies for Topic and Audience
 *
 * @since 1.0
 */
add_action('init', function () {

    $labels = [
        'name' => __('Resources'),
        'singular_name' => __('Resource'),
        'menu_name' => __('Resources'),
        'parent_item_colon' => __('Parent Resource'),
        'all_items' => __('All Resources'),
        'view_item' => __('View Resource'),
        'add_new_item' => __('Add New Resource'),
        'add_new' => __('Add New'),
        'edit_item' => __('Edit Resource'),
        'update_item' => __('Update Resource'),
        'search_items' => __('Search Resource'),
        'not_found' => __('Not Found'),
        'not_found_in_trash' => __('Not found in Trash')
    ];

    $resource_post_type_args = [
        'label' => __('resource'),
        'supports' => ['title', 'editor', 'thumbnail'],
        'labels' => $labels,
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'has_archive' => true,
        'can_export' => true,
        'exclude_from_search' => false,
        'yarpp_support' => true,
        'taxonomies' => array('post_tag'),
        'publicly_queryable' => true,
        'capability_type' => 'page'
    ];

    $topic_taxonomy_args = [
        'hierarchical' => true,
        'label' => __('Topics'),
        'query_var' => true,
        'rewrite' => [
            'slug' => 'topics',
            'with_front' => false
        ]
    ];

    $audience_taxonomy_args = [
        'hierarchical' => true,
        'label' => __('Audiences'),
        'query_var' => true,
        'rewrite' => [
            'slug' => 'topics',
            'with_front' => false
        ]
    ];

    register_post_type('resource', $resource_post_type_args);
    register_taxonomy('topic', 'resource', $topic_taxonomy_args);
    register_taxonomy('audience', 'resource', $audience_taxonomy_args);

    add_theme_support('post-thumbnails');
});

/**
 * Apply Filters to Resource Archive Query
 *
 * @since 1.0
 */
add_action('pre_get_posts', function ($query) {

    if (is_post_type_archive('resource')) {

        // default is 10 but I will add it since it's in the instructions
        $query->set('posts_per_page', 10);

        $filter_audience = [];
        $filter_topic = [];

        if (isset($_GET['filter_audience']) && $_GET['filter_audience']) {
            $filter_audience[] = [
                'taxonomy' => 'audience',
                'field' => 'id',
                'terms' => $_GET['filter_audience']
            ];
        }

        if (isset($_GET['filter_topic']) && $_GET['filter_topic']) {
            $filter_topic[] = [
                'taxonomy' => 'topic',
                'field' => 'id',
                'terms' => $_GET['filter_topic']
            ];
        }

        if (!empty($filter_topic) && !empty($filter_audience)) {
            $query->set('tax_query', [
                'relation' => 'AND',
                $filter_audience,
                $filter_topic
            ]);
        } else if (!empty($filter_topic)) {
            $query->set('tax_query', [
                $filter_topic
            ]);
        } else if (!empty($filter_audience)) {
            $query->set('tax_query', [
                $filter_audience
            ]);
        }
    }

    return $query;
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('wp-challenge-style', get_stylesheet_uri());
});