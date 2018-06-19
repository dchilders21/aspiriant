<?php
/* Bones Blog Post Type
This page walks you through creating
a Blog type and taxonomies. You
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
function fathom_post() {
	// creating (registering) the custom type
	register_post_type( 'fathom',
		array('labels' => array(
			'name' => __('Fathom', 'bonestheme'), /* This is the Title of the Group */
			'singular_name' => __('Blog', 'bonestheme'), /* This is the individual type */
			'all_items' => __('All Fathom Posts', 'bonestheme'), /* the all items menu item */
			'add_new' => __('Add New', 'bonestheme'), /* The add new menu item */
			'add_new_item' => __('Add New Fathom Post', 'bonestheme'), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __('Edit Fathom Post', 'bonestheme'), /* Edit Display Title */
			'new_item' => __('New Fathom Post', 'bonestheme'), /* New Display Title */
			'view_item' => __('View Fathom Post', 'bonestheme'), /* View Display Title */
			'search_items' => __('Search Fathom Posts', 'bonestheme'), /* Search Custom Type Title */
			'not_found' =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */
			'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This custom post type manages company blog posts.', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the Bio type menu */
			'rewrite'	=> array( 'slug' => 'fathom/%fathom-type%', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'fathom', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			// editor and excerpt fields are omitted for this post type
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky', 'wpcom-markdown')
	 	) /* end of options */
	); /* end of register post type */

	/* this adds your post categories to your Fathom post type */
	register_taxonomy_for_object_type('category', 'fathom');
	/* this adds your post tags to your Fathom post type */
	register_taxonomy_for_object_type('post_tag', 'fathom');

}

// adding the function to the Wordpress init
add_action( 'init', 'fathom_post');

// make permalink structure for Fathom posts and categories
add_filter('post_link', 'fathom_permalink', 10, 3);
add_filter('post_type_link', 'fathom_permalink', 10, 3);

function fathom_permalink($permalink, $post_id, $leavename) {
    if (strpos($permalink, '%fathom-type%') === FALSE) return $permalink;

        // Get post
        $post = get_post($post_id);
        if (!$post) return $permalink;

        // Get taxonomy terms
        $terms = wp_get_object_terms($post->ID, 'fathom-type');
        if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) $taxonomy_slug = $terms[0]->slug;
        else $taxonomy_slug = 'uncategorized';

    return str_replace('%fathom-type%', $taxonomy_slug, $permalink);
}

/*
for more information on taxonomies, go here:
http://codex.wordpress.org/Function_Reference/register_taxonomy
*/

// now let's add custom categories (these act like categories)
register_taxonomy( 'fathom-type',
	array('fathom'), /* if you change the name of register_post_type( 'bio', then you have to change this */
	array('hierarchical' => true,     /* if this is true, it acts like categories */
		'labels' => array(
			'name' => __( 'Fathom Post Types', 'bonestheme' ), /* name of the custom taxonomy */
			'singular_name' => __( 'Fathom Post Type', 'bonestheme' ), /* single taxonomy name */
			'search_items' =>  __( 'Search Fathom Post Types', 'bonestheme' ), /* search title for taxomony */
			'all_items' => __( 'All Fathom Post Types', 'bonestheme' ), /* all title for taxonomies */
			'parent_item' => __( 'Parent Fathom Post Type', 'bonestheme' ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent Fathom Post Type:', 'bonestheme' ), /* parent taxonomy title */
			'edit_item' => __( 'Edit Fathom Post Type', 'bonestheme' ), /* edit custom taxonomy title */
			'update_item' => __( 'Update Fathom Post Type', 'bonestheme' ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New Fathom Post Type', 'bonestheme' ), /* add new title for taxonomy */
			'new_item_name' => __( 'New Fathom Post Type', 'bonestheme' ) /* name title for taxonomy */
		),
		'show_admin_column' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'fathom' ),
	)
);

// Limit tag archive results to tagged Fathom posts
add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
	if( is_tag() && $query->is_main_query() && !is_admin() ) {
    $post_types = array( 'page', 'fathom' );
    $query->set( 'post_type', $post_types );
  }
}

?>
