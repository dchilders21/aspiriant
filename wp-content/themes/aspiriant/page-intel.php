<?php
/*
Template Name: Intel Page
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
									//the_content(); 

									$args = array(
									    'posts_per_page' => -1,
									    'post_type' => 'news_intel',
									    'post_status' => 'publish',
									    'tax_query' => array(
									        array(
									            'taxonomy' => 'news_intel-types',
									            'field' => 'slug',
									            'terms' => 'intel'
									        )
									    ),
										'order'=> 'DESC'
									);


									$intel_query = new WP_Query( $args );
									
										while ( $intel_query->have_posts() ) : $intel_query->the_post();

										// store custom post meta fields in one array 
										$post_meta_data = get_post_custom($post->ID);
										// Set variables
										$intel_article_source = $post_meta_data['news_intel_article_source'][0];
										$intel_article_description = $post_meta_data['news_intel_article_description'][0];
										$intel_article_url = $post_meta_data['news_intel_article_url'][0];
										$intel_article_date = $post_meta_data['news_intel_article_date'][0];
										$intel_article_date = strtotime( $intel_article_date );
										$intel_article_date = date( 'F j, Y', $intel_article_date );

										if(has_post_thumbnail()) {
											the_post_thumbnail('full', array('class' => 'alignleft'));
										}
										echo '<div class="intel-text">';
										echo '<h2 class="h4">';
										the_title();
										echo '</h2>';
										echo'<p>' . $intel_article_source . ', ' . $intel_article_date . '</p>';
									    
									    if(has_excerpt()) {
									    	the_excerpt();
									    }
									    echo '<a href="$intel_article_url" class="read-more">READ</a>';
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

								<?php comments_template(); ?>

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
