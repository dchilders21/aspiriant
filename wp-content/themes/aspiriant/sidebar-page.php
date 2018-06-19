<?php
$slug = $post->post_name; //get the name of the title and match it with category

if(is_page(array('news-intel') )) {

} else {?>
	<div id="sidebar1" class="sidebar" role="complementary">
<?php } ?>

 	<?php

	 if (is_page('faq')) {
	 	// echo 'true';
	 	  //look for posts that have category of the $slug
		  $faq_args = array(
		      'post_type' => array('video'),
		      'posts_per_page' => -1,
		      'category_name' => $slug
		    );
		  
		  $faq = new WP_Query( $faq_args );

		   	if ($faq->have_posts()) : while ($faq->have_posts()) : $faq->the_post();
		   		?>
		   		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		   		<?php
		   		if (has_post_thumbnail()) {
		   			the_post_thumbnail('full');
		   		} ?>

		   		</a>
		   		<?php
		   		echo '<h3>Video</h3>';?>
		      	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>

		      	<?php
		      
		   		endwhile;
		  	endif;

		  wp_reset_query();

	 }


 	global $post;
 	// echo $post->ID;
 	$sidebar =  get_post_meta($post->ID, 'sidebar_info', true); // the event is housed on an external page

 	if ($sidebar) {
 		// echo 'true';
 		echo $sidebar;
 	}

	?>

<?php 
if(is_page(array('news-intel', 'investment-management', 'custom-wealth-planning') )) {

} else {?>
	</div>
<?php } ?>

