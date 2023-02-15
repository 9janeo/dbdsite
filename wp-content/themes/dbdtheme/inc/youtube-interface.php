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

    // Get the list of videos from the YouTube API
    $video_list = get_videos_from_youtube_api();

    ?>
    <div class="wrap">
        <h1>Video Analytics</h1>
        <!-- <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col">Video Title</th>
                    <th scope="col">Views</th>
                    <th scope="col">Analytics</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($video_list as $video) : ?>
                    <tr>
                        <td><?php echo $video['title']; ?></td>
                        <td><?php echo $video['views']; ?></td>
                        <td><a href="<?php echo $video['analytics_url']; ?>">View Analytics</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> -->
    </div>
    <?php
}

// Retrieve the list of videos from the YouTube API
function get_videos_from_youtube_api() {
    // Replace this with your own code to make a request to the YouTube API
		$yt_key = get_field('Youtube_API_key', 'options');
		$req_url = "https://www.googleapis.com/youtube/v3/videos?key=".$yt_key."&channelId=DISBYDEM";
		$response = wp_remote_get($req_url);
		$code = wp_remote_retrieve_response_code($response);
		$result = json_decode(wp_remote_retrieve_body( $response ));
		print_r($result);
		if ($code == 200){
			$videos = $result;
			// $vid_snippet = $result->items[0]->snippet;
			// update_post_meta($post_ID, 'video_info', $vid_snippet);
			// $vid_title = $vid_snippet->title;
			// $vid_desc = $vid_snippet->description;
			// $vid_published = $vid_snippet->publishedAt;
			return print_r($videos);
		}
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