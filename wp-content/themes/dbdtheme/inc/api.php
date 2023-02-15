<?php 

/**
 * Add video_schema post meta to post on save or update based on schema changes
 * @param [type] $post_ID [description]
 * @param [type] $post    [description]
 */
function dis_by_dem_video_info($post_ID, $post){
	$meta = $post->post_video_schema;
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
      $vid_title = $vid_snippet->title;
      $vid_desc = $vid_snippet->description;
      $vid_published = $vid_snippet->publishedAt;
    }
    
    // prepare schema values
    $thumb = 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg';
    $name = 'Dis By Dem Video Link' ;
    $description = 'Created by Tale Adewole';
    $published = get_the_date();
    $upload_date = date("Y-m-d", strtotime($published));

    $vid_schema = array(
      '@type' 				=> 'VideoObject',
      '@id' 					=> $hostname.'#/schema/VideoObject/{{'.$id.'}}',
      'name' 					=> $name,
      'description' 	=> $description,
      'thumbnail' 		=> $thumb,
      'uploadDate' 		=> $upload_date
    );
    array_push($meta, $vid_schema);
  endif;
  
  update_post_meta($post_ID, 'post_video_schema', $meta);

	// // if acf video schema is present update post_meta else delete post_meta
	// if(! empty($acf_vid) ){
	// 	if($meta !== $acf_vid){
	// 		update_post_meta($post_ID, 'post_video_schema', $acf_vid);
	// 	}
	// 	return;
	// } else {
	// 	delete_post_meta($post_ID, 'post_video_schema');
	// }
}
add_action( 'save_post','dis_by_dem_video_info', 10, 2);
