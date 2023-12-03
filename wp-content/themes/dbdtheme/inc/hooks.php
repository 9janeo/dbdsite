<?php

/**
 * Custom hooks
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (!function_exists('understrap_site_info')) {
  /**
   * Add site info hook to WP hook library.
   */
  function understrap_site_info()
  {
    do_action('understrap_site_info');
  }
}

add_action('understrap_site_info', 'understrap_add_site_info');
if (!function_exists('understrap_add_site_info')) {
  /**
   * Add site info content.
   */
  function understrap_add_site_info()
  {
    $the_theme = wp_get_theme();

    $site_info = sprintf(
      '%1$s<span class="sep"> </span>%2$s (%3$s).',
      sprintf(
        /* translators: WordPress */
        esc_html__(' %s', 'understrap'),
        $the_theme->get('Name') . ' Theme',
      ),
      sprintf( // WPCS: XSS ok.
        /* translators: 1: Theme name, 2: Theme author */
        esc_html__(' Created by %1$s', 'understrap'),
        '<a href="' . esc_url(__('https://clearcutcomms.ca/', 'understrap')) . '">Clear Cut Communications</a>'
      ),
      sprintf( // WPCS: XSS ok.
        /* translators: Theme version */
        esc_html__('&copy;%1$s', 'understrap'),
        date("Y")
        // $the_theme->get( 'Version' )
      )
    );

    // Check if customizer site info has value.
    if (get_theme_mod('understrap_site_info_override')) {
      $site_info = get_theme_mod('understrap_site_info_override');
    }

    echo apply_filters('understrap_site_info_content', $site_info); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

  }
}
