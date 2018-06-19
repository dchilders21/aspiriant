<?php

/* !Alternate Title and Tagline */
/*************************************/
function add_tagline_meta_box() {
    add_meta_box(
        'tagline_meta_box', // $id
        'Title and Tagline Information', // $title
        'show_tagline_meta_box', // $callback
        'page', // $page
        'side', // $context
        'high'); // $priority
}

global $post;
add_action('add_meta_boxes', 'add_tagline_meta_box');


// Field Array
$prefix = 'tagline_';
$tagline_meta_fields = array(
    array(
        'label'=> 'Title and Tagline Information',
        'id'    => $prefix.'info',
        'type'  => 'textarea'
    )
);

// The Callback
function show_tagline_meta_box() {
    global $tagline_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="tagline_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($tagline_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <td>';
                switch($field['type']) {
                    // case items will go here

                    // textarea
                    case 'textarea':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo wp_editor( $meta, 'content-id-tageline', array( 'textarea_name' => ''.$field['id'].'', 'quicktags'=> true, 'textarea_rows' => 4, 'media_buttons' => false, 'wpautop' => true) ); 
                        //echo '<br /><span class="description">'.$field['desc'].'</span>';
                    break;

                    
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_tagline_meta($post_id) {
    global $tagline_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['tagline_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    // loop through fields and save the data
    foreach ($tagline_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_tagline_meta');



/* !Sidebar metabox */
/*************************************/
function add_sidebar_meta_box() {
    add_meta_box(
        'sidebar_meta_box', // $id
        'Sidebar Information', // $title
        'show_sidebar_meta_box', // $callback
        'page', // $page
        'normal', // $context
        'high'); // $priority
}

global $post;
add_action('add_meta_boxes', 'add_sidebar_meta_box');


// Field Array
$prefix = 'sidebar_';
$sidebar_meta_fields = array(
    array(
        'label'=> 'Sidebar Information',
        'id'    => $prefix.'info',
        'type'  => 'textarea'
    )
);

// The Callback
function show_sidebar_meta_box() {
    global $sidebar_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="sidebar_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($sidebar_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <td>';
                switch($field['type']) {
                    // case items will go here

                    // textarea
                    case 'textarea':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo wp_editor( $meta, 'content-id', array( 'textarea_name' => ''.$field['id'].'', 'quicktags'=> true, 'textarea_rows' => 4, 'media_buttons' => false, 'wpautop' => true) ); 
                        //echo '<br /><span class="description">'.$field['desc'].'</span>';
                    break;

                    
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_sidebar_meta($post_id) {
    global $sidebar_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['sidebar_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('sidebar' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    // loop through fields and save the data
    foreach ($sidebar_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_sidebar_meta');


/*This file handles all meta boxes added to the admin interface*/

/* !Bio metabox */
/****************************/
function add_bio_meta_box() {
    add_meta_box(
        'bio_meta_box', // $id
        'Bio Information', // $title
        'show_bio_meta_box', // $callback
        'bio', // $page
        'normal', // $context
        'high'); // $priority
}

global $post;
add_action('add_meta_boxes', 'add_bio_meta_box');


// Field Array
//bio_last_name
$prefix = 'bio_';
$bio_meta_fields = array(
    array(
        'label'=> 'Last Name',
        'desc'  => 'Used to order alphabetically.',
        'id'    => $prefix.'last_name',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Job Title',
        'desc'  => '',
        'id'    => $prefix.'job_title',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Job Title 2',
        'desc'  => '',
        'id'    => $prefix.'job_title_2',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Accreditation',
        'desc'  => '',
        'id'    => $prefix.'accreditation',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Email',
        'desc'  => '',
        'id'    => $prefix.'email',
        'type'  => 'text'
    ),
    array(
        'label'=> 'More Resources (videos, white papers, press)',
        'desc'  => '',
        'id'    => $prefix.'more_resources',
        'type'  => 'textarea'
    ),
	array(
        'label'=> 'Full Bio',
        'desc'  => '',
        'id'    => $prefix.'full_bio',
        'type'  => 'textarea2'
    ),
    array(
        'label'=> 'Link 1',
        'desc'  => '',
        'id'    => $prefix.'link1',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Link 2',
        'desc'  => '',
        'id'    => $prefix.'link2',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Link 3',
        'desc'  => '',
        'id'    => $prefix.'link3',
        'type'  => 'text'
    ),
);

// The Callback
function show_bio_meta_box() {
    global $bio_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="bio_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($bio_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <td>';
                switch($field['type']) {
                    // case items will go here

                    // text
                    case 'text':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;

                    // textarea
                    case 'textarea':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo wp_editor( $meta, 'content-id', array( 'textarea_name' => ''.$field['id'].'', 'quicktags'=> true, 'textarea_rows' => 4, 'media_buttons' => false, 'wpautop' => true) ); 
                        echo '<br /><span class="description">'.$field['desc'].'</span>';
                    break;

                    // textarea 2
                    case 'textarea2':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo wp_editor( $meta, 'content-id', array( 'textarea_name' => ''.$field['id'].'', 'quicktags'=> true, 'textarea_rows' => 10, 'media_buttons' => false, 'wpautop' => true) ); 
                        echo '<br /><span class="description">'.$field['desc'].'</span>';
                    break;

                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                            <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;

                    // radio
                    case 'radio':
                        foreach ( $field['options'] as $option ) {
                            echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
                                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                        }
                    break;
                    
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_bio_meta($post_id) {
    global $bio_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['bio_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('bio' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    // loop through fields and save the data
    foreach ($bio_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_bio_meta');




/* !News & Intel metabox */
/*************************************/
function add_news_intel_meta_box() {
    add_meta_box(
        'news_intel_meta_box', // $id
        'News &amp; Intel Information', // $title
        'show_news_intel_meta_box', // $callback
        'news_intel', // $page
        'normal', // $context
        'high'); // $priority
}

global $post;
add_action('add_meta_boxes', 'add_news_intel_meta_box');


// Field Array
$prefix = 'news_intel_';
$news_intel_meta_fields = array(
    array(
        'label'=> 'Article Source',
        'desc'  => '(e.g. Bloomberg)',
        'id'    => $prefix.'article_source',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Article Description',
        'desc'  => '',
        'id'    => $prefix.'article_description',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Article URL',
        'desc'  => '',
        'id'    => $prefix.'article_url',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Article Date',
        'desc'  => '',
        'id'    => $prefix.'article_date',
        'type'  => 'date'
    ),
);

// The Callback
function show_news_intel_meta_box() {
    global $news_intel_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="news_intel_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($news_intel_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <td>';
                switch($field['type']) {
                    // case items will go here

                    // text
                    case 'text':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;

                    // textarea
                    case 'textarea':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo wp_editor( $meta, 'content-id', array( 'textarea_name' => ''.$field['id'].'', 'quicktags'=> true, 'textarea_rows' => 4, 'media_buttons' => false, 'wpautop' => true) ); 
                        echo '<br /><span class="description">'.$field['desc'].'</span>';
                    break;

                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                            <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;

                    // radio
                    case 'radio':
                        foreach ( $field['options'] as $option ) {
                            echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
                                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                        }
                    break;

                    // date
                    case 'date':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;
                    
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_news_intel_meta($post_id) {
    global $news_intel_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['news_intel_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('news_intel' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    // loop through fields and save the data
    foreach ($news_intel_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_news_intel_meta');


/* !Publications metabox */
/*************************************/
function add_publications_meta_box() {
    add_meta_box(
        'publications_meta_box', // $id
        'Publications Information', // $title
        'show_publications_meta_box', // $callback
        'publications', // $page
        'normal', // $context
        'high'); // $priority
}

global $post;
add_action('add_meta_boxes', 'add_publications_meta_box');


// Field Array
$prefix = 'publications_';
$publications_meta_fields = array(
    // array(
    //     'label'=> 'Publication Source',
    //     'desc'  => '(e.g. Bloomberg)',
    //     'id'    => $prefix.'article_source',
    //     'type'  => 'text'
    // ),
    array(
        'label'=> 'Publication Description',
        'desc'  => '',
        'id'    => $prefix.'description',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Publication URL (if not company publication)',
        'desc'  => '',
        'id'    => $prefix.'url',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Publication Date',
        'desc'  => '',
        'id'    => $prefix.'date',
        'type'  => 'date'
    ),
);

// The Callback
function show_publications_meta_box() {
    global $publications_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="publications_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($publications_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <td>';
                switch($field['type']) {
                    // case items will go here

                    // text
                    case 'text':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;

                    // textarea
                    case 'textarea':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo wp_editor( $meta, 'content-id', array( 'textarea_name' => ''.$field['id'].'', 'quicktags'=> true, 'textarea_rows' => 4, 'media_buttons' => false, 'wpautop' => true) ); 
                        echo '<br /><span class="description">'.$field['desc'].'</span>';
                    break;

                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                            <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;

                    // radio
                    case 'radio':
                        foreach ( $field['options'] as $option ) {
                            echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
                                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                        }
                    break;

                    // date
                    case 'date':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;
                    
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_publications_meta($post_id) {
    global $publications_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['publications_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('publications' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    // loop through fields and save the data
    foreach ($publications_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_publications_meta');

/* !Video metabox */
/*************************************/
function add_video_meta_box() {
    add_meta_box(
        'video_meta_box', // $id
        'Video Information', // $title
        'show_video_meta_box', // $callback
        'video', // $page
        'normal', // $context
        'high'); // $priority
}

global $post;
add_action('add_meta_boxes', 'add_video_meta_box');


// Field Array
$prefix = 'video_';
$video_meta_fields = array(
    array(
        'label'=> 'Video Description',
        'desc'  => '',
        'id'    => $prefix.'description',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Video Embed Code',
        'desc'  => '',
        'id'    => $prefix.'code',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Video Date',
        'desc'  => '',
        'id'    => $prefix.'date',
        'type'  => 'date'
    ),
);

// The Callback
function show_video_meta_box() {
    global $video_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="video_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($video_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <td>';
                switch($field['type']) {
                    // case items will go here

                    // text
                    case 'text':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;

                    // textarea
                    case 'textarea':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo wp_editor( $meta, 'content-id', array( 'textarea_name' => ''.$field['id'].'', 'quicktags'=> true, 'textarea_rows' => 4, 'media_buttons' => false, 'wpautop' => true) ); 
                        echo '<br /><span class="description">'.$field['desc'].'</span>';
                    break;

                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                            <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;

                    // radio
                    case 'radio':
                        foreach ( $field['options'] as $option ) {
                            echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
                                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
                        }
                    break;

                    // date
                    case 'date':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;
                    
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_video_meta($post_id) {
    global $video_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['video_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('video' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    // loop through fields and save the data
    foreach ($video_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_video_meta');


/* !Fathom metaboxes */
/****************************/
function add_fathom_meta_box() {
    add_meta_box(
        'fathom_meta_box', // $id
        'Fathom Post Options', // $title
        'show_fathom_meta_box', // $callback
        'fathom', // $page
        'side', // $context
        'high'); // $priority
}

global $post;
add_action('add_meta_boxes', 'add_fathom_meta_box');


// Field Array
$prefix = 'fathom_';
$fathom_meta_fields = array(
    array(
        'label'=> 'Short Headline',
        'id'    => $prefix.'short_headline',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Sticky on Fathom home page',
        'desc'  => 'Show top of Fathom home page',
        'id'    => $prefix.'sticky',
        'type'  => 'checkbox'
    )
);

// The Callback
function show_fathom_meta_box() {
    global $fathom_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="fathom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($fathom_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <td>';
                switch($field['type']) {

                    // text
                    case 'text':
                        echo '<span class="label">'.$field['label'].'</span><br />';
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /><br />';
                        echo '<span class="description">'.$field['desc'].'</span><br />';
                    break;

                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                            <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;
                    
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_fathom_meta($post_id) {
    global $fathom_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['fathom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('fathom' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    // loop through fields and save the data
    foreach ($fathom_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
}

add_action('save_post', 'save_fathom_meta');

?>