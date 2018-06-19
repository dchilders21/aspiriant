<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="twelvecol first clearfix" role="main">

						<h1 class="archive-title h2"><?= get_the_title(); ?></h1>

							<article classid="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<footer class="article-footer">

								</footer> <!-- end article footer -->

							</article> <!-- end article -->


						</div> <!-- end #main -->

						<?php //get_sidebar(); ?>

								</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
