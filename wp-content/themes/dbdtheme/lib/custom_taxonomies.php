<?php

function dbd_create_genre_taxonomy()
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => _x('Genres', 'taxonomy general name', 'dbd'),
    'singular_name'     => _x('Genre', 'taxonomy singular name', 'dbd'),
    'search_items'      => __('Search Genres', 'dbd'),
    'all_items'         => __('All Genres', 'dbd'),
    'parent_item'       => __('Parent Genre', 'dbd'),
    'parent_item_colon' => __('Parent Genre:', 'dbd'),
    'edit_item'         => __('Edit Genre', 'dbd'),
    'update_item'       => __('Update Genre', 'dbd'),
    'add_new_item'      => __('Add New Genre', 'dbd'),
    'new_item_name'     => __('New Genre Name', 'dbd'),
    'menu_name'         => __('Genres', 'dbd'),
    'not_found'         => __('No genres found.', 'dbd'),
    'back_to_items'     => __('Back to Genres', 'dbd'),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array('slug' => 'genre'),
  );

  register_taxonomy('genre', array('post'), $args);

  unset($args);
  unset($labels);
}
add_action('init', 'dbd_create_genre_taxonomy', 0);
