<?php
  
?>
  <section class="page-block row">
    <?php if(have_rows('list')): ?>
      <!-- <div class="row"> -->
      <?php
        while( have_rows('list') ): the_row();
          $list = get_field('list');
          $list_name = get_sub_field('title');
          $category = get_sub_field('category');
          $rankings = get_sub_field('rankings');
          
          // $args = array('cat' => $current_cat_id, 'orderby' => 'post_date', 'order' => 'DESC', 'posts_per_page' => $showposts,'post_status' => 'publish', 'category__and' => $rankings);
          $args = array('order' => 'DESC', 'post__in' => $rankings);
          
          $posts = get_posts($args);
          set_query_var('class', 'list_item');
        ?>
        <div class="post-list row">
          <?php
            if($list_name){
              echo '<h3 class="header justify-center">'.$list_name.'</h3>';
            }
            foreach($posts as $post){ 
            echo '<div class="list-item col-lg-4">';
          ?>
              <?php get_template_part('loop-templates/content', 'card'); ?>
            <?php 
              echo '</div>';
            }
          ?>
        </div><!-- end post-list -->
        <?php
          wp_reset_postdata();
          wp_reset_query();          
        endwhile;
        // echo '</div>';
      endif;
    ?>
  </section>
<?php ?>