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
<?php get_header(); ?>

<div id="content">
  <div id="inner-content" class="wrap clearfix">
    <div id="main" class="twelvecol" role="main">
      <?php //print the name of the taxonomy term
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
		//edit_post_link('Edit');
		$termSlug = $term->slug;
	
		//print the description of category
		$termDesc = $term->description;
		echo "<h2>" . $termDesc . "</h2>";

		//put the C-level execs first
		?>
	<?php
		$ceo_query = new WP_Query( 
		  array( 
			'posts_per_page' => 1, //displays all posts on one page
			'bio-types'=>$termSlug,
			'post_type' => 'bio',
			'bio-types' => 'CEO'
		  )//end of first array
		);//end of query


		//echo 'test';
	while ( $ceo_query->have_posts() ) : $ceo_query->the_post();
		
		?>
      <?php
		$job_title = get_post_meta( $post->ID, 'bio_job_title', true);
		$job_title_2 = get_post_meta( $post->ID, 'bio_job_title_2', true);
		$accreditation = get_post_meta( $post->ID, 'bio_accreditation', true);
		$last_name = get_post_meta( $post->ID, 'bio_last_name', true);
		$link = the_permalink() . 'c=' . $termSlug;
      ?>
        <div class="threecol bio-grid">
			<div class="bio-image-medium">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
        			<?php the_post_thumbnail('medium'); ?>
        		</a>
        	</div>
	        <h4 class="h4 bio-nametitle">
	          <div class="bio-name"><?php the_title(); ?>
	            <span class="bio-accreditation"> <?php echo $accreditation;?></span>
	          </div>
	          <div class="bio-job-title"><?php echo $job_title; ?></div>
	      	</h4>
	        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="profile-link"><!--<img alt="link-icon" src="/wp-content/uploads/link-icon.png" width="23" height="31" />-->Profile</a> 
    	</div>
      <?php
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
      ?>

 <?php //Chief Investment Officer

 		$cio_query = new WP_Query( 
		  array( 
			'posts_per_page' => 1, //displays all posts on one page
			'bio-types'=>$termSlug,
			'post_type' => 'bio',
			'bio-types' => 'CIO'
		  )//end of first array
		);//end of query


		//echo 'test';
	while ( $cio_query->have_posts() ) : $cio_query->the_post();
		
		?>
      <?php
		$job_title = get_post_meta( $post->ID, 'bio_job_title', true);
		$job_title_2 = get_post_meta( $post->ID, 'bio_job_title_2', true);
		$accreditation = get_post_meta( $post->ID, 'bio_accreditation', true);
		$last_name = get_post_meta( $post->ID, 'bio_last_name', true);
      ?>
        <div class="threecol bio-grid">
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('medium'); ?></a>
        <h4 class="h4 bio-nametitle">
          <div class="bio-name"><?php the_title(); ?>
            <span class="bio-accreditation"> <?php echo $accreditation;?></span>
          </div>
          <div class="bio-job-title"><?php echo $job_title; ?></div>
      	</h4>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="profile-link"><!--<img alt="link-icon" src="/wp-content/uploads/link-icon.png" width="23" height="31" />-->Profile</a> </div>
      <?php
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
      ?>
	 

 <?php //Chief Operations Officer

 		$coo_query = new WP_Query( 
		  array( 
			'posts_per_page' => 1, //displays all posts on one page
			'bio-types'=>$termSlug,
			'post_type' => 'bio',
			'bio-types' => 'COO'
		  )//end of first array
		);//end of query

	while ( $coo_query->have_posts() ) : $coo_query->the_post();
		
		?>
      <?php
		$job_title = get_post_meta( $post->ID, 'bio_job_title', true);
		$job_title_2 = get_post_meta( $post->ID, 'bio_job_title_2', true);
		$accreditation = get_post_meta( $post->ID, 'bio_accreditation', true);
		$last_name = get_post_meta( $post->ID, 'bio_last_name', true);
      ?>
	    <div class="threecol bio-grid">
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('medium'); ?></a>
        <h4 class="h4 bio-nametitle">
          <div class="bio-name"><?php the_title(); ?>
            <span class="bio-accreditation"> <?php echo $accreditation;?></span>
          </div>
          <div class="bio-job-title"><?php echo $job_title; ?></div>
      	</h4>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="profile-link"><!--<img alt="link-icon" src="/wp-content/uploads/link-icon.png" width="23" height="31" />-->Profile</a> </div>
      <?php
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
      ?>

 <?php //Chief Operating Officer

 		$coperatingo_query = new WP_Query( 
		  array( 
			'posts_per_page' => 1, //displays all posts on one page
			'bio-types'=>$termSlug,
			'post_type' => 'bio',
			'bio-types' => 'Chief Operating Officer'
		  )//end of first array
		);//end of query

	while ( $coperatingo_query->have_posts() ) : $coperatingo_query->the_post();
		
		?>
      <?php
		$job_title = get_post_meta( $post->ID, 'bio_job_title', true);
		$job_title_2 = get_post_meta( $post->ID, 'bio_job_title_2', true);
		$accreditation = get_post_meta( $post->ID, 'bio_accreditation', true);
		$last_name = get_post_meta( $post->ID, 'bio_last_name', true);
      ?>
	    <div class="threecol bio-grid">
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('medium'); ?></a>
		<h4 class="h4 bio-nametitle">
          <div class="bio-name"><?php the_title(); ?>
            <span class="bio-accreditation"> <?php echo $accreditation;?></span>
          </div>
          <div class="bio-job-title"><?php echo $job_title; ?></div>
      	</h4>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="profile-link"><!--<img alt="link-icon" src="/wp-content/uploads/link-icon.png" width="23" height="31" />-->Profile</a> </div>
      <?php
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
      ?>

