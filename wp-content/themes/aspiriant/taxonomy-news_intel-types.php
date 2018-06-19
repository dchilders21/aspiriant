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
		//echo '<h2 class="entry-title">' . $term->name . '</h2>';
		edit_post_link('Edit');
		$termSlug = $term->slug;
	
		//print the description of category
		$termDesc = $term->description;
		echo "<h2>" . $termDesc . "</h2>";
		
		$query = new WP_Query( 
		  array( 
			'posts_per_page' => -1, //displays all posts on one page
			'news_intel-types'=> $termSlug,
			'post_type' => 'news_intel',
			'order'=> 'ASC'
		  )//end of first array
		);//end of query
		
		while ( $query->have_posts() ) : $query->the_post(); ?>

	      <div>
	        <h3 class="h3"><?= get_the_title(); ?></h3>
	      </div>

      	<?php
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
      ?>
    </div>
    <!-- end #main -->
    
    <?php //get_sidebar(); ?>
    
  </div>
  <!-- end #inner-content --> 
  
</div>
<!-- end #content -->

<?php get_footer(); ?>
