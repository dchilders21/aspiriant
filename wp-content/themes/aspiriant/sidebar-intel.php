<?php 
  $slug = $post->post_name; //get the name of the title in order to display items with this category
  //$title = get_the_title();
  ?>

  <div id="sidebar1" role="complementary">

  <?php

  //look for news items that have category of this page
  $news_args = array(
      'post_type' => 'news_intel',
      'posts_per_page' => 2,
      'category_name' => $slug
    );

    $news_more = new WP_Query( $news_args );

$count = 0;
if ($news_more->have_posts()) {
          echo '<h3 class="h4">Aspiriant in the News</h3>';
}

    if ($news_more->have_posts()) : while ($news_more->have_posts()) : $news_more->the_post();
      // store custom post meta fields in one array 
      $post_meta_data = get_post_custom($post->ID);
      // Set variables
      $intel_article_source = $post_meta_data['news_intel_article_source'][0];
      $intel_article_description = $post_meta_data['news_intel_article_description'][0];
      $intel_article_url = $post_meta_data['news_intel_article_url'][0];
      $intel_article_date = $post_meta_data['news_intel_article_date'][0];
      $intel_article_date = strtotime( $intel_article_date );
      $intel_article_date = date( 'F j, Y', $intel_article_date );
      the_title('<h4 class="h5">', '</h4>');
      echo'<p>' . $intel_article_source . ', ' . '<br />' . $intel_article_date . '</p>';
      echo '<br /><a class="read-more" href="' . get_post_permalink() . '">Read</a>';
      $count = $count++;
    endwhile;
  endif;
?>

</div>