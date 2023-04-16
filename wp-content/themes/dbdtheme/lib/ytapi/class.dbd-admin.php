<?php
defined('ABSPATH') or die('Cant access this file directly');
class Dbd_Admin
{
  // initialize class constants
  const CHANNEL_ID = 'UCglE7vDtPHuulBhLvn9Q-eg';
  const CHANNEL_NAME = 'DISBYDEM';
  const API_KEY = 'AIzaSyAfiysBRyIIHIUsenOXURi2xRTRtWBn2A4';

  // initialize class variables
  private static $initiated = false;
  private static $notices   = array();

  public static function init()
  {
    if (!file_exists(get_stylesheet_directory() . '/vendor/autoload.php')) {
      throw new Exception(sprintf('This site does not have the required package for YouTube functions. Please run "composer require google/apiclient:~2.0" in "%s"', (get_stylesheet_directory() . '/vendor/autoload.php')));
    }
    require_once(get_stylesheet_directory() . '/vendor/autoload.php');

    // require_once('yt-service.php');
    require_once('class.dbd-youtube.php');
    require_once('class.dbd-menu.php');

    if (!self::$initiated) {
      self::init_hooks();
    }
  }

  /**
   * Initializes WordPress hooks
   */
  private static function init_hooks()
  {
    self::$initiated = true;

    Dbd_Youtube::init();
    add_action('admin_menu', array('Dbd_Menu', 'admin_menu'));

    // load client
    // set up client scopes
    // set client Access type
    // load client service account credentials from json
    // get channel Id
    // Get channel username
  }

  public static function display_start_page()
  {
    echo '<h3>DisByDem Admin: Display start page</h3>';
    if (isset($_GET['action'])) {
      if ($_GET['action'] == 'delete-key') {
        if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], self::NONCE))
          delete_option('wordpress_api_key');
      }
    }

    if ($api_key = Vortex::get_api_key() && (empty(self::$notices['status']) || 'existing-key-invalid' != self::$notices['status'])) {
      self::display_configuration_page();
      return;
    }

    $vortex_user = false;

    Dbd_Admin::view('start', compact('vortex_user'));
  }

  public static function display_page()
  {
    // ToDo: rewrite url for vortex admin settings page
    if (!Dbd_Admin::API_KEY || (isset($_GET['view']) && $_GET['view'] == 'dbd_admin_menu')) :
      self::display_start_page();
    else :
      self::display_configuration_page();
    endif;
  }

  public static function display_configuration_page()
  {
    $api_key      = Dbd_Admin::API_KEY;
    $akismet_user = 'Beta User';
    echo '<h3>DisByDem Admin: Display configuration page</h3>';
    Dbd_Admin::view('settings', compact('api_key', 'beta_user'));
  }

  public static function display_analytics()
  {
    $api_key      = Dbd_Admin::API_KEY;
    $dbd_user = 'Beta User';
    echo '<h3>DisByDem: YouTube analytics</h3>';
    Dbd_Admin::view('analytics', compact('api_key', 'dbd_user'));
  }

  public static function view($name, array $args = array())
  {
    $args = apply_filters('vortex_view_arguments', $args, $name);

    foreach ($args as $key => $val) {
      $$key = $val;
    }

    load_plugin_textdomain('cc-vortex');

    $file = get_stylesheet_directory() . '/lib/youtube-templates/' . $name . '.php';

    include($file);
  }

  /*
  * Displays youtube playlist
  * ToDo: Convert to use view function
  */
  public static function display_playlists($playlists, $listvideo = false)
  {
    if (isset($playlists) && $playlists) :
      if (!($playlists->error)) : ?>
        <div class="yt playlists row row-cols-2">
          <?php $videos = (object) array();
          foreach ($playlists->items as $key => $playlist) :
            $id = $playlist->id;
            if (!($playlist->contentDetails->itemCount > 0)) {
              continue; # skip playlist if no items in it
            } ?>
            <div class="card pl_<?= $key + 1 ?> mb-3">
              <?php get_template_part('lib/youtube-templates/playlists', 'playlists', $playlist); ?>
              <div class="card-footer py-2">
                <?php if (isset($listvideo) && $listvideo) :
                  // if listvideo parameter is true get the playlist items
                  $videos = DBD_Youtube::get_playlist_items($id);
                ?>
                  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-target="#list_<?php echo $id; ?>" data-bs-target="#list_<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseVideoList">Show videos
                  </button>
                  <div id="list_<?php echo $id ?>" class="collapse show playlist mt-2">
                    <?php get_template_part('lib/youtube-templates/video_list', 'video_list', $videos); ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
<?php endif;
    endif;
  }
}