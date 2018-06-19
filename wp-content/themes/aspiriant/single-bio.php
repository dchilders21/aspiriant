<?php
/*
This is the custom post type post template.
If you edit the post type name, you've got
to change the name of this template to
reflect that name change.

i.e. if your custom post type is called
register_post_type( 'bookmarks',
then your single template should be
single-bookmarks.php

*/
?>

<?php get_header(); ?>

<?php

$term_name = get_query_var( 'c' ); //get query string 

$post = $wp_query->post;

// Set variables 
$termName = wp_get_object_terms( $post->ID, 'bio-types' );
$termSlug = $termName[0]->slug;
$link = get_permalink() . '?c=' . $term_name;

// get_posts in same custom taxonomy
$items = get_posts( array(
	'post_type'   => 'bio',
	'numberposts' => -1,
	'taxonomy'    => 'bio-types',
	'term'        => $term_name,
	'orderby' => 'meta_value', 
	'meta_key' => 'bio_last_name',
	'order'=> 'ASC'
	) );

// get ids of posts retrieved from get_posts
$ids = array();
foreach ($items as $item) {
	$ids[] = $item->ID;
}

$count =  count( $items );

//get the title of the last item with that term
$first_person = $items[0]->post_title;
$last_person = $items[$count-1]->post_title;

?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="twelvecol first clearfix" role="main">
							<?php 
							//add counter to identify when post is the first being shown 
							$counter = 1;
							?>

							<?php while (have_posts()) : the_post(); ?>

							<?php


							$post_meta_data = get_post_custom($post->ID); // store custom post meta fields in one array 

							// Set variables 
							$bio_job_title = $post_meta_data['bio_job_title'][0];
							$bio_job_title_2 = $post_meta_data['bio_job_title_2'][0];
							$bio_accreditation = $post_meta_data['bio_accreditation'][0];
							$bio_email = $post_meta_data['bio_email'][0];
							$bio_more_resources = $post_meta_data['bio_more_resources'][0];
							$bio_full_bio = $post_meta_data['bio_full_bio'][0];
							

							?>
						<div id="main" class="eightcol first clearfix" role="main">

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<header class="article-header">
									<?php edit_post_link('Edit'); ?>

									<h1 class="single-title custom-post-type-title"><?php the_title(); ?> <span class="accreditation"><?php echo $bio_accreditation; ?></span><br />
									<span class="job-title"><?php if ($bio_job_title) { echo $bio_job_title; }
										  if ($bio_job_title_2) { echo $bio_job_title_2; }
									?>
									</span>

									</h1>
									<?php single_term_title();?>
									<div class="navigation">

										<?php
										
										// get and echo previous and next post in the same taxonomy        
										$thisindex = array_search($post->ID, $ids);
										$previd = $ids[$thisindex-1];
										$nextid = $ids[$thisindex+1];
										
										?>
										
										<div class="alignleft prev-link">
										
											<?php 
											
											if ( !empty($previd) ) {
												echo '<a rel="prev" href="' . get_permalink($previd). '?c=' . $term_name . '">previous</a>';
											}

											?>

										</div>

										<div class="alignright next-link">
										
											<?php

											if ( !empty($nextid) ) {
												echo '<a rel="next" href="' . get_permalink($nextid). '?c=' . $term_name . '">next</a>';
											}

											?>

										</div>

									</div>

								</header> <!-- end article header -->

								<section class="entry-content clearfix">

									<div class="bio-image">
										<?php the_post_thumbnail('full'); ?>
									</div>
		
										<div id="bio">
										<?php the_content(); ?>
										</div>
										<div id="full-bio">
										<?php echo $bio_full_bio; ?>
										</div>

										<?php 
										if ($bio_full_bio) {
											echo '<p class="alignright"><a href="#" class="read-more">Read Full Bio</a></p>
';
										}
										?>

								</section> <!-- end article section -->

								<footer class="article-header">
									<!-- <p class="tags"><?php // echo get_the_term_list( get_the_ID(), 'custom_tag', '<span class="tags-title">' . __('Custom Tags:', 'bonestheme') . '</span> ', ', ' ) ?></p> -->

								</footer> <!-- end article footer -->

								<?php comments_template(); ?>

							</article> <!-- end article -->

							</div>

							<?php 

							$counter++;

							endwhile; 
							
							get_sidebar('bio');
							
							?>
						
						</div> <!-- end #main -->

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
