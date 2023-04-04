<?php
// Sample WP table to display list of videos from the YouTube API

$video_list =  (object) array(
  array(
    'title' => 'Sample Video 1',
    'views' => 1000,
    'analytics_url' => 'https://www.youtube.com/analytics/video/1',
  ),
  array(
    'title' => 'Sample Video 2',
    'views' => 500,
    'analytics_url' => 'https://www.youtube.com/analytics/video/2',
  ),
  array(
    'title' => 'Sample Video 3',
    'views' => 2000,
    'analytics_url' => 'https://www.youtube.com/analytics/video/3',
  ),
);

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
      <?php
      ?>
      <?php if (!(property_exists($video_list, 'error'))) :
        foreach ($video_list as $video) : ?>
          <tr>
            <td><?php echo $video['title']; ?></td>
            <td><?php echo $video['views']; ?></td>
            <td><a href="<?php echo $video['analytics_url']; ?>">View Analytics</a></td>
          </tr>
        <?php endforeach;
      else : ?>
        <tr>
          <td><?php echo $video_list->error->message ?></td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>