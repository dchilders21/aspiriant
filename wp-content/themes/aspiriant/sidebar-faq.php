<?php //look for anything that is has category of this person's name
  $slug = $post->post_name; //get the name of the title and match it with category
  //echo $slug;
  $title = get_the_title();
  ?>

  <div id="sidebar1" class="sidebar no-image threecol" role="complementary">

  <?php

  //look for video that has category of this person
  $video_args = array(
      'post_type' => array('video'),
      'posts_per_page' => 1,
      'category_name' => $slug
    );

    $video_more = new WP_Query( $video_args );

$count = 0;
if ($video_more->have_posts()) {
          echo '<h3>Video</h3>';
}

    if ($video_more->have_posts()) : while ($video_more->have_posts()) : $video_more->the_post();
      the_post_thumbnail('full');
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
      'posts_per_page' => -1,
      'category_name' => $slug
    );

    $publications_more = new WP_Query( $publications_args );

$count = 0;
if ($publications_more->have_posts()) {
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
  ?>
</div>