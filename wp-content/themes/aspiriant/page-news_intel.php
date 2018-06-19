<?php
/*
Template Name: News & Intel Page
*/
?>

<?php get_header(); ?>

	<div id="content">

		<div id="inner-content" class="wrap clearfix">

			<?php 
			//layout rules
			//if there is sidebar content and there's a featured image them make div span twelve col if not 
			// then div for featured image and content should only be ninecol
			$sidebar =  get_post_meta($post->ID, 'sidebar_info', true); // the event is housed on an external page

			
			if ($sidebar && has_post_thumbnail()) {
				echo '<div class="twelvecol first clearfix" role="main">';
			} else {
				echo '<div class="twelvecol first clearfix" role="main">';
			} ?>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<?php 
					//if alternate title get it
					$alternate_title = get_post_meta($post->ID, 'tagline_info', true);

					if ($alternate_title) {
					?>
						<header class="article-header">
							<h1 class="page-title" itemprop="headline"><?php echo $alternate_title; ?></h1>
						</header>
					<?php } else {?>
						<header class="article-header">
							<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
						</header>

					<?php }?>

					<?php if (has_post_thumbnail()) {
						the_post_thumbnail('full'); 
					} ?>

					<div id="main" class="eightcol">
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
							<section class="entry-content clearfix" itemprop="articleBody">
								
								<?php the_content(); ?>

								<div class="twelvecol first">

								    <div class="fivecol first">
								    
								        <h1>Latest Press</h1><p>What our people are saying in the <a class="read-more" href="/news-intel/press/">press</a></p>

										<?php

									    $args_press = array(
										    'posts_per_page' => 2,
										    'post_type' => 'news_intel',
										    'post_status' => 'publish',
										    'tax_query' => array(
										        array(
										            'taxonomy' => 'news_intel-types',
										            'field' => 'slug',
										            'terms' => array('press')
										        )
										    )
										);

									    $query_press = new WP_Query( $args_press );

									    if ($query_press->have_posts()) : while ($query_press->have_posts()) : $query_press->the_post();

									    	// query here for news_intel cpt w/ category 'intel'

											// store custom post meta fields in one array 
											$post_meta_data = get_post_custom($post->ID);

											// Set variables
											$press_article_source = $post_meta_data['news_intel_article_source'][0];
											$press_article_description = $post_meta_data['news_intel_article_description'][0];
											$press_article_url = $post_meta_data['news_intel_article_url'][0];
											$press_article_date = $post_meta_data['news_intel_article_date'][0];
											$press_article_date = strtotime( $press_article_date );
											$press_article_date = date( 'F j, Y', $press_article_date );

											?>

									        <p><?php the_title(); ?></p>
									        <?= '<p>' . $press_article_source . ', ' . $press_article_date . '</p>'; ?>
									        <p><a href="<?= $press_article_url; ?>" target="_blank" class="read-more">DETAILS</a></p>


										<?php endwhile; endif; wp_reset_postdata(); ?>

									</div><!-- end fivecol first-->
									<div class="fivecol kludge-news-intel">
										<img src="http://aspiriant.com/wp/wp-content/uploads/wallstreetjournal.jpg">
										<img src="http://aspiriant.com/wp/wp-content/uploads/Barrons.jpg">
										<img src="http://aspiriant.com/wp/wp-content/uploads/nytimes-logo.jpg">
										<img src="http://aspiriant.com/wp/wp-content/uploads/bloomberg-e1381257343232.jpg">
										<img src="http://aspiriant.com/wp/wp-content/uploads/marketwatch-logo.jpg"> 
									</div> <!-- end fivecol -->

								</div> <!-- end twelvecol first -->

							</section> <!-- end article section -->
						</article> <!-- end article -->
					</div>

					<?php if($sidebar) { ?>
						<div class="threecol sidebar-container">
							<?php get_template_part( 'sidebar', 'page' ); ?>
						</div>
					<?php } ?>

					<footer class="article-footer">
						<?php the_tags('<span class="tags">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?>
					</footer> <!-- end article footer -->

				<?php endwhile; else : ?>

						<article id="post-not-found" class="hentry clearfix">
							<header class="article-header">
								<h1><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
							</header>
							<section class="entry-content">
								<p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
							</section>
							<footer class="article-footer">
								<p><?php _e("This is the error message in the page.php template.", "bonestheme"); ?></p>
							</footer>
						</article>

				<?php endif; ?>

			</div> <!-- end role main -->

		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php get_footer(); ?>