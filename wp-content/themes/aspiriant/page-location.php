<?php
/*
Template Name: Location Page
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="twelvecol" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php edit_post_link(); ?>

									<h2><?php the_title(); ?></h2>
										
									<section class="clearfix" class="entry-content clearfix" itemprop="articleBody">
										<?php the_content(); ?>
									</section> <!-- end article section -->
									<?php
									//query here for bio cpt with tax term management and new york
									
									$post_meta_data = get_post_custom($post->ID); // store custom post meta fields in one array 

									if(get_the_title()=='New York') {
										$term='new-york';
									} elseif(get_the_title()=='San Francisco') {
										$term='san-francisco';
									} elseif(get_the_title()=='Los Angeles') {
										$term='los-angeles';
									} elseif(get_the_title()=='Cincinnati') {
										$term = 'cincinnati';
									} elseif(get_the_title()=='Milwaukee') {
										$term = 'milwaukee';
									} elseif(get_the_title()=='Minneapolis') {
										$term = 'minneapolis';
									} elseif(get_the_title()=='Boston') {
										$term = 'boston'; 
									} elseif(get_the_title()=='Irvine') {
										$term = 'irvine';
									} elseif(get_the_title()=='San Diego') {
										$term = 'san-diego';
									} elseif(get_the_title()=='Silicon Valley') {
										$term = 'silicon-valley';
									}

									//echo $term;
									// Set variables 
									$bio_job_title = $post_meta_data['bio_job_title'][0];
									$bio_job_title_2 = $post_meta_data['bio_job_title_2'][0];
									$bio_accreditation = $post_meta_data['bio_accreditation'][0];
									$bio_email = $post_meta_data['bio_email'][0];
									$bio_more_resources = $post_meta_data['bio_more_resources'][0];
									$bio_full_bio = $post_meta_data['bio_full_bio'][0];

									
									$args = array(
									    'posts_per_page' => -1,
									    'post_type' => 'bio',
									    'post_status' => 'publish',
									    'tax_query' => array(
									        array(
									            'taxonomy' => 'bio-types',
									            'field' => 'slug',
									            'terms' => $term
									        )
									    ),
									    'orderby' => 'meta_value', 
										'meta_key' => 'bio_last_name',
										'order'=> 'ASC'
									);


									$management_query = new WP_Query( $args );
									
										while ( $management_query->have_posts() ) : $management_query->the_post();

										?>
										<?php
										$link = get_permalink() . '?c=' . $term;

										$job_title = get_post_meta( $post->ID, 'bio_job_title', true);
										$job_title_2 = get_post_meta( $post->ID, 'bio_job_title_2', true);
										$accreditation = get_post_meta( $post->ID, 'bio_accreditation', true);

										?>

											<div class="threecol bio-grid">

													<a href="<?php echo $link; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('medium'); ?></a>


													<h4 class="h4">
														<div class="bio-name">
															<?php the_title(); ?><span class="bio-accreditation">&nbsp;<?php echo $accreditation;?></span><br />
														</div>
														<div class="bio-job-title">
															<?php echo $job_title; ?>
														</div>
													</h4>

													<a class="profile-link" href="<?php echo $link; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
														Profile
													</a>
											</div>



										<?php
										endwhile;

										// Reset Post Data
										wp_reset_postdata();
									?>
			

								<footer class="article-footer">
									<p class="clearfix"><?php the_tags('<span class="tags">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?></p>

								</footer> <!-- end article footer -->

								<?php comments_template(); ?>

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

						<?php //get_sidebar(); ?>

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>