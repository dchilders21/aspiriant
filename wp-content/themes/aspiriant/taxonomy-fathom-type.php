<?php
/*
This is the custom post type taxonomy template.
If you edit the custom taxonomy name, you've got
to change the name of this template to
reflect that name change.

i.e. if your custom taxonomy is called
register_taxonomy( 'shoes',
then your single template should be
taxonomy-shoes.php

*/
?>
<?php get_header( 'blog' ); ?>

	<div id="content">

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

				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$termSlug = $term->slug;

				// First show sticky post, if it exists

				$sticky = get_option( 'sticky_posts' );

				$args_sticky = array(
					'post_type' => 'fathom',
					'fathom-type'=> $termSlug,
					'posts_per_page' => 1,
					'post__in' => $sticky,
					'ignore_sticky_posts' => 1
				);

				$loop_sticky = new WP_Query( $args_sticky );

				if ( isset($sticky[0]) ):

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
										elseif ( $term->name == "Insight" ) {
											echo 'Insight';
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
								
								if ( $sticky_img !== "" ) {
									echo '<img src="' . $sticky_img . '">';
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

								//$categories = get_the_category();
								// Only pulling the primary category for the listing page instead of all '$categories'
								$cat = new WPSEO_Primary_Term('category', get_the_ID());
    							$cat = $cat->get_primary_term();
    							$catName = get_cat_name($cat);
    							$cat = get_category($cat);
    							//echo var_dump($cat);
    							$categories = array();
    							$categories[0] = $cat;

								$authors_array = array();

								foreach ($categories as $key => $value) {
									$authors_array[$key] = $categories[$key]->slug;
								}

								// get bio info
								$bio_args = array(
									'posts_per_page' => sizeof($categories),
									'post_type' => 'bio',
									'post_status' => array( 'publish', 'private' ),
									'post_name__in' => $authors_array
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

									while ( $bio_query->have_posts() ) : $bio_query->the_post();

										$author_count++;

										$category_slug = $categories[$index_bio]->slug;
										$category_name = $categories[$index_bio]->name;
										$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$category_slug'");
										$job_title = get_post_meta( $post_id, 'bio_job_title', true );

										if ( $author_count == 1 && !empty( $categories ) && $category_slug != 'rob-francais' ) {

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

					<?php endwhile; endif; wp_reset_query(); ?>

					<?php

					// Show all other posts

					$args = array(
						'post_type' => 'fathom',
						'fathom-type'=> $termSlug,
						'posts_per_page' => -1,
						'post__not_in' => get_option( 'sticky_posts' ),
						'ignore_sticky_posts' => 1
					);

					$loop = new WP_Query( $args );

					while ( $loop->have_posts() ) : $loop->the_post(); ?>

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

									//$categories = get_the_category();
									// Only pulling the primary category for the listing page instead of all '$categories'
									$cat = new WPSEO_Primary_Term('category', get_the_ID());
	    							$cat = $cat->get_primary_term();
	    							$catName = get_cat_name($cat);
	    							$cat = get_category($cat);
	    							//echo var_dump($cat);
	    							$categories = array();
	    							$categories[0] = $cat;

									$authors_array = array();

									foreach ($categories as $key => $value) {
										$authors_array[$key] = $categories[$key]->slug;
									}

									// get bio info
									$bio_args = array(
										'posts_per_page' => sizeof($categories),
										'post_type' => 'bio',
										'post_status' => array( 'publish', 'private' ),
										// 'name' => $categories[0]->slug
										'post_name__in' => $authors_array
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

										$count = $bio_query->found_posts;
										$count--;

										$authors = array();
										$bio_links = array();
										$jobs = array();

										while ( $bio_query->have_posts() ) : $bio_query->the_post();

											$author_count++;

											$category_slug = $categories[$index_bio]->slug;
											$category_name = $categories[$index_bio]->name;
											$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$category_slug'");
											$job_title = get_post_meta( $post_id, 'bio_job_title', true );

											if ( !empty( $categories ) && $category_slug != 'rob-francais' ) {

												$bio_link = '/our-exceptional-people-bios/' . $category_slug . '/';

												array_push($authors, $category_name);
												array_push($bio_links, $bio_link);
												array_push($jobs, $job_title);
												

											}

											if ($index_bio == $count) {
												$authors_separated = implode(", ", $authors);
												// Don't link to private bio pages
												if ( get_post_status($post_id) == 'private' ) {
													echo '<p class="byline vcard">By ' . $authors_separated . '<br>';
												}
												else {
													echo '<p class="byline vcard">By ';
													foreach ($authors as $key=>$value) {
													    echo '<a href="' . $bio_links[$key] . '">' . $value . '</a>';
													    if ($key < $count) {
													    	echo ', ';
													    }
													}
													echo '<br>';
													foreach ($jobs as $key=>$value) {
													    echo $value;
													    if ($key < $count) {
													    	echo '<br>';
													    }
													}
													echo '</p>';
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

					<?php endwhile; wp_reset_query(); ?>

		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php get_footer( 'blog' ); ?>
