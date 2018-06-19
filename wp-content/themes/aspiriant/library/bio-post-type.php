<?php
/* Bones Bio Type Example
This page walks you through creating 
a Bio type and taxonomies. You
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
function bio_post() { 
	// creating (registering) the custom type 
	register_post_type( 'bio', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
	 	// let's now add all the options for this post type
		array('labels' => array(
			'name' => __('Bios', 'bonestheme'), /* This is the Title of the Group */
			'singular_name' => __('Bio', 'bonestheme'), /* This is the individual type */
			'all_items' => __('All Bios', 'bonestheme'), /* the all items menu item */
			'add_new' => __('Add New', 'bonestheme'), /* The add new menu item */
			'add_new_item' => __('Add New Bio', 'bonestheme'), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __('Edit Bios', 'bonestheme'), /* Edit Display Title */
			'new_item' => __('New Bio', 'bonestheme'), /* New Display Title */
			'view_item' => __('View Bio', 'bonestheme'), /* View Display Title */
			'search_items' => __('Search Bios', 'bonestheme'), /* Search Custom Type Title */ 
			'not_found' =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This custom post type manages company bios.', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the Bio type menu */
			'rewrite'	=> array( 'slug' => 'our-exceptional-people-bios', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'our-exceptional-people', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky')
	 	) /* end of options */
	); /* end of register post type */
	
	/* this adds your post categories to your Bio type */
	register_taxonomy_for_object_type('category', 'bio');
	/* this adds your post tags to your Bio type */
	register_taxonomy_for_object_type('post_tag', 'bio');
	
} 

	// adding the function to the Wordpress init
	add_action( 'init', 'bio_post');
	
	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/
	
	// now let's add custom categories (these act like categories)
    register_taxonomy( 'bio-types', 
    	array('bio'), /* if you change the name of register_post_type( 'bio', then you have to change this */
    	array('hierarchical' => true,     /* if this is true, it acts like categories */             
    		'labels' => array(
    			'name' => __( 'Bio Types', 'bonestheme' ), /* name of the custom taxonomy */
    			'singular_name' => __( 'Bio Type', 'bonestheme' ), /* single taxonomy name */
    			'search_items' =>  __( 'Search Bio Types', 'bonestheme' ), /* search title for taxomony */
    			'all_items' => __( 'All Bio Types', 'bonestheme' ), /* all title for taxonomies */
    			'parent_item' => __( 'Parent Bio Type', 'bonestheme' ), /* parent title for taxonomy */
    			'parent_item_colon' => __( 'Parent Bio Type:', 'bonestheme' ), /* parent taxonomy title */
    			'edit_item' => __( 'Edit Bio Type', 'bonestheme' ), /* edit custom taxonomy title */
    			'update_item' => __( 'Update Bio Type', 'bonestheme' ), /* update title for taxonomy */
    			'add_new_item' => __( 'Add New Bio Type', 'bonestheme' ), /* add new title for taxonomy */
    			'new_item_name' => __( 'New Bio Type', 'bonestheme' ) /* name title for taxonomy */
    		),
    		'show_admin_column' => true, 
    		'show_ui' => true,
    		'query_var' => true,
    		'rewrite' => array( 'slug' => 'our-exceptional-people' ),
    	)
    );   
    
	// now let's add custom tags (these act like categories)
    // register_taxonomy( 'custom_tag', 
    // 	array('bio'), /* if you change the name of register_post_type( 'bio', then you have to change this */
    // 	array('hierarchical' => false,    /* if this is false, it acts like tags */                
    // 		'labels' => array(
    // 			'name' => __( 'Custom Tags', 'bonestheme' ), /* name of the custom taxonomy */
    // 			'singular_name' => __( 'Custom Tag', 'bonestheme' ), /* single taxonomy name */
    // 			'search_items' =>  __( 'Search Custom Tags', 'bonestheme' ), /* search title for taxomony */
    // 			'all_items' => __( 'All Custom Tags', 'bonestheme' ), /* all title for taxonomies */
    // 			'parent_item' => __( 'Parent Custom Tag', 'bonestheme' ), /* parent title for taxonomy */
    // 			'parent_item_colon' => __( 'Parent Custom Tag:', 'bonestheme' ), /* parent taxonomy title */
    // 			'edit_item' => __( 'Edit Custom Tag', 'bonestheme' ), /* edit custom taxonomy title */
    // 			'update_item' => __( 'Update Custom Tag', 'bonestheme' ), /* update title for taxonomy */
    // 			'add_new_item' => __( 'Add New Custom Tag', 'bonestheme' ), /* add new title for taxonomy */
    // 			'new_item_name' => __( 'New Custom Tag Name', 'bonestheme' ) /* name title for taxonomy */
    // 		),
    // 		'show_admin_column' => true,
    // 		'show_ui' => true,
    // 		'query_var' => true,
    // 	)
    // ); 
    
    /*
    	looking for custom meta boxes?
    	check out this fantastic tool:
    	https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
    */
	

?>
