<?php 
/*
Template Name: Home Page
*/
	get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="twelvecol first clearfix" role="main">

							<?php 

							$args = array(
								'post_type' => 'page',
								// 'post_id' => 53,
								'name' =>'home',
								'posts_per_page' => 1,
							);

							$home = new WP_Query( $args );

							if ($home->have_posts()) : while ($home->have_posts()) : $home->the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<header class="article-header">
									<h1 class="h2"><?php //the_title(); ?></h1>
								</header> <!-- end article header -->

								<section class="entry-content clearfix">
									<p><?php the_content(); ?></p>
								</section> <!-- end article section -->

								<?php //the_post_thumbnail('full'); ?>

								<footer class="article-footer">
									<p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?></p>
								</footer> <!-- end article footer -->

								<?php // comments_template(); // uncomment if you want to use them ?>

							</article> <!-- end article -->

							<?php endwhile; 
							?>

									<?php if (function_exists('bones_page_navi')) { ?>
											<?php bones_page_navi(); ?>
									<?php } else { ?>
											<nav class="wp-prev-next">
													<ul class="clearfix">
														<li class="prev-link"><?php next_posts_link(__('&laquo; Older Entries', "bonestheme")) ?></li>
														<li class="next-link"><?php previous_posts_link(__('Newer Entries &raquo;', "bonestheme")) ?></li>
													</ul>
											</nav>
									<?php } ?>

							<?php endif; ?>

						</div> <!-- end #main -->
					</div><!--end of content -->
				</div> <!-- end #inner-content -->

						<?php //get_sidebar(); ?>
						<?php //if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage Highlight') ) : ?>
						<?php //endif;?> 

								<?php 

								$args = array(
										'post_type' => 'post',
										'cat' =>'4',
										'posts_per_page' => 1,
									);

								$featured = new WP_Query( $args );

								if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post(); 
									echo '<div class="twelvecol feature-section">
										<div class="featured wrap">
										<article>';
										edit_post_link();
									the_post_thumbnail('full', array('class' => 'alignleft'));
								
									the_title('<h1 class="h3">', '</h1>');
								
									the_content();
									echo '	</article>
											</div><!--end of featured-->
											</div> <!-- end #content -->';							
									endwhile;
								endif;
								?>

						

<?php get_footer(); ?>
