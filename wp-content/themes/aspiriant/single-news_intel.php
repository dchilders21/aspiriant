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

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="ninecol first clearfix" role="main">

							<?php while (have_posts()) : the_post(); ?>

							<?php

							//query here for news_intel cpt
							$post_meta_data = get_post_custom($post->ID); // store custom post meta fields in one array 

							// Set variables
							$news_intel_article_source = $post_meta_data['news_intel_article_source'][0];
							$news_intel_article_description = $post_meta_data['news_intel_article_description'][0];
							$news_intel_article_url = $post_meta_data['news_intel_article_url'][0];
							$news_intel_article_date = $post_meta_data['news_intel_article_date'][0];

							?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<header class="article-header">
								
									<h1 class="single-title custom-post-type-title"><?php the_title(); ?> <span><?php echo $bio_accreditation; ?></span></h1>
									
										<div class="navigation">
										<div class="alignleft">
										<?php previous_post('%', 'Previous', 'no'); ?>
										</div>
										<div class="alignright">
										<?php next_post('%', 'Next', 'no'); ?>
										</div>
										</div>
									<p class="byline vcard"><?php
										// printf(__('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span> <span class="amp">&</span> filed under %4$s.', 'bonestheme'), get_the_time('Y-m-j'), get_the_time(__('F jS, Y', 'bonestheme')), bones_get_the_author_posts_link(), get_the_term_list( $post->ID, 'custom_cat', ' ', ', ', '' ));
									?></p>

								</header> <!-- end article header -->

								<section class="entry-content clearfix">

									<?= '<p>' . $news_intel_article_source . '</p>'; ?>
									<?= '<p>' . $news_intel_article_description . '</p>'; ?>
									<?= '<p>' . $news_intel_article_url . '</p>'; ?>
									<?= '<p>' . $news_intel_article_date . '</p>'; ?>

								</section> <!-- end article section -->

								<footer class="article-header">
									<!-- <p class="tags"><?php // echo get_the_term_list( get_the_ID(), 'custom_tag', '<span class="tags-title">' . __('Custom Tags:', 'bonestheme') . '</span> ', ', ' ) ?></p> -->

								</footer> <!-- end article footer -->

								<?php comments_template(); ?>

							</article> <!-- end article -->

							<?php endwhile; ?>

						</div> <!-- end #main -->
						
							<?php 
							// get_template_part( 'sidebar', 'news_intel' );                    		
							//pull in all content that has this category checked
							//if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage Highlight') ) : ?>
						

						<?php // get_sidebar();	?>

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
