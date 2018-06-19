<?php 
/*
Custom template for videos

*/

get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

					<div id="main" class="eightcol first clearfix" role="main">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="entry-title single-title" itemprop="headline"><?php the_title(); ?></h1>
								
									<?php
									// retrieve the metavalue for video vode
									echo '<p>';
									$video_code = get_post_meta( $post->ID, 'video_code', true);
									$video_description = get_post_meta( $post->ID, 'video_description', true);
									$video_date = get_post_meta( $post->ID, 'video_date', true);
									echo '</p>';

									?>	

								</header> <!-- end article header -->

								<section class="entry-content clearfix" itemprop="articleBody">
									<?php 
									if ($video_date) {
										echo '<p>' . date('F j, Y, $video_date') . '</p>';
									}
									if ($video_description) {
										echo '<p>' . $video_description . '</p>';
									}
									echo $video_code;
									?>
								</section> <!-- end article section -->

								<footer class="article-footer">
									<?php the_tags('<p class="tags"><span class="tags-title">' . __('Tags:', 'bonestheme') . '</span> ', ', ', '</p>'); ?>

								</footer> <!-- end article footer -->

								<?php comments_template(); ?>

							</article> <!-- end article -->

						<?php endwhile; ?>

						<?php else : ?>

							<article id="post-not-found" class="hentry clearfix">
									<header class="article-header">
										<h1 class="page-title" ><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
									</header>
									<section class="entry-content">
										<p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
									</section>
									<footer class="article-footer">
											<p><?php _e("This is the error message in the single.php template.", "bonestheme"); ?></p>
									</footer>
							</article>

						<?php endif; ?>

					</div> <!-- end #main -->

					<?php //get_sidebar(); ?>

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
