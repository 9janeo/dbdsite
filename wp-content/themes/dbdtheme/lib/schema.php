<?php

/**
 * Custom functions to generate and output page schema
 */

/**
 * Helper to output schema arrays as json.
 *
 * @param array $schema
 * @return void
 */
function dbd_schema_array_output($schema = array())
{
  return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

function dbd_single_youtube_post_schema()
{
  if (!is_single()) {
    return;
  }

  $schema = array(
    '@context'         => 'http://schema.org',
    '@type'            => 'BlogPosting',
    'headline'         => get_the_title(),
    'mainEntityOfPage' => get_the_permalink(),
    'publisher'        => array(
      '@type' => 'Organization',
      'name'  => 'DISBYDEM',
      'url'   => 'https://disbydem.com',
      'logo'  => array(
        '@type'  => 'ImageObject',
        'url'    => 'https://disbydem.com/wp-content/themes/dbdtheme/src/images/main_brand.jpg',
        'width'  => '250px',
        'height' => '250px',
      ),
    ),
    'url'              => get_the_permalink(),
    'datePublished'    => get_the_time('Y-m-d'),
    'dateModified'     => get_the_modified_time('Y-m-d'),
    'description'      => get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true),
  );

  $video_schema = get_post_meta(get_the_ID(), 'post_video_schema');
  if ($video_schema) {
    $schema['video'] = get_post_meta(get_the_ID(), 'post_video_schema', true);
  }
  // $schema['video'] = "We dont have @contect or video schema yet";
  echo dbd_schema_array_output($schema);
}
add_action('wp_head', 'dbd_single_youtube_post_schema');
