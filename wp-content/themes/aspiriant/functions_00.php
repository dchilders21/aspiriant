<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once('library/bones.php'); // if you remove this, bones will break
/*
2. library/custom-post-type.php
	- an example custom post type
	- example custom taxonomy (like categories)
	- example custom taxonomy (like tags)
*/
require_once('library/bio-post-type.php'); // you can disable this if you like
require_once('library/news_intel-post-type.php'); // you can disable this if you like
require_once('library/publications-post-type.php'); // you can disable this if you like
require_once('library/video-post-type.php'); // you can disable this if you like

/*
3. library/admin.php
	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin
*/
// require_once('library/admin.php'); // this comes turned off by default
/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once('library/translation/translation.php'); // this comes turned off by default

// Require metaboxes
require_once('library/metabox.php'); // if you remove this, bones will break

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __('Sidebar 1', 'bonestheme'),
		'description' => __('The first (primary) sidebar.', 'bonestheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	register_sidebar(array(
		'id' => 'homepagehighlight',
		'name' => __('Homepage Highlight', 'bonestheme'),
		'description' => __('The homepage highlight widget.', 'bonestheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

		register_sidebar(array(
		'id' => 'social_media',
		'name' => __('Social Media', 'bonestheme'),
		'description' => __('The social media widget.', 'bonestheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="social-media">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __('Sidebar 2', 'bonestheme'),
		'description' => __('The second (secondary) sidebar.', 'bonestheme'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/


} // don't remove this bracket!

/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php
				/*
					this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
					echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				*/
				?>
				<!-- custom gravatar call -->
				<?php
					// create variable
					$bgauthemail = get_comment_author_email();
				?>
				<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				<!-- end custom gravatar call -->
				<?php printf(__('<cite class="fn">%s</cite>', 'bonestheme'), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__('F jS, Y', 'bonestheme')); ?> </a></time>
				<?php edit_comment_link(__('(Edit)', 'bonestheme'),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
				<div class="alert alert-info">
					<p><?php _e('Your comment is awaiting moderation.', 'bonestheme') ?></p>
				</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
	<!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bones_wpsearch($form) {
	$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __('Search for:', 'bonestheme') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','bonestheme').'" />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</form>';
	return $form;
} // don't remove this bracket!



/************* Debug Page *****************/
function dev4press_debug_page_request() {
  global $wp, $template;
  define("D4P_EOL", "\r\n");

		echo '<!-- Request: ';
		echo empty($wp->request) ? "None" : esc_html($wp->request);
		echo ' -->'.D4P_EOL;
		echo '<!-- Matched Rewrite Rule: ';
		echo empty($wp->matched_rule) ? None : esc_html($wp->matched_rule);
	  	echo ' -->'.D4P_EOL;
		echo '<!-- Matched Rewrite Query: ';
		echo empty($wp->matched_query) ? "None" : esc_html($wp->matched_query);
		echo ' -->'.D4P_EOL;
		echo '<!-- Loaded Template: ';
		echo basename($template);
		echo ' -->'.D4P_EOL;
}

/************* Adding Active Class to Menu Items *****************/

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class($classes, $item){
     if( in_array('current-menu-item', $classes) ){
             $classes[] = 'active-trail ';
     }
     return $classes;
}

/* 
NOTE: In Appearance > Menu make sure to add the custom menu link and for the title, add the custom post type (example: frbsf_events) - this will allow all cpt and children of the cpt to be displayed as 'current' link.
http://wordpress.stackexchange.com/questions/3014/highlighting-wp-nav-menu-ancestor-class-w-o-children-in-nav-structure
*/
add_filter('nav_menu_css_class', 'current_type_nav_class', 10, 2 ); 
function current_type_nav_class($classes, $item) {
	global $post;
	global $blog_id;

    $post_type = get_query_var('post_type');
    $term_name = get_query_var( 'c' ); //get query string 

    
    if ($item->description != '' && $item->description == $post_type) {    
        array_push($classes, 'active-trail');
    };

	if( 
		// ( $item->title == 'Management' && get_post_type() == 'bio' && ( is_single(array('rob-francais', 'david-grecsek', 'michael-kossman', 'tom-tracy', 'bob-wagman', 'cammie-doder', 'sherri-dorton', 'kacy-gott')) ) ) || 
		// ( $item->title == 'Management' && get_post_type() == 'bio' ) || 
		( $term_name == 'management' && $item->title == 'Management' && get_post_type() == 'bio' ) ||
		( $term_name == 'principals' && $item->title == 'Principals' && get_post_type() == 'bio' ) ||
		( $term_name == 'advisors' && $item->title == 'Advisors' && get_post_type() == 'bio' ) ||
		( $term_name == 'investment-team' && $item->title == 'Investment Team' && get_post_type() == 'bio' ) ||
		( $term_name == 'los-angeles' && $item->title == 'Los Angeles' && get_post_type() == 'bio' ) ||
		( $term_name == 'san-francisco' && $item->title == 'San Francisco' && get_post_type() == 'bio' ) ||
		( $term_name == 'new-york' && $item->title == 'New York' && get_post_type() == 'bio' ) ||
		( $term_name == 'boston' && $item->title == 'Boston' && get_post_type() == 'bio' ) ||
		( $term_name == 'minneapolis' && $item->title == 'Minneapolis' && get_post_type() == 'bio' ) ||
		( $term_name == 'milwaukee' && $item->title == 'Milwaukee' && get_post_type() == 'bio' ) ||
		( $term_name == 'cincinnati' && $item->title == 'Cincinnati' && get_post_type() == 'bio' ) ||

		( is_archive() && $item->title == 'Our Exceptional People') ||
		( $item->title == 'By Office' && is_page(array('los-angeles', 'san-francisco', 'new-york', 'boston', 'minneapolis', 'milwaukee', 'cincinnati')) )  ||
		( $item->title == 'Library' && is_page(array('insight', 'white-papers', 'publications', 'events', 'insight-sign-up')) )  ||
		( $item->title == 'Insight' && is_page('insight-sign-up')) ||
		// ( $item->title == 'News & Intel' && is_page('library')) ||
		( $item->title == 'Library' && get_post_type()=='video') ||
		( $item->title == 'Events' && get_post_type()=='video') ||
		( $item->title == 'Programs' && is_page('call-the-fed')) ||
		( $item->title == 'Programs' && is_page('asia-program'))


	) 
	{
	    array_push($classes, 'active-trail');
	}
	
    return $classes;
}



/**
 * Add custom TinyMCE CSS options
 * 
 */

function myformatTinyMCE($in)
{
	$in['remove_linebreaks']=false;
	$in['gecko_spellcheck']=false;
	$in['keep_styles']=true;
	$in['accessibility_focus']=true;
	$in['tabfocus_elements']='major-publishing-actions';
	$in['media_strict']=false;
	$in['paste_remove_styles']=false;
	$in['paste_remove_spans']=false;
	$in['paste_strip_class_attributes']='none';
	$in['paste_text_use_dialog']=true;
	$in['wpeditimage_disable_captions']=true;
	$in['plugins']='inlinepopups,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen';
	$in['apply_source_formatting']=true;
	$in['theme_advanced_text_colors']='294967,b35215,696056,403932,d4cfcd,000000';
	$in['theme_advanced_more_colors']=false;
	$in['theme_advanced_buttons1']='styleselect,forecolor,|,bold,italic,|,bullist,numlist,|,link,unlink,|,removeformat,|,undo,redo';
	$in['theme_advanced_buttons2']='';
	$in['theme_advanced_buttons3']='';
	$in['theme_advanced_buttons4']='';

	return $in;
}
add_filter('tiny_mce_before_init', 'myformatTinyMCE');

function my_mce_before_init( $settings )
{
    $style_formats = array(
    	array(
    		'title' => 'Style: Intro Paragraph',
    		'block' => 'p',
    		'classes' => 'intro',
    	),
    	array(
    		'title' => 'Tag: Paragraph',
    		'block' => 'p',
    	),
    	array(
    		'title' => 'Tag: Office List Links',
    		'classes' => 'offices-list-links',
    	),
    	array(
    		'title' => 'Tag: Read More Link',
    		'block' => 'a',
    		'classes' => 'read-more',
    	),
    	array(
    		'title' => 'Tag: H1',
    		'block' => 'h1',
    	),
    	array(
    		'title' => 'Tag: H2',
    		'block' => 'h2',
    	),
    	array(
    		'title' => 'Tag: H3',
    		'block' => 'h3',
    	),
    	array(
    		'title' => 'Style: Heading 1',
    		'selector' => '*',
    		'classes' => 'h1',
    	),
    	array(
    		'title' => 'Style: Heading 2',
    		'selector' => '*',
    		'classes' => 'h2',
    	),
    	array(
    		'title' => 'Style: Heading 3',
    		'selector' => '*',
    		'classes' => 'h3',
    	),
    	array(
    		'title' => 'Style: Heading 4',
    		'selector' => '*',
    		'classes' => 'h4',
    	),
    	array(
    		'title' => 'Style: Heading 5',
    		'selector' => '*',
    		'classes' => 'h5',
    	),
    	array(
    		'title' => 'Style: Heading 6',
    		'selector' => '*',
    		'classes' => 'h6',
    	)
    );

    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;

}
add_filter( 'tiny_mce_before_init', 'my_mce_before_init' );

/**
 * Add slug to body class
 * 
 */

function add_body_class( $classes )
{
    global $post;
    if ( isset( $post ) ) {
        // $classes[] = $post->post_type . '-' . $post->post_name;
        $classes[] = $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_body_class' );

/**
 * Allow html in menu description field
 * 
 */

remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter( 'pre_link_description', 'wp_filter_kses' );
remove_filter( 'pre_link_notes', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );


function add_query_vars_filter( $vars ){
  $vars[] = "c";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

// //Ensure the $wp_rewrite global is loaded
// global $wp_rewrite;
// //Call flush_rules() as a method of the $wp_rewrite object
// $wp_rewrite->flush_rules();

?>