<?php //Chief Strategy Officer

 		$cso_query = new WP_Query( 
		  array( 
			'posts_per_page' => 1, //displays all posts on one page
			'bio-types'=>$termSlug,
			'post_type' => 'bio',
			'bio-types' => 'CSO'
		  )//end of first array
		);//end of query

	while ( $cso_query->have_posts() ) : $cso_query->the_post();
		
		?>
      <?php
		$job_title = get_post_meta( $post->ID, 'bio_job_title', true);
		$job_title_2 = get_post_meta( $post->ID, 'bio_job_title_2', true);
		$accreditation = get_post_meta( $post->ID, 'bio_accreditation', true);
		$last_name = get_post_meta( $post->ID, 'bio_last_name', true);
      ?>
	    <div class="threecol bio-grid">
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('medium'); ?></a>
        <h4 class="h4 bio-nametitle">
          <div class="bio-name"><?php the_title(); ?>
            <span class="bio-accreditation"> <?php echo $accreditation;?></span>
          </div>
          <div class="bio-job-title"><?php echo $job_title; ?></div>
      	</h4>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="profile-link"><!--<img alt="link-icon" src="/wp-content/uploads/link-icon.png" width="23" height="31" />-->Profile</a> </div>
      <?php
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
      ?>

      <br class="clearfix" />

 <?php
		
		$query = new WP_Query( 
		  array( 
			'posts_per_page' => -1, //displays all posts on one page
			'tax_query' => array( 
		        array(
		            'taxonomy' => 'bio-types',
		            'field' => 'slug',
		            'terms' => 'Management',
		            'include_children' => 0,
		            'operator' => IN
		        )
		    ),
			// 'bio-types'=>'Management',
			// 'include_children' => 0,
			'post_type' => 'bio',
			'orderby' => 'meta_value', 
			'meta_key' => 'bio_last_name',
			'order'=> 'ASC'
			//end of second array
		  )//end of first array
		);//end of query
		
		while ( $query->have_posts() ) : $query->the_post();
		
		?>
      <?php
		$job_title = get_post_meta( $post->ID, 'bio_job_title', true);
		$job_title_2 = get_post_meta( $post->ID, 'bio_job_title_2', true);
		$accreditation = get_post_meta( $post->ID, 'bio_accreditation', true);
		$last_name = get_post_meta( $post->ID, 'bio_last_name', true);
		$link = get_permalink() . '?c=' . $termSlug;


      ?>
      <div class="threecol bio-grid">
        <a href="<?php echo $link; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('medium'); ?></a>
        <h4 class="h4 bio-nametitle">
          <div class="bio-name"><?php the_title(); ?>
            <span class="bio-accreditation"> <?php echo $accreditation;?></span>
          </div>
          <div class="bio-job-title"><?php echo $job_title; ?></div>
      	</h4>
        <a href="<?php echo $link; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="profile-link"><!--<img alt="link-icon" src="/wp-content/uploads/link-icon.png" width="23" height="31" />-->Profile</a> </div>
      <?php
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
      ?>
    </div> <!-- end #main -->
        
  </div>  <!-- end #inner-content --> 
  
</div><!-- end #content -->


<?php get_footer(); ?>
