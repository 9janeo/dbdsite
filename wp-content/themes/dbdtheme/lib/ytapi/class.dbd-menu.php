<?php
defined('ABSPATH') or die('Cant access this file directly');

class Dbd_Menu
{
  public static function admin_menu()
  {
    Dbd_Menu::load_menu();
    Dbd_Menu::load_submenu();
  }

  public static function load_menu()
  {
    $hook = add_menu_page(
      __('Dbd Admin Page', 'disbydem'),
      __('Dbd', 'disbydem'),
      'manage_options',
      'dbd_admin_menu',
      array('Dbd_Admin', 'display_page'),
      'dashicons-share-alt',
      76
    );

    if ($hook) {
      add_action("load-$hook", array('Dbd_Menu', 'admin_help'));
    }
  }

  public static function load_submenu()
  {
    $hook = add_submenu_page(
      'dbd_admin_menu',
      __('Channels', 'disbydem'),
      __('Dbd Socials', 'disbydem'),
      'manage_options',
      'dbd_channels',
      array('Dbd_Admin', 'display_channel_settings'),
    );

    $hook2 = add_submenu_page(
      'dbd_admin_menu',
      __('Video Analytics', 'disbydem'),
      __('Video Analytics', 'disbydem'),
      'manage_options',
      'video-analytics',
      array('Dbd_Admin', 'display_analytics'),
    );

    if ($hook) {
      add_action("load-$hook", array('Dbd_Menu', 'admin_help'));
    }
    if ($hook2) {
      add_action("load-$hook2", array('Dbd_Menu', 'admin_help'));
    }
  }

  /**
   * Add help to the Dbd page
   *
   * @return false if not the Dbd page
   */
  public static function admin_help()
  {
    $current_screen = get_current_screen();

    // Screen Content
    if (current_user_can('manage_options')) {
      if (!Dbd_Youtube::API_KEY || (isset($_GET['view']) && $_GET['view'] == 'start')) {
        //setup page
        $current_screen->add_help_tab(
          array(
            'id'    => 'overview',
            'title'    => __('Overview', 'disbydem'),
            'content'  =>
            '<p><strong>' . esc_html__('Dbd Setup', 'disbydem') . '</strong></p>' .
              '<p>' . esc_html__('Dbd filters out spam, so you can focus on more important things.', 'disbydem') . '</p>' .
              '<p>' . esc_html__('On this page, you are able to set up the Dbd plugin.', 'disbydem') . '</p>',
          )
        );

        $current_screen->add_help_tab(
          array(
            'id'    => 'setup-signup',
            'title'    => __('New to Dbd', 'disbydem'),
            'content'  =>
            '<p><strong>' . esc_html__('Dbd Setup', 'disbydem') . '</strong></p>' .
              '<p>' . esc_html__('You need to enter an API key to activate the Dbd service on your site.', 'disbydem') . '</p>' .
              '<p>' . sprintf(__('Sign up for an account on %s to get an API Key.', 'disbydem'), '<a href="https://dbd.com/plugin-signup/" target="_blank">Dbd.com</a>') . '</p>',
          )
        );

        $current_screen->add_help_tab(
          array(
            'id'    => 'setup-manual',
            'title'    => __('Enter an API Key', 'disbydem'),
            'content'  =>
            '<p><strong>' . esc_html__('Dbd Setup', 'disbydem') . '</strong></p>' .
              '<p>' . esc_html__('If you already have an API key', 'disbydem') . '</p>' .
              '<ol>' .
              '<li>' . esc_html__('Copy and paste the API key into the text field.', 'disbydem') . '</li>' .
              '<li>' . esc_html__('Click the Use this Key button.', 'disbydem') . '</li>' .
              '</ol>',
          )
        );
      } elseif (isset($_GET['view']) && $_GET['view'] == 'stats') {
        //stats page
        $current_screen->add_help_tab(
          array(
            'id'    => 'overview',
            'title'    => __('Overview', 'disbydem'),
            'content'  =>
            '<p><strong>' . esc_html__('Dbd Stats', 'disbydem') . '</strong></p>' .
              '<p>' . esc_html__('Dbd filters out spam, so you can focus on more important things.', 'disbydem') . '</p>' .
              '<p>' . esc_html__('On this page, you are able to view stats on spam filtered on your site.', 'disbydem') . '</p>',
          )
        );
      } else {
        //configuration page
        $current_screen->add_help_tab(
          array(
            'id'    => 'overview',
            'title'    => __('Overview', 'disbydem'),
            'content'  =>
            '<p><strong>' . esc_html__('Dbd Configuration', 'disbydem') . '</strong></p>' .
              '<p>' . esc_html__('Dbd filters out spam, so you can focus on more important things.', 'disbydem') . '</p>' .
              '<p>' . esc_html__('On this page, you are able to update your Dbd settings and view spam stats.', 'disbydem') . '</p>',
          )
        );

        $current_screen->add_help_tab(
          array(
            'id'    => 'Dbd',
            'title'    => __('Dashboard', 'disbydem'),
            'content'  =>
            '<p><strong>' . esc_html__('Dbd Configuration', 'disbydem') . '</strong></p>' .
              (Dbd_Youtube::API_KEY ? '' : '<p><strong>' . esc_html__('API Key', 'disbydem') . '</strong> - ' . esc_html__('Enter/remove an API key.', 'disbydem') . '</p>') .
              '<p><strong>' . esc_html__('Comments', 'disbydem') . '</strong> - ' . esc_html__('Show the number of approved comments beside each comment author in the comments list page.', 'disbydem') . '</p>' .
              '<p><strong>' . esc_html__('Strictness', 'disbydem') . '</strong> - ' . esc_html__('Choose to either discard the worst spam automatically or to always put all spam in spam folder.', 'disbydem') . '</p>',
          )
        );

        if (!Dbd_Youtube::API_KEY) {
          $current_screen->add_help_tab(
            array(
              'id'    => 'account',
              'title'    => __('Account', 'disbydem'),
              'content'  =>
              '<p><strong>' . esc_html__('Dbd Configuration', 'disbydem') . '</strong></p>' .
                '<p><strong>' . esc_html__('Subscription Type', 'disbydem') . '</strong> - ' . esc_html__('The Dbd subscription plan', 'disbydem') . '</p>' .
                '<p><strong>' . esc_html__('Status', 'disbydem') . '</strong> - ' . esc_html__('The subscription status - active, cancelled or suspended', 'disbydem') . '</p>',
            )
          );
        }
      }
    }

    // Help Sidebar
    $current_screen->set_help_sidebar(
      '<p><strong>' . esc_html__('For more information:', 'disbydem') . '</strong></p>' .
        '<p><a href="https://clearcutcomms.ca/dbd/faq/" target="_blank">'     . esc_html__('Dbd FAQ', 'disbydem') . '</a></p>' .
        '<p><a href="https://clearcutcomms.ca/dbd/support/" target="_blank">' . esc_html__('Dbd Support', 'disbydem') . '</a></p>'
    );
  }
}
