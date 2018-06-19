<?php get_header(); ?>

	<div id="content">

    <div id="inner-content" class="wrap clearfix">

      <?php
      //layout rules
      //if there is sidebar content and there's a featured image them make div span twelve col if not
      // then div for featured image and content should only be ninecol
      $sidebar =  get_post_meta($post->ID, 'sidebar_info', true); // the event is housed on an external page

      $slug = $post->post_name; //get the name of the title and match it with category

      if ($sidebar && has_post_thumbnail()) {

				echo '<div class="twelvecol first clearfix" role="main">';

      } else {

				echo '<div class="twelvecol first clearfix" role="main">';

      }

			if (have_posts()) : while (have_posts()) : the_post();

      //if alternate title get it
      $alternate_title = get_post_meta($post->ID, 'tagline_info', true);

      if ($alternate_title) { ?>

        <header class="article-header">
            <h1 class="page-title" itemprop="headline"><?php echo $alternate_title; ?></h1>
        </header>

      <?php } else {?>

				<header class="article-header">
            <h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
        </header>

      <?php } ?>

      <?php

      if ( has_post_thumbnail() && !is_page('the-client-experience') && !is_page('the-client-experience-3') ) {
          the_post_thumbnail('full');
      }

      ?>

      <div id="main" class="eightcol">

        <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

          <section class="entry-content clearfix" itemprop="articleBody">

            <?php

            the_content();

            ?>

          </section>

        </article>

      </div>

      <?php if($sidebar || is_page('faq')) { ?>
      <div class="threecol sidebar-container">
        <?php get_template_part( 'sidebar', 'page' ); ?>
      </div>
      <?php } ?>

      <footer class="article-footer">
        <?php the_tags('<span class="tags">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?>
      </footer>

      <?php endwhile; else : ?>

        <article id="post-not-found" class="hentry clearfix">
            <header class="article-header">
                <h1><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
            </header>
            <section class="entry-content">
                <p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
            </section>
            <footer class="article-footer">
                    <p><?php _e("This is the error message in the page.php template.", "bonestheme"); ?></p>
            </footer>
        </article>

      <?php endif; ?>

      </div> <!-- end #main -->

    </div> <!-- end #inner-content -->

  </div> <!-- end #content -->

<?php get_footer(); ?>
