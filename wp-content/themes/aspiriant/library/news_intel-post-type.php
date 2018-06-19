<?php
/* Bones News & Intel Type Example
This page walks you through creating 
a News & Intel type and taxonomies. You
can edit this one or copy the following code 
to create another one. 

I put this in a separate file so as to 
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/


// let's create the function for the custom type
function news_intel_post() { 
	// creating (registering) the custom type 
	register_post_type( 'news_intel', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
	 	// let's now add all the options for this post type
		array('labels' => array(
			'name' => __('News &amp; Intel', 'bonestheme'), /* This is the Title of the Group */
			'singular_name' => __('News &amp; Intel', 'bonestheme'), /* This is the individual type */
			'all_items' => __('All News &amp; Intel', 'bonestheme'), /* the all items menu item */
			'add_new' => __('Add New', 'bonestheme'), /* The add new menu item */
			'add_new_item' => __('Add New News &amp; Intel', 'bonestheme'), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __('Edit News &amp; Intel', 'bonestheme'), /* Edit Display Title */
			'new_item' => __('New News &amp; Intel', 'bonestheme'), /* New Display Title */
			'view_item' => __('View News &amp; Intel', 'bonestheme'), /* View Display Title */
			'search_items' => __('Search News &amp; Intel', 'bonestheme'), /* Search Custom Type Title */ 
			'not_found' =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This custom post type manages company news &amp; intel.', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the Bio type menu */
			'rewrite'	=> array( 'slug' => 'news-intel/article', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'news-intel/archive', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
//			'taxonomies' => array('category'),
			/* the next one is important, it tells what's enabled in the post editor */
			// editor and excerpt fields are omitted for this post type
			'supports' => array( 'title', 'author', 'thumbnail', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky', 'excerpt')
	 	) /* end of options */
	); /* end of register post type */
	
	/* this adds your post categories to your Bio type */
	register_taxonomy_for_object_type('category', 'news_intel');
	/* this adds your post tags to your Bio type */
	register_taxonomy_for_object_type('post_tag', 'news_intel');
	
} 

// adding the function to the Wordpress init
add_action( 'init', 'news_intel_post');

/*
for more information on taxonomies, go here:
http://codex.wordpress.org/Function_Reference/register_taxonomy
*/

// now let's add custom categories (these act like categories)
register_taxonomy( 'news_intel-types', 
	array('news_intel'), /* if you change the name of register_post_type( 'bio', then you have to change this */
	array('hierarchical' => true,     /* if this is true, it acts like categories */             
		'labels' => array(
			'name' => __( 'News &amp; Intel Types', 'bonestheme' ), /* name of the custom taxonomy */
			'singular_name' => __( 'News &amp; Intel Type', 'bonestheme' ), /* single taxonomy name */
			'search_items' =>  __( 'Search News &amp; Intel Types', 'bonestheme' ), /* search title for taxomony */
			'all_items' => __( 'All News &amp; Intel Types', 'bonestheme' ), /* all title for taxonomies */
			'parent_item' => __( 'Parent News &amp; Intel Type', 'bonestheme' ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent News &amp; Intel Type:', 'bonestheme' ), /* parent taxonomy title */
			'edit_item' => __( 'Edit News &amp; Intel Type', 'bonestheme' ), /* edit custom taxonomy title */
			'update_item' => __( 'Update News &amp; Intel Type', 'bonestheme' ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New News &amp; Intel Type', 'bonestheme' ), /* add new title for taxonomy */
			'new_item_name' => __( 'New News &amp; Intel Type', 'bonestheme' ) /* name title for taxonomy */
		),
		'show_admin_column' => true, 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'news-intel-article' ),
	)
);

?>
