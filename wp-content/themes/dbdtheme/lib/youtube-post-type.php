<?php

function dbd_create_youtube_post_type()
{
  $labels = array(
    'name'                  => _x('YouTube Posts', 'Post type general name', 'disbydem'),
    'singular_name'         => _x('YouTube Post', 'Post type singular name', 'disbydem'),
    'menu_name'             => _x('YouTube Posts', 'Admin Menu text', 'disbydem'),
    'name_admin_bar'        => _x('YouTube Post', 'Add New on Toolbar', 'disbydem'),
    'add_new'               => __('Add New', 'disbydem'),
    'add_new_item'          => __('Add New YouTube Post', 'disbydem'),
    'new_item'              => __('New YouTube Post', 'disbydem'),
    'edit_item'             => __('Edit YouTube Post', 'disbydem'),
    'view_item'             => __('View YouTube Post', 'disbydem'),
    'all_items'             => __('All YouTube Posts', 'disbydem'),
    'search_items'          => __('Search YouTube Posts', 'disbydem'),
    'parent_item_colon'     => __('Parent YouTube Posts:', 'disbydem'),
    'not_found'             => __('No books found.', 'disbydem'),
    'not_found_in_trash'    => __('No books found in Trash.', 'disbydem'),
    'featured_image'        => _x('YouTube Post Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'disbydem'),
    'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'disbydem'),
    'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'disbydem'),
    'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'disbydem'),
    'archives'              => _x('YouTube Post archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'disbydem'),
    'insert_into_item'      => _x('Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'disbydem'),
    'uploaded_to_this_item' => _x('Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'disbydem'),
    'filter_items_list'     => _x('Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'disbydem'),
    'items_list_navigation' => _x('YouTube Posts list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'disbydem'),
    'items_list'            => _x('YouTube Posts list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'disbydem'),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'youtube-post'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array('title', 'editor', 'thumbnail'),
    'taxonomies'         => array('category', 'post_tag', 'genre'),
    'menu_icon'          => 'dashicons-video-alt',
    'show_in_rest'       => true,
    'description'        => __('A custom post type for youtube posts', 'disbydem')
  );

  register_post_type('youtube-post', $args);
}

add_action('init', 'dbd_create_youtube_post_type');
