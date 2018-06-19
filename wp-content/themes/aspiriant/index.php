<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="twelvecol first clearfix" role="main">

							<?php 

							$args = array(
								'post_type' => 'page',
								'post_title' =>'home',
								'posts_per_page' => 1,
							);

							$home = new WP_Query( $args );

							if ($home->have_posts()) : while ($home->have_posts()) : $home->the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<header class="article-header">

									<h1 class="h2"><?php the_title(); ?></h1>

								</header> <!-- end article header -->

								<section class="entry-content clearfix">
									<p><?php the_content(); ?></p>
								</section> <!-- end article section -->

								<footer class="article-footer">
									<p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?></p>

								</footer> <!-- end article footer -->

								<?php // comments_template(); // uncomment if you want to use them ?>

							</article> <!-- end article -->

							<?php endwhile; ?>

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

						<?php //get_sidebar(); ?>
						<?php //if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage Highlight') ) : ?>
						<?php //endif;?> 
						<?php 

						query_posts( array ( 'category_name' => 'homepage-highlight', 'posts_per_page' => 1 ) );
						the_title();
						wp_reset_query()

						?>

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
