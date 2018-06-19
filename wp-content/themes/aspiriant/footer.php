			<footer class="footer twelvecol" role="contentinfo">

				<div id="inner-footer" class="clearfix">

					<nav role="navigation">
						<span class="social-icons"><?php
							if(is_active_sidebar('social_media')){
								dynamic_sidebar('social_media');
							}
						?></span>
						<?php bones_footer_links(); ?>
					</nav>

					<?php /*<p class="source-org copyright">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
					*/?>

				</div> <!-- end #inner-footer -->

			</footer> <!-- end footer -->

		</div> <!-- end #container -->

		<!-- all js scripts are loaded in library/bones.php -->
		<?php wp_footer(); ?>

        <script type="application/ld+json">

        { "@context" :  "http://schema.org",

          "@type" :     "Organization",

          "name" :      "Aspiriant",

          "url" :       "http://aspiriant.com",

          "sameAs" : [  "http://www.facebook.com/Aspiriant",

                        "http://www.twitter.com/AspiriantNews",

                        "http://www.linkedin.com/company/aspiriant",

                        "http://plus.google.com/+AspiriantSanFrancisco",

                        "http://plus.google.com/+AspiriantLosAngeles",

                        "http://plus.google.com/+AspiriantNewYork",

                        "http://plus.google.com/+AspiriantCincinnati",

                        "http://plus.google.com/+AspiriantMilwaukee",

                        "http://plus.google.com/+AspiriantMinneapolis",

                        "http://plus.google.com/+AspiriantBoston" ] }

        </script>

	</body>

</html> <!-- end page. what a ride! -->
