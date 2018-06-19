<?php //look for anything that is has category of this person's name
$slug = $post->post_name; //get the name of the title and match it with category
//echo $slug;
$title = get_the_title();
//look for posts that have category of the $slug
$staff_args = array(
    'post_type' => array('news_intel'),
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'category_name' => $slug
  );
$staff_more = new WP_Query( $staff_args );
$counter = 0;
if ($staff_more->have_posts()) {
  $news = true;
  if ($counter == 0 ) {
    echo '<div class="threecol sidebar-container">';
    echo '<div id="sidebar1" class="sidebar" role="complementary">';
  }
  echo '<h3>More from ' . $title . '</h3>';
  echo '<h3>Press</h3>';
}
if ($staff_more->have_posts()) : while ($staff_more->have_posts()) : $staff_more->the_post();
    the_title('<h4>', '</h4>');
    the_excerpt();
    the_time('F j, Y');
    echo '<br /><a class="read-more" href="' . get_post_permalink() . '">Read</a>';
    $counter++;
  endwhile;
endif;
  //look for video that has category of this person
  $video_args = array(
      'post_type' => array('video'),
      'post_status' => 'publish',
      'posts_per_page' => 1,
      'category_name' => $slug
    );
  $video_more = new WP_Query( $video_args );
$count = 0;
if ($video_more->have_posts()) {
    $videos = true;
          echo '<h3>Video</h3>';
}
    if ($video_more->have_posts()) : while ($video_more->have_posts()) : $video_more->the_post();
      the_title('<h4>', '</h4>');
      the_excerpt();
      the_time('F j, Y');
      echo '<br /><a class="read-more" href="' . get_post_permalink() . '">Watch</a>';
      $count = $count++;
    endwhile;
  endif;
  //look for publications that have category of this person
  $publications_args = array(
      'post_type' => array('publications'),
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'category_name' => $slug
    );
    $publications_more = new WP_Query( $publications_args );
$count = 0;
if ($publications_more->have_posts()) {
      $publications = true;
      if ($count=1) 
        {
          echo '<h3>Whitepaper</h3>';
        } else {
          echo '<h3>Whitepapers</h3>';
        }
}
    if ($publications_more->have_posts()) : while ($publications_more->have_posts()) : $publications_more->the_post();
      the_title('<h4>', '</h4>');
      the_excerpt();
      the_time('F j, Y');
      echo '<br /><a class="read-more" href="' . get_post_permalink() . '">Read</a>';
      $count = $count++;
    endwhile;
  endif;
  $more_resources = get_post_meta($post->ID, 'bio_more_resources', true);
  if ($more_resources) { ?>
  <h1 class="h3">More from
    <?php the_title();
?>
  </h1>
  <?php echo '<p>' . $more_resources . '</p>';
?>
  <?php
    $args = array(
      'post_type' => array('post', 'page', 'bio'),
      'post_status' => 'publish',
      'posts_per_page' => 1,
      'category_name' => $slug
    );

$more = new WP_Query( $args );

if ($more->have_posts()) : while ($more->have_posts()) : $more->the_post();
the_title();
the_excerpt();
endwhile;
endif;
}
//more resources
  // $args_bookmarks = array(
  // 'orderby'          => 'link_id',
  // 'order'            => 'ASC',
  // 'limit'            => 3,
  // 'exclude_category' => 21,
  // 'hide_invisible'   => 1,
  // 'show_updated'     => 0,
  // 'echo'             => 1,
  // 'categorize'       => 1,
  // 'title_li'         => __('Press'),
  // 'title_before'     => '<h2>',
  // 'title_after'      => '</h2>',
  // 'category_orderby' => 'name',
  // 'category_order'   => 'ASC',
  // 'class'            => 'linkcat',
  // 'category_before'  => '',
  // 'category_after'   => '' );

// wp_list_bookmarks( $args_bookmarks );
if ( $news == true || $videos == true || $publications == true ) {
echo '</div>';
echo '</div>';
}

?>