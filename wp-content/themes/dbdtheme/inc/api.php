<?php 
/**
 * Add video_schema post meta to post on save or update based on schema changes
 * @param [type] $post_id [description]
 */
function dis_by_dem_video_info($post_id){
	$hostname = get_site_url();
	$yt_key = get_site_option('Youtube_API_key');
	
  if(get_field('video_link')):
    $url = get_field('video_link');
    parse_str(parse_url($url, PHP_URL_QUERY), $arr_of_vars );
    $id = $arr_of_vars['v'];

    $req_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=".$id."&key=".$yt_key;
    $response = wp_remote_get($req_url);
    $code = wp_remote_retrieve_response_code($response);
    $result = json_decode(wp_remote_retrieve_body( $response ));
    if ($code == 200){
      $vid_snippet = $result->items[0]->snippet;
      $vid_tags = $vid_snippet->tags;
      // prepare schema values
      $thumb = 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg';
      $name = 'Dis By Dem Video Link' ;
      $description = 'Created by Tale Adewole';
      $published = get_the_date();
      $upload_date = date("Y-m-d", strtotime($published));
      // build schema for video
      $vid_schema = array(
        '@type' 				=> 'VideoObject',
        '@id' 					=> $hostname.'#/schema/VideoObject/{{'.$id.'}}',
        'name' 					=> $name,
        'description' 	=> $description,
        'thumbnail' 		=> $thumb,
        'uploadDate' 		=> $upload_date
      );
      // Set both video schema and info meta:
      $metaValues = array(
        'post_video_schema' => $vid_schema,
        'video_info' => $vid_snippet,
      );
      foreach ($metaValues as $metaKey => $metaValue) {
        update_post_meta($post_id, $metaKey, $metaValue);
      }

      //Pull tags from YT and add them to existing post tags
      $post_tags = get_the_tags($post_id);
      $new_tags = [];
      foreach($vid_tags as $key => $tag) {
        if(!($post_tags) || !in_array($tag, $post_tags)){
          $new_tags[] = $tag;
        }
      }
      if(!empty($new_tags)){
        wp_set_post_tags($post_id, $new_tags, true);
      }
    }
    
  endif;
}
add_action( 'save_post','dis_by_dem_video_info', 10, 2);
