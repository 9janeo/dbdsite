<?php
// video list template

$video_list = $args;

?>
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
    // print_r("video_list: \n");
    // var_dump($video_list)
    ?>
    <?php if (!(property_exists($video_list, 'error'))) :
      foreach ($video_list as $video) : ?>
        <tr>
          <td><?php echo $video->snippet->title; ?></td>
          <td><?php echo $video->snippet->views; ?></td>
          <td><a href="<?php echo 'analytics_url'; ?>">View Analytics</a></td>
        </tr>
      <?php endforeach;
    else : ?>
      <tr>
        <td><?php echo $video_list->error->message ?></td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>