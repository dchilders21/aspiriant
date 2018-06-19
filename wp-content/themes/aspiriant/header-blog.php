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
		<meta http-equiv="X-UA-Compatible" content="IE=9,IE=10,IE=11,IE=edge,chrome=1">

		<title><?php wp_title(''); ?></title>

		<!-- mobile meta (hooray!) -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<!-- Kinney webfont, served from terminaldesign.com -->
		<!-- <link rel="stylesheet" href="http://webfonts.terminaldesign.com/aspiriant.com/type.css" type="text/css" charset="utf-8" /> -->
		
		<!-- jQuery Modal styles -->
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/js/libs/jquery-modal/jquery.modal.min.css" type="text/css" charset="utf-8" />

		<!-- icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) -->
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-icon-touch.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">

		<!-- FontAwesome -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

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

		</script>

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
		<!-- end analytics -->


		<!-- Alexa code -->
		<!-- Start Alexa Certify Javascript -->
		<script type="text/javascript">
		_atrk_opts = { atrk_acct:"3VaCo1IWhe106C", domain:"aspiriant.com",dynamic: true};
		(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
		</script>
		<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=3VaCo1IWhe106C" style="display:none" height="1" width="1" alt="" /></noscript>
		<!-- End Alexa Certify Javascript --> 

		<!-- Hotjar Tracking Code for http://aspiriant.com/fathom/ -->
		<script>
		    (function(h,o,t,j,a,r){
		        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
		        h._hjSettings={hjid:377354,hjsv:6};
		        a=o.getElementsByTagName('head')[0];
		        r=o.createElement('script');r.async=1;
		        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
		        a.appendChild(r);
		    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
		</script>  


	</head>

	<body <?php body_class('mobile fathom'); ?>><div id="container">
			<header class="header" role="banner">
				<div id="inner-header" class="twelvecol clearfix">
					<!-- to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> -->
					<p id="logo"><a href="<?php echo get_post_type_archive_link( 'fathom' ); ?>" rel="nofollow">
					<img src="/wp/wp-content/uploads/fathom-logo.png" alt="Fathom" />
					</a>
					</p>

					<!-- if you'd like to use the site description you can un-comment it below -->
					<?php // bloginfo('description'); ?>
					<?php
					$term_name = get_query_var( 'c' ); //get query string
					?>

					<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
						<div>
							<!-- FontAwesome icon for search -->
							<label for="search-input">
							    <i class="fa fa-search"></i>
							</label>

							<input type="text" size="18" value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" class="searchbox" />
							<input type="hidden" name="post_type[]" value="fathom" />
							<!-- <input type="hidden" name="post_type[]" value="post_type_two" />
							<input type="hidden" name="post_type[]" value="post_type_three" /> -->

							<!-- <input type="submit" id="searchsubmit" value="Search" class="btn" /> -->
						</div>
					</form>
				</div> <!-- end #inner-header -->
				<div class="large-scrn">
					<nav role="navigation" class="tencol">
						<ul id="menu-main-nav" class="nav top-nav clearfix">
							<div>
							    <li class="menu-item menu-item-type-post_type menu-item-object-page">
							    	<?php
										// wp_nav_menu( array( 'menu' => 'Fathom Blog', 'after' => '<span class="divider">|</span>' ) );
										wp_nav_menu( array( 'menu' => 'Fathom Blog' ) );
										?>
							    </li>
							</div>
						</ul>
						<div id="hamburger">
							<div id="icon"><a href="#" class="hamburger-menu-link"><!-- &#9776; --></a></div>
							<div id="menu">
							  <div><a href="/">Aspiriant Home</a></div>
							  <div><a href="/what-exactly-we-do/">What We Do</a></div>
							  <div><a href="/our-exceptional-people/">Our People</a></div>
							  <div><a href="/start-dialogue/">Start a Dialogue</a></div>
							</div>
						</div>					
					</nav>
				</div>
				<!-- end .large-scrn -->

				<div class="medium-scrn">
					<nav role="navigation" class="tencol">
						<ul id="menu-main-nav" class="nav top-nav clearfix">
							<div>
							    <li class="menu-item menu-item-type-post_type menu-item-object-page">
							    	<?php
										// wp_nav_menu( array( 'menu' => 'Fathom Blog', 'after' => '<span class="divider">|</span>' ) );
										wp_nav_menu( array( 'menu' => 'Fathom Blog' ) );
										?>
							    </li>
							</div>
						</ul>
						<div id="hamburger">
							<div id="icon"><a href="#" class="hamburger-menu-link"><!-- &#9776; --></a></div>
							<div id="menu">
							  <div><a href="/">Aspiriant Home</a></div>
							  <div><a href="/what-exactly-we-do/">What We Do</a></div>
							  <div><a href="/our-exceptional-people/">Our People</a></div>
							  <div><a href="/start-dialogue/">Start a Dialogue</a></div>
							</div>
						</div>
					</nav>
				</div>
				<!-- end .medium-scrn -->

				<div class="small-scrn">
					<div id="hamburger">
						<div id="icon"><a href="#" class="hamburger-menu-link"><!-- &#9776; --></a></div>
						<div id="menu">
						  <div class="menu-item menu-item-type-post_type menu-item-object-page">
							    <?php
									// wp_nav_menu( array( 'menu' => 'Fathom Blog', 'after' => '<span class="divider">|</span>' ) );
									wp_nav_menu( array( 'menu' => 'Fathom Blog' ) );
								?>
							</div>
						  <div><a href="/">Aspiriant Home</a></div>
						</div>
					</div>
				</div>
				<!--end .small-scrn -->			
			</header> <!-- end header -->