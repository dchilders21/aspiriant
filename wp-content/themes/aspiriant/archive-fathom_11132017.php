<?php get_header( 'blog' ); ?>

	<div id="content"> <!-- DC Tax -->

		<div id="inner-content" class="wrap clearfix">

			<div class="large-scrn">
				<div id="follow-links">
					<a href="https://twitter.com/AspiriantNews" title="Follow us on Twitter" target="_blank">
						<span class="fa fa-twitter-square fa-2x" aria-hidden="true"></span> 
					</a>
					<a href="https://www.linkedin.com/company/aspiriant" title="Follow us on LinkedIn" target="_blank">
						<span class="fa fa-linkedin-square fa-2x" aria-hidden="true"></span> 
					</a>
				</div>
			</div>

			<div id="main" class="first clearfix" role="main">

				<?php

				// First show sticky post, if it exists
				// And if it's on the first page

				if ( !is_paged() ) {

				$args_sticky = array(
					'post_type' => 'fathom',
					'posts_per_page' => 1,
					'meta_key' => 'fathom_sticky',
					'meta_compare' => 'EXISTS'
				);

				$loop_sticky = new WP_Query( $args_sticky );

				while ( $loop_sticky->have_posts() ) : $loop_sticky->the_post(); ?>
					
					<div class="fivecol post-container sticky-post">

						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

							<header class="article-header">

								<?php

								$terms = get_the_terms( $post->ID , 'fathom-type' );

								echo '<div class="post_meta">';

									echo '<span class="post_category">';

									foreach ( $terms as $term ) {
										if ( $term->name !== "Insight" ) {
											echo '<a href="./';
												echo $term->slug;
											echo '/">';
												echo $term->name;
											echo '</a>';
										}
									}

									echo '</span>';

									echo '<span class="post_date">';
										the_time( 'F j, Y' );
									echo '</span>';

								echo '</div>';

								// if ( has_post_thumbnail() ) {
								// 	the_post_thumbnail('full');
								// }
								
								// Get sticky image
								$media = get_attached_media('image');
								$sticky_img = "";
								
								foreach( $media as $m ) {
									$sticky = strpos($m->guid, '-sticky');
									if ( $sticky !== false ) {
										$sticky_img = wp_get_attachment_url($m->ID);
									}
								}
								
								

								$fathom_short_headline = get_post_meta( $post->ID, 'fathom_short_headline', true);

								if ($fathom_short_headline) {
									$title = $fathom_short_headline;
								}
								else {
									$title = get_the_title();
								}

								$excerpt = get_the_excerpt();
								$link = get_the_permalink();

								if ( $sticky_img !== "" ) {
									echo '<a href="' . $link . '" rel="bookmark" title="' . $title . '"><img src="' . $sticky_img . '"></a>';
								}

								?>

								<h3 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $title; ?>"><?php echo $title; ?></a></h3>

							</header> <!-- end article header -->

							<section class="entry-content clearfix">

								<?php echo $excerpt; ?>

							</section> <!-- end article section -->

							<footer class="article-footer">

							</footer> <!-- end article footer -->

						</article> <!-- end article -->

					</div>

				<?php endwhile;  wp_reset_query(); } ?>
				
				<?php

				// Show all other posts

				$args_unsticky = array(
					'post_type' => 'fathom',
					'posts_per_page' => 12,
					'meta_query'	=> array(
						'relation'		=> 'AND',
						array(
							'key'	 	=> 'fathom_sticky',
							'compare' 	=> 'NOT EXISTS',
						),
						array(
							'relation'		=> 'OR',
							array(
								'key'	  	=> 'suppress',
								'compare' 	=> '==',
								'value'	  	=> '0',
							),
							array(
								'key'	  	=> 'suppress',
								'compare' 	=> 'NOT EXISTS',
							),
						),
					),
 					'paged' => get_query_var( 'paged' )
				);

				$loop_unsticky = new WP_Query( $args_unsticky );

				function sortByLastName($a, $b) {
					return $a['lastname'] - $b['lastname'];
				}

				while ( $loop_unsticky->have_posts() ) : $loop_unsticky->the_post(); ?>
				
					<div class="fivecol post-container">

						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

							<header class="article-header">

								<?php

								$terms = get_the_terms( $post->ID , 'fathom-type' );

								echo '<div class="post_meta">';

									echo '<span class="post_category">';

									foreach ( $terms as $term ) {
										if ( $term->name !== "Insight" ) {
											echo '<a href="./';
												echo $term->slug;
											echo '/">';
												echo $term->name;
											echo '</a>';
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

								if ( has_post_thumbnail() ) {
									the_post_thumbnail('full');
								}

								$fathom_short_headline = get_post_meta( $post->ID, 'fathom_short_headline', true);

								if ($fathom_short_headline) {
									$title = $fathom_short_headline;
								}
								else {
									$title = get_the_title();
								}
								
								$excerpt = get_the_excerpt();

								?>

								<h3 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $title; ?>"><?php echo $title; ?></a></h3>

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

								// function sortByLastName($a, $b) {
								// 	return $a['lastname'] - $b['lastname'];
								// }

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

								$author_count = 0;

								while ( $bio_query->have_posts() ) : $bio_query->the_post();

									$category_slug = $categories[$index_bio]->slug;
									$category_name = $categories[$index_bio]->name;
									$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$category_slug'");
									$job_title = get_post_meta( $post_id, 'bio_job_title', true );

									if ( !empty( $categories ) && $category_slug == 'rob-francais' ) {

										$author_count++;

										$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';
										
										// Don't link to private bio pages
										if ( get_post_status($post_id) == 'private' ) {
											echo '<p class="byline vcard">By ' . $category_name . '<br>';
										}
										else {
											echo '<p class="byline vcard">By <a href="' . $bio_link . '">' . $category_name . '</a><br>';
										}

										echo $job_title . '</p>';

									}

									$index_bio++;

								endwhile;

								if ( $author_count == 0 ) {

									// rewind loop
									$bio_query->rewind_posts();

									$index_bio = 0;

									$john_allen_count = 0;

									while ( $bio_query->have_posts() ) : $bio_query->the_post();

										$author_count++;

										$category_slug = $categories[$index_bio]->slug;
										$category_name = $categories[$index_bio]->name;
										$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$category_slug'");
										$job_title = get_post_meta( $post_id, 'bio_job_title', true );

										if ( $author_count == 1 && !empty( $categories ) && $category_slug != 'rob-francais' ) {

											if ( $has_john_allen == 1 && $john_allen_count == 0 ) {

												echo '<p class="byline vcard">By <a href="/our-exceptional-people-bios/john-allen/">John Allen</a><br>';
												echo 'Chief Investment Officer, Principal</p>';

												// if ( $category_slug != 'john-allen' ) {

												// 	$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';
													
												// 	// Don't link to private bio pages
												// 	if ( get_post_status($post_id) == 'private' ) {
												// 		echo '<p class="byline vcard">By ' . $category_name . '<br>';
												// 	}
												// 	else {
												// 		echo '<p class="byline vcard">By <a href="' . $bio_link . '">' . $category_name . '</a><br>';
												// 	}

												// 	echo $job_title . '</p>';

												// }

												$john_allen_count++;

											}

											else {

												if ( $john_allen_count == 0 ) {

													$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';
													
													// Don't link to private bio pages
													if ( get_post_status($post_id) == 'private' ) {
														echo '<p class="byline vcard">By ' . $category_name . '<br>';
													}
													else {
														echo '<p class="byline vcard">By <a href="' . $bio_link . '">' . $category_name . '</a><br>';
													}

													echo $job_title . '</p>';

												}

											}

										}

										$index_bio++;

									endwhile;

								} // end if ( $author_count == 0 ) ...
								
								wp_reset_query();

								?>

							</header> <!-- end article header -->

							<section class="entry-content clearfix">

								<?php echo $excerpt; ?>

							</section> <!-- end article section -->

							<footer class="article-footer">

							</footer> <!-- end article footer -->

						</article> <!-- end article -->

					</div>

					

				<?php endwhile; ?>

                                <nav class="wp-prev-next">
							<ul class="clearfix">
								<li class="prev-link"><?php next_posts_link(__('Previous Posts', "bonestheme")) ?></li>
								<li class="next-link"><?php previous_posts_link(__('Newer Posts', "bonestheme")) ?></li>
							</ul>
					</nav>

                                <?php wp_reset_query(); ?>


		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php get_footer( 'blog' ); ?>
