<?php
/*
Template Name: Events
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="twelvecol first clearfix" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title"><?php the_title(); ?></h1>
									


								</header> <!-- end article header -->

								<section class="entry-content clearfix eightcol" itemprop="articleBody">
									<?php 
									the_content(); 

									$args = array(
									    'posts_per_page' => -1,
									    'post_type' => 'video',
									    'post_status' => 'publish',
										'order'=> 'DESC'
									);


									$video_query = new WP_Query( $args );
									
										while ( $video_query->have_posts() ) : $video_query->the_post();

										// store custom post meta fields in one array 
										$post_meta_data = get_post_custom($post->ID);
										// Set variables
										$video_description = $post_meta_data['video_description'][0];
										$video_code = $post_meta_data['video_code'][0];
										
										edit_post_link();

										
										echo '<div class="press-text">';
										if(has_post_thumbnail()) {
											the_post_thumbnail('full', array('class' => 'alignleft'));
										}
				
										echo '<h2 class="video-title">';
										the_title();

										$id = get_the_ID();
								        $cats = wp_get_post_categories($id);
								        //print_r( wp_get_post_categories( $id ) );
										$category = get_category($cats[0]->cat_ID);


										$category_name = get_the_category(); 
										//echo $category_name[0]->cat_name;
										//echo get_cat_name( $category);

										//print_r($category);
										//echo $category->name;
										$cats[0]->name;
										if($category_name[0] && ($category_name[0]->cat_name != 'homepage highlight' || $category_name[0]->cat_name != 'Uncategorized' || $category_name[0]->cat_name != ' ')) {
											echo ' with ' . $category_name[0]->cat_name;
										}

										echo '<a href="' . get_permalink() . '" class="read-more">&nbsp;</a></h2>';
									    echo '<p>' . the_excerpt() . '</p>';

										echo '</div>';
										echo '<br class="clearfix">';

										endwhile;
										wp_reset_query();

									?>
								</section> <!-- end article section -->

								<div class="threecol sidebar-container">
									<?php 
									get_template_part( 'sidebar', 'intel' );  ?>                   		
								</div>

								<footer class="article-footer">
									<p class="clearfix"><?php the_tags('<span class="tags">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?></p>

								</footer> <!-- end article footer -->

							</article> <!-- end article -->

							<?php endwhile; else : ?>

									<article id="post-not-found" class="hentry clearfix">
											<header class="article-header">
												<h1><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e("This is the error message in the page-custom.php template.", "bonestheme"); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div> <!-- end #main -->

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
