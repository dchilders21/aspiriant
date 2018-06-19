<?php

if ( ! defined( 'ABSPATH' ) ) exit;

//http://stackoverflow.com/questions/25461277/store-the-content-into-a-javascript-jquery-variable

ob_start();
the_content();
$printcontent = ob_get_contents();
ob_end_clean();

$article_title = get_the_title();
    
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
    $temp_lastname = $lastname;

}

function sort_last_name($names, $allen){


    // Removing allen from the sort list
    foreach ($names as $key => $value) {
        if ($names[$key]['lastname'] == $allen) {
            $toAddLater = $names[$key];
            unset($names[$key]);
        }
    }

    // Sorting
    $lastname = array();
    foreach ($names as $key => $row){
        $lastname[$key] = $row['lastname'];
    }

    array_multisort($lastname, SORT_ASC, $names);
    array_unshift($names, $toAddLater);
    return $names;
}


$sorted_array = sort_last_name($tmp_array, 'grecsek');

$authors_array_sorted = array();

for ($i=0; $i < count($sorted_array); $i++) {
    array_push($authors_array_sorted, $sorted_array[$i]['firstname'] . "-" . $sorted_array[$i]['lastname']);
}

$bio_args = array(
    'posts_per_page' => sizeof($categories),
    'post_type' => 'bio',
    'post_status' => array( 'publish', 'private' ),
    'post_name__in' => $authors_array_sorted
);

$bio_query = new WP_Query( $bio_args );

$combined_array = array();

$index_bio = 0;

// Loop through the authors
while ( $bio_query->have_posts() ) : $bio_query->the_post();

    $category_slug = $categories[$index_bio]->slug;
    $category_name = $categories[$index_bio]->name;

    $title_args = array(
        'post_type' => 'bio',
        'name' => $category_slug
    );

    $title_query = new WP_Query( $title_args );

    // Find the title and combine in one array.  Pass to the print function
    while ( $title_query->have_posts() ) : $title_query->the_post();
        $bio_id = get_the_ID();
        $job_title = get_post_meta( $bio_id, 'bio_job_title', true );
        $combined_array[$category_name] = $job_title;
    endwhile;   

    $index_bio++;

endwhile;   

?>

<script> 

jQuery(document).ready(function($){
    //console.log('<?php esc_attr(the_ID()); ?>');
    //console.log('<?php echo $category_slug ?>'); 
    //console.log('<?php echo $bio_title ?>'); 
    console.log(' ======== ========')


    sessionStorage.setItem('h5ab-print-article', '<header class="header"><img src="/wp/wp-content/uploads/fathom-logo-print.jpg" alt="Fathom"></header><div id="h5ab-print-content" style="margin-left:20px;"><h1><?php echo $article_title; ?></h1><p><?php
        $i = 1;
        $len = count(get_the_category());
        echo '<div style="display: flex; flex-direction: row;">';
        foreach($combined_array as $key => $value) { 
            echo '<div style="flex-basis: 25%; margin-left: 10px; margin-right: 10px;">';
            echo '<span><b>';
            echo $key; 
            echo '</b></span><br />'; 
            echo '<span>';
            echo $value;
            echo '</span>';
            echo '</div>';
        } 
        echo '</div>';
        ?></p>' + <?php echo json_encode( $printcontent ); ?> + '</div><footer style="font-size:16px; margin-left:20px;">&copy; 1991-2018 Aspiriant, LLC</footer>');

    $.strRemove = function(theTarget, theString) {
        return $("<div/>").append(
            $(theTarget, theString).remove().end()
        ).html();
    };

    var articleStr = sessionStorage.getItem('h5ab-print-article');
    var removeArr = ['video','audio','script','iframe'];

    $.each(removeArr, function(index, value){
        var processedCode = $.strRemove(value, articleStr);
        articleStr = processedCode;
    });
    
    var fullPrintContent = articleStr;
    sessionStorage.setItem('h5ab-print-article', fullPrintContent);
    
});

</script>