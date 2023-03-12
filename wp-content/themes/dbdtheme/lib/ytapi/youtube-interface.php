<?php

// Register the options page for viewing video analytics
add_action('admin_menu', 'register_video_analytics_page');
function register_video_analytics_page() {
    add_options_page(
        'Video Analytics',
        'Video Analytics',
        'manage_options',
        'video-analytics',
        'display_video_analytics'
    );
}

// Display the video analytics page
function display_video_analytics() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // initialize YouTube key
    $yt_key = get_field('Youtube_API_key', 'options');
?>

    <div class="wrap">
        <h1>Settings</h1>
        <form role="presentation" action="yt_settings">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="channel_id">Channel ID</label></th>
                        <td><input name="channelid" type="text" aria-describedby="youtube-channel-id" name="channel_id" id="channel_id" placeholder="YouTube channel id" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="channel_name">Channel Name</label></th>
                        <td><input type="text" name="channel_name" id="channel_name" class="regular-text"></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" class="button button-primary" value="save">
                            <input type="reset" name="reset" class="button button-danger" value="clear">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <hr>
    
<?php


    // Get the list of videos from the YouTube API
    $video_list = get_videos_from_youtube_api($yt_key);
    print_r("video_list:- \n");
    var_dump($video_list);

    ?>
    <div class="wrap">
        <h1>Video Analytics</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col">Video Title</th>
                    <th scope="col">Views</th>
                    <th scope="col">Analytics</th>
                </tr>
            </thead>
            <tbody>
                <?php print_r("video_list: \n") ?>
                <?php var_dump($video_list) ?>
                <?php if (!($video_list->error)) :
                    foreach ($video_list as $video) : ?>
                        <tr>
                            <td><?php echo $video['title']; ?></td>
                            <td><?php echo $video['views']; ?></td>
                            <td><a href="<?php echo $video['analytics_url']; ?>">View Analytics</a></td>
                        </tr>
                        <tr>
                            <td><?php echo $video['title']; ?></td>
                            <td><?php echo $video['views']; ?></td>
                            <td><a href="<?php echo $video['analytics_url']; ?>">View Analytics</a></td>
                        </tr>
                    <?php endforeach; 
                else: ?>
                        <tr><td><?php echo $video_list->error->message ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Retrieve the list of videos from the YouTube API
function get_videos_from_youtube_api($key) {
    // Replace this with your own code to make a request to the YouTube API
		
		$req_url = "https://www.googleapis.com/youtube/v3/videos?key=".$key."&part=snippet,contentDetails,statistics&Id=UC_x5XG1OV2P6uZZ5FSM9Ttw";
		// $req_url = "https://www.googleapis.com/youtube/v3/videos?key=".$key;
		$response = wp_remote_get($req_url);
		$code = wp_remote_retrieve_response_code($response);
        var_dump($code);
        print_r("<br> === <br> \n");
        $body = wp_remote_retrieve_body( $response );
        var_dump($body);
        print_r("<br> === <br> \n");
		$result = json_decode($body);
        print_r("<br> \n");
		// if ($code == 200){
		// 	$videos = $result;
		// 	// $vid_snippet = $result->items[0]->snippet;
		// 	// update_post_meta($post_ID, 'video_info', $vid_snippet);
		// 	// $vid_title = $vid_snippet->title;
		// 	// $vid_desc = $vid_snippet->description;
		// 	// $vid_published = $vid_snippet->publishedAt;
        //     print_r("get_videos_from_youtube > result <br> \n");
        //     var_dump($result);
        //     print_r("<br> \n");
		// }
        return $result;
    // and retrieve the list of videos, views, and analytics URL
		// GET https://www.googleapis.com/youtube/v3/videos

    // For example purposes, let's just return a dummy list of videos
		// return print_r($videos);
    // return array(
    //     array(
    //         'title' => 'Sample Video 1',
    //         'views' => 1000,
    //         'analytics_url' => 'https://www.youtube.com/analytics/video/1',
    //     ),
    //     array(
    //         'title' => 'Sample Video 2',
    //         'views' => 500,
    //         'analytics_url' => 'https://www.youtube.com/analytics/video/2',
    //     ),
    //     array(
    //         'title' => 'Sample Video 3',
    //         'views' => 2000,
    //         'analytics_url' => 'https://www.youtube.com/analytics/video/3',
    //     ),
    // );
}