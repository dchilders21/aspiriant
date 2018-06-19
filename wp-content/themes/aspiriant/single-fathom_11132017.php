<?php
/*
This is the Fathom custom post type post template.

*/
?>

<?php get_header( 'blog' ); ?>

	<div id="content" class="twelvecol">

		<div id="inner-content" class="clearfix">

				<div id="main" class="first eightcol push-onecol clearfix" role="main">

					<?php while (have_posts()) : the_post(); ?>

						<?php

						// Get terms
						$terms = get_the_terms( $post->ID , 'fathom-type' );

						//query here for fathom cpt
						$post_meta_data = get_post_custom($post->ID); // store custom post meta fields in one array

						?>

						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

							<header class="article-header"></header> <!-- end article header -->

							<section class="entry-content clearfix" id="top"> <!-- #top for back to top links -->

								<!-- Category/Insight/Date header -->
								<div>
									<?php

										$terms = get_the_terms( $post->ID , 'fathom-type' );

										echo '<div class="post_meta">';

											echo '<span class="post_category">';

												foreach ( $terms as $term ) {
													if ( $term->name !== "Insight" ) {
															echo $term->name;
													}
													elseif ( $term->name == "Insight" ) {
														echo 'Insight';
													}
												}


											echo '</span>';

											echo '<span class="post_date">';
												the_time( 'F j, Y' );
											echo '</span>';

										echo '</div>';

									?>

								</div> <!-- END Category/Insight/Date header -->


								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail('full');
								}
								?>

								<h1 class="single-title custom-post-type-title">
									<?php the_title(); ?>
								</h1>

								<div class="author-container">
								
								<?php

								$categories = get_the_category();

								$authors_array = array();

								foreach ($categories as $key => $value) {
									$authors_array[$key] = $categories[$key]->slug;
								}
								
								// Sort authors by last name

								$tmp_array = array();

								$authors_array_count = count($authors_array);

								for ($i=0; $i < $authors_array_count; $i++) { 
									
									$firstname = substr($authors_array[$i], 0, strrpos( $authors_array[$i], '-') );
									$lastname = substr($authors_array[$i], strrpos($authors_array[$i], '-') + 1);

									$tmp_array[] = array('firstname' => $firstname, 'lastname' => $lastname);

								}

								function sortByLastName($a, $b) {
									return $a['lastname'] - $b['lastname'];
								}

								usort($tmp_array, 'sortByLastName');

								$authors_array_sorted = array();

								$has_john_allen = 0;

								for ($i=0; $i < $authors_array_count; $i++) {

									$authors_array_sorted[] = $tmp_array[$i]['firstname'] . "-" . $tmp_array[$i]['lastname'];

									if ($authors_array_sorted[$i] == 'john-allen') {
										$has_john_allen = 1;
									}

								}
								
								// get author's bio info
								$bio_args = array(
									'posts_per_page' => sizeof($categories),
									'post_type' => 'bio',
									'post_status' => array( 'publish', 'private' ),
									'post_name__in' => $authors_array_sorted
								);

								$bio_query = new WP_Query( $bio_args );

								$index_bio = 0;

								while ( $bio_query->have_posts() ) : $bio_query->the_post();	

									$category_slug = $categories[$index_bio]->slug;
									$category_name = $categories[$index_bio]->name;
									$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$category_slug'");
									$job_title = get_post_meta( $post_id, 'bio_job_title', true );

									if ( !empty( $categories ) && $category_slug == 'rob-francais' ) {
										$bg_img = '/wp/wp-content/uploads/' . $category_slug . '-blog.jpg';
										$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';
										echo '<div class="blog_author fivecol">';
										echo '<div class="blog_img" style="background: url(' . $bg_img . ') no-repeat;"></div>';
										
										// Don't link to private bio pages
										if ( get_post_status($post_id) == 'private' ) {
											echo '<p class="byline vcard">' . $category_name . '<br>';
										}
										else {
											echo '<p class="byline vcard"><a href="' . $bio_link . '">' . $category_name . '</a><br>';
										}

										echo $job_title . '</p>';
										echo '</div>';
									}

									$index_bio++;

								endwhile;
								
								// rewind loop
								$bio_query->rewind_posts();
								
								$index_bio = 0;

								$john_allen_count = 0;
								
								while ( $bio_query->have_posts() ) : $bio_query->the_post();

									$category_slug = $categories[$index_bio]->slug;
									$category_name = $categories[$index_bio]->name;
									$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$category_slug'");
									$job_title = get_post_meta( $post_id, 'bio_job_title', true );
									//echo $category_name;
									//echo $category_slug;
									if ( !empty( $categories ) && $category_slug != 'rob-francais' ) {

										if ( $has_john_allen == 1 && $john_allen_count == 0) {
											echo '<div class="blog_author fivecol">';
											echo '<div class="blog_img" style="background: url(/wp/wp-content/uploads/john-allen-blog.jpg) no-repeat;"></div>';
											echo '<p class="byline vcard"><a href="/our-exceptional-people-bios/john-allen/">John Allen</a><br>';
											echo 'Chief Investment Officer, Principal</p>';
											echo '</div>';

											if ( $category_slug != 'john-allen' ) {
												$bg_img = '/wp/wp-content/uploads/' . $category_slug . '-blog.jpg';
												$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';
												echo '<div class="blog_author fivecol">';
												echo '<div class="blog_img" style="background: url(' . $bg_img . ') no-repeat;"></div>';
												
												// Don't link to private bio pages
												if ( get_post_status($post_id) == 'private' ) {
													echo '<p class="byline vcard">' . $category_name . '<br>';
												}
												else {
													echo '<p class="byline vcard"><a href="' . $bio_link . '">' . $category_name . '</a><br>';
												}

												echo $job_title . '</p>';
												echo '</div>';
											}

											

											$john_allen_count++;

										} else if ( $category_slug != 'john-allen' ) {
												$bg_img = '/wp/wp-content/uploads/' . $category_slug . '-blog.jpg';
												$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';
												echo '<div class="blog_author fivecol">';
												echo '<div class="blog_img" style="background: url(' . $bg_img . ') no-repeat;"></div>';
												
												// Don't link to private bio pages
												if ( get_post_status($post_id) == 'private' ) {
													echo '<p class="byline vcard">' . $category_name . '<br>';
												}
												else {
													echo '<p class="byline vcard"><a href="' . $bio_link . '">' . $category_name . '</a><br>';
												}

												echo $job_title . '</p>';
												echo '</div>';

										} else {

											if ( $john_allen_count == 0 ) {
												$bg_img = '/wp/wp-content/uploads/' . $category_slug . '-blog.jpg';
												$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';
												echo '<div class="blog_author fivecol">';
												echo '<div class="blog_img" style="background: url(' . $bg_img . ') no-repeat;"></div>';
												
												// Don't link to private bio pages
												if ( get_post_status($post_id) == 'private' ) {
													echo '<p class="byline vcard">' . $category_name . '<br>';
												}
												else {
													echo '<p class="byline vcard"><a href="' . $bio_link . '">' . $category_name . '</a><br>';
												}
												
												echo $job_title . '</p>';
												echo '</div>';

											}

										}
									}

									$index_bio++;

								endwhile; wp_reset_query();

								?>
								</div> <!-- // END .author-container -->

								<?php the_content(); ?>

							</section> <!-- end article section -->

							<footer class="article-header">
								<!-- <p class="tags"><?php // echo get_the_term_list( get_the_ID(), 'custom_tag', '<span class="tags-title">' . __('Custom Tags:', 'bonestheme') . '</span> ', ', ' ) ?></p> -->

							</footer> <!-- end article footer -->

							<?php comments_template(); ?>

						</article> <!-- end article -->

					<?php endwhile; ?>


					<div class="post_tags" style="clear:both">

						<?php the_tags( '<p>TAGS: ', ', ', '</p>' ); ?>

					</div>


					<?php

					// If "fathom/insight" exists in URL, show news snippets

					$url = $_SERVER["REQUEST_URI"];
					$isInsight = strpos($url, 'fathom/insight');

					if ( $isInsight !== false ) { ?>

						<div class="fathom-news-intel clearfix">
							<h3>Inside Aspiriant</h3>

							<?php
							// Get latest news

							$inside_aspiriant = array(
								'post_type' => 'fathom',
								'fathom-type'=> 'inside-aspiriant',
								'posts_per_page' => 3,
								'order'=> 'DESC'
							);

							//echo $inside_aspiriant;

							$inside_query = new WP_Query( $inside_aspiriant );
							//echo $inside_query;

							while ( $inside_query->have_posts() ) : $inside_query->the_post();

								//echo $post;	

								$post_meta_data = get_post_custom($post->ID);
								$press_article_source = $post_meta_data['news_intel_article_source'][0];
								$press_article_description = $post_meta_data['news_intel_article_description'][0];
								$press_article_url = $post_meta_data['news_intel_article_url'][0];

								echo '<div class="threecol fathom-news-snippet">';

									echo '<h2><a href="' . $press_article_url . '" target="_blank">' . get_the_title() . '</a></h2>';

									$id = get_the_ID();
									$cats = wp_get_post_categories($id);
									$category = get_category($cats[0]->cat_ID);

									$category_name = get_the_category();
									$cats[0]->name;

									echo '<span>';

										if($category_name[0] && ($category_name[0]->cat_name != 'homepage highlight' || $category_name[0]->cat_name != 'Uncategorized' || $category_name[0]->cat_name != ' ')) {
											echo ' with ' . $category_name[0]->cat_name;
										}

									echo '</span>';

									/*if(has_post_thumbnail()) {
										the_post_thumbnail('thumb', array('class' => 'news-intel-logo alignright'));
									}*/

								echo '</div>';

							endwhile;
							wp_reset_query();

							?>
						</div> <!-- // END .fathom-intel-news -->

						<?php

					} // end check for Insight post type

					?>
					
					<?php

					// If not "fathom/insight" in URL, show pagination

					$url = $_SERVER["REQUEST_URI"];
					$isInsight = strpos($url, 'fathom/insight');

					if ( $isInsight == false ) { ?>
						<!-- pagination -->
						
						<div class="pagination navigation clearfix">
							<div class="alignleft sixcol">
								<h3 class="text-left"><!-- Previous article<br /> -->
								<?php previous_post_link('Previous article<br />%link'); ?></h3>
							</div>
							<div class="alignright sixcol">
								<h3 class="text-right"><!-- Next article<br /> -->
								<?php next_post_link('Next article<br />%link'); ?></h3>
							</div>
						</div>

					<?php

					} // end check for Insight post type ?>

					

						<div class="insight_newsletter_container clearfix">

							<h2 class="newsletter-hed">
								Want our insights delivered straight to your inbox?
							</h2>
							
							<div class="newsletter-content">
								
								<p>Sign up for regular updates here.</p>
							
								<form action="https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST">
									<input name="oid" type="hidden" value="00Dd0000000bhHR" /> 
									<input name="retURL" type="hidden" value="http://aspiriant.com/insight-welcome/" /> 
									<input name="Referral_Source__c" type="hidden" value="Insight" /> 
									<input id="email" maxlength="80" name="email" value="Email" required="" size="" type="text" class="text-field" onfocus="if (this.value=='Email') this.value='';" />
									<br />
									<input type="submit" value="Submit" class="insight-form-submit" /> 
								</form>
							</div>

						</div>

					

				</div> <!-- end #main -->

			<?php get_template_part( 'sidebar', 'fathom' ); ?>

		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php get_footer( 'blog' ); ?>
