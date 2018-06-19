<?php $url = "/"; // set site url ?>
<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<?php dev4press_debug_page_request(); ?>
		<meta charset="utf-8">

		<!-- Google Chrome Frame for IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php wp_title(''); ?></title>

		<!-- mobile meta (hooray!) -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<!-- Kinney webfont, served from terminaldesign.com -->
		<!--<link rel="stylesheet" href="http://webfonts.terminaldesign.com/aspiriant.com/type.css" type="text/css" charset="utf-8" />-->


		<!-- icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) -->
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-icon-touch.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<!-- or, set /favicon.ico for IE10 win -->
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->

		<!-- drop Google Analytics Here -->
		<script>
 			 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 			 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

 			 ga('create', 'UA-17893662-1', 'auto');
 			 ga('send', 'pageview');
			 ga('require', 'linkid');

		</script>

<!-- Hotjar Tracking Code for www.aspiriant.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:376830,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>

<!-- end analytics -->
<!-- Wishpond Tracking Code -->

<script type="text/javascript">
(function(d, s, id) {
  window.Wishpond = window.Wishpond || {};
  Wishpond.merchantId = '1298247';
  Wishpond.writeKey = '0f47e5ae861e';
  var js, wpjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//cdn.wishpond.net/connect.js";
  wpjs.parentNode.insertBefore(js, wpjs);
}(document, 'script', 'wishpond-connect'));
</script>

	</head>

	<body <?php body_class('mobile'); ?>><div id="container">

			<header class="header twelvecol" role="banner">

				<div id="inner-header" class="clearfix">
					
					<!-- to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> -->
					<p id="logo"><a href="<?php echo home_url(); ?>" rel="nofollow">
					<img src="/wp/wp-content/uploads/aspiriant-logo.png" alt="Aspiriant" />
					</a>
					</p>
					
					<a class="client-login" href="/client-login2/">Client Login</a>
					

					<!-- if you'd like to use the site description you can un-comment it below -->
					<?php // bloginfo('description'); ?>
					<?php     
					$term_name = get_query_var( 'c' ); //get query string 
					?>

					<nav role="navigation">
						<ul id="menu-main-nav" class="nav top-nav clearfix">
							<li id="menu-item-37" class="<?php if(is_page('the-client-experience')) { echo 'active-trail '; } ?> menu-item menu-item-type-post_type menu-item-object-page menu-item-37"><a href="/the-client-experience/">The Client Experience</a> | </li>
							<li id="menu-item-91" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-91"><a href="#">Everything Else</a></li>
							<div id="everything-else">
							    <li id="menu-item-36" class="<?php if (is_page(array( 'why-aspiriant-unusual', 'our-story', 'how-to-select-a-firm', 'mutual-fund', 'faq' ) )) { echo 'active-trail '; } ?> menu-item menu-item-type-post_type menu-item-object-page menu-item-36"><a href="<?= $url; ?>why-aspiriant-unusual/">Why We’re Unusual</a> <span class="divider">|</span> 
							    	<?php
									if (is_page(array('why-aspiriant-unusual', 'how-to-select-a-firm', 'our-story', 'mutual-fund', 'faq'))) 
									{
										wp_nav_menu( array( 'theme_location' => 'secondary-nav-unusual', 'menu_class'=> 'secondary-nav mobile', 'link_after' => '<span class="divider">|</span>') );
									}
									?>
							    </li>
								<li id="menu-item-35" class="<?php if (is_archive() || is_page( array('los-angeles', 'san-francisco', 'new-york', 'boston', 'minneapolis', 'milwaukee', 'cincinnati', 'irvine' )) || get_post_type() == 'bio' ) { echo 'active-trail '; } ?> menu-item menu-item-type-post_type menu-item-object-page menu-item-35"><a href="<?= $url; ?>our-exceptional-people/">Our Exceptional People</a> <span class="divider">|</span> 
									<?php
									if (is_archive('our-exceptional-people') || 'bio' == get_post_type()) 
									{
										wp_nav_menu( array( 'theme_location' => 'secondary-nav', 'menu_class'=> 'secondary-nav mobile', 'link_after' => '<span class="divider">|</span>' ) );
										wp_nav_menu( array( 'theme_location' => 'tertiary-nav', 'menu_id'=>'tertiary', 'menu_class'=> 'tertiary-nav mobile', 'link_after' => '<span class="divider">|</span>' ) );
									}
									?>
								</li>
								<li id="menu-item-34" class="<?php if (is_page(array('what-exactly-we-do', 'investment-management', 'custom-wealth-planning', 'family-office') )) { echo 'active-trail '; } ?>menu-item menu-item-type-post_type menu-item-object-page menu-item-34"><a href="<?= $url; ?>what-exactly-we-do/">What Exactly We Do</a> <span class="divider">|</span> 
									<?php
									if (is_page(array ('what-exactly-we-do', 'investment-management', 'custom-wealth-planning', 'family-office') )) 
									{
										wp_nav_menu( array( 'theme_location' => 'secondary-nav-services', 'menu_class'=> 'secondary-nav mobile', 	'link_after' => '<span class="divider">|</span>') );
									}
									?>
								</li>
								<li id="menu-item-33" class="<?php if (is_page('offices-coast-to-coast')) { echo 'active-trail '; } ?>menu-item menu-item-type-post_type menu-item-object-page menu-item-33"><a href="<?= $url; ?>offices-coast-to-coast/">Offices Coast-to-Coast</a> <span class="divider">|</span> </li>
								<li id="menu-item-32" class="<?php 
								if (is_page(array('news-intel', 'library', 'intel', 'press', 'events', 'publications', 'white-papers', 'insight')) || get_post_type() == 'video') { echo 'active-trail '; } ?>menu-item menu-item-type-post_type menu-item-object-page menu-item-32"><a href="<?= $url; ?>news-intel/">News &amp; Intel</a> <span class="divider">|</span> 
									<?php
									if (is_page( array('news-intel', 'intel', 'library', 'press', 'insight', 'white-papers', 'events', 'publications', 'insight-sign-up') )) 
									{
										wp_nav_menu( array( 'theme_location' => 'secondary-nav-news', 'menu_class'=> 'secondary-nav mobile', 'link_after' => '<span class="divider">|</span>') );
										wp_nav_menu( array( 'theme_location' => 'tertiary-nav-library', 'menu_class'=> 'tertiary-nav mobile', 'link_after' => '<span class="divider">|</span>') );
							
									}
									// if (is_page( array('library', 'insight', 'white-papers', 'publications', 'events') )) 
									// {
									// 	wp_nav_menu( array( 'theme_location' => 'tertiary-nav-library', 'menu_class'=> 'tertiary-nav mobile', 'link_after' => '<span class="divider">|</span>') );
									// }
									?>
								</li>
							    <li id="menu-item-31" class="<?php if (is_page(array ('start-dialogue', 'investment-managers', 'press-contacts', 'job-seekers') )) { echo 'active-trail '; } ?>menu-item menu-item-type-post_type menu-item-object-page menu-item-31"><a href="<?= $url; ?>start-dialogue/">Start a Dialogue</a>
							    	<?php
									if (is_page(array ('start-dialogue', 'investment-managers', 'press-contacts', 'job-seekers') )) 
									{
										wp_nav_menu( array( 'theme_location' => 'secondary-nav-contact', 'menu_class'=> 'secondary-nav mobile', 'link_after' => '<span class="divider">|</span>') );
									}
									?>
							    </li>
							</div>
						</ul>					
					</nav>


					<?php /*<nav role="navigation">
						<?php bones_main_nav(); ?>
					</nav> */?>

				</div> <!-- end #inner-header -->

			</header> <!-- end header -->

			<?php
			if (is_archive('our-exceptional-people') || 'bio' == get_post_type()) 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav', 'menu_class'=> 'secondary-nav', 'link_after' => '<span class="divider">|</span>' ) );
				wp_nav_menu( array( 'theme_location' => 'tertiary-nav', 'menu_id'=>'tertiary', 'menu_class'=> 'tertiary-nav', 'link_after' => '<span class="divider">|</span>' ) );
			} 
			//	if it's tax term LA, SF, NY, BA, etc then show the nav
			elseif ( is_page( array('los-angeles', 'san-francisco', 'new-york', 'boston', 'minneapolis', 'milwaukee', 'cincinnati', 'irvine') ) || 'bio' == get_post_type() ) 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav', 'menu_class'=> 'secondary-nav', 'link_after' => '<span class="divider">|</span>' ) );
				wp_nav_menu( array( 'theme_location' => 'tertiary-nav', 'menu_class'=> 'tertiary-nav', 'link_after' => '<span class="divider">|</span>' ) );
			} 
			elseif (is_page(array('why-aspiriant-unusual', 'how-to-select-a-firm', 'our-story', 'mutual-fund', 'faq'))) 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav-unusual', 'menu_class'=> 'secondary-nav', 'link_after' => '<span class="divider">|</span>') );
			} 
			elseif (is_page('our-services')) 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav-services', 'menu_class'=> 'secondary-nav', 	'link_after' => '<span class="divider">|</span>') );
			} 
			elseif (is_page(array ('what-exactly-we-do', 'investment-management', 'custom-wealth-planning', 'family-office') )) 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav-services', 'menu_class'=> 'secondary-nav', 	'link_after' => '<span class="divider">|</span>') );
			}
			elseif (is_page( array('news-intel', 'intel', 'library', 'press', 'insight', 'white-papers', 'events', 'publications', 'insight-sign-up') )) 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav-news', 'menu_class'=> 'secondary-nav', 'link_after' => '<span class="divider">|</span>') );
				if (is_page( array('library', 'insight', 'white-papers', 'publications', 'events', 'insight-sign-up') )) 
				{
				wp_nav_menu( array( 'theme_location' => 'tertiary-nav-library', 'menu_class'=> 'tertiary-nav', 'link_after' => '<span class="divider">|</span>') );
				}
			} 
			elseif (get_post_type() == 'video') 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav-news', 'menu_class'=> 'secondary-nav', 'link_after' => '<span class="divider">|</span>') );
				wp_nav_menu( array( 'theme_location' => 'tertiary-nav-library', 'menu_class'=> 'tertiary-nav', 'link_after' => '<span class="divider">|</span>') );
			} 
			elseif (get_post_type() == 'bio') 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav', 'menu_class'=> 'secondary-nav', 'link_after' => '<span class="divider">|</span>') );
				wp_nav_menu( array( 'theme_location' => 'tertiary-nav', 'menu_class'=> 'tertiary-nav', 'link_after' => '<span class="divider">|</span>') );
			} 
			elseif (is_page(array ('start-dialogue', 'investment-managers', 'press-contacts', 'job-seekers') )) 
			{
				wp_nav_menu( array( 'theme_location' => 'secondary-nav-contact', 'menu_class'=> 'secondary-nav', 'link_after' => '<span class="divider">|</span>') );
			} 
			else {}
			?>