  <?php

  // If "fathom/insight" exists in URL, show Insight sidebar

  $url = $_SERVER["REQUEST_URI"];
  $isInsight = strpos($url, 'fathom/insight');

  if ( $isInsight !== false ) { ?>
  <div role="complementary" id="fathom-sidebar" class="insight-sidebar threecol push-right">
    <div class="clearfix">
      <?php
      // If "fathom/insight" exists in URL, show Insight signup form

      $url = $_SERVER["REQUEST_URI"];
      $isInsight = strpos($url, 'fathom/insight');

      if ( $isInsight !== false ) { ?>
      
      <div class="insight_newsletter_container_sidebar large-scrn">
        <h2>Insight</h2>
        <div class="insight_newsletter_signup">
          <a href="#modal" rel="modal:open" class="insight-modal-link"><span class="fa fa-envelope-square fa-2x insight-icon" aria-hidden="true"></span> Get our newsletter</a>
        </div>

        <div class="insight_newsletter_modal" id="modal" style="display:none;">

          <div class="insight_newsletter_container clearfix">

            <h2 class="newsletter-hed">
            Want our insights delivered straight to your inbox?
            </h2>

            <div class="newsletter-content">
              <p>Sign up for regular updates here.</p>

              <form action="https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST">
                <input name="oid" type="hidden" value="00Dd0000000bhHR" /> 
                <input name="retURL" type="hidden" value="http://aspiriant.com/insight-welcome/" /> 
                <input name="Referral_Source__c" type="hidden" value="Insight" /> 
                <input id="email" maxlength="80" name="email" value="Email" required="" size="30" type="text" class="text-field" onfocus="if (this.value=='Email') this.value='';" />
                <br />
                <input type="submit" value="Submit" class="insight-form-submit" /> 
              </form>
            </div>

          </div>
        </div>
      </div>

      <?php
      } // end check for Insight post type ?>
    </div>
    
    <div class="sidebar-insight-contents">
      <h2>Contents</h2>
      <ul class="insight_contents"></ul>
    </div>
    <div class="sidebar-latest-insight">
      <h2>Recent Issues of Insight</h2>

      <?php

      // get 5 latest Insight posts
      $insight_args = array(
          'post_type' => 'fathom',
          'post_status' => 'publish',
          'posts_per_page' => 5,
          'tax_query' => array(
        		array(
        			'taxonomy' => 'fathom-type',
        			'field'    => 'slug',
        			'terms'    => 'insight',
        		),
        	)
      );

      $insight_query = new WP_Query( $insight_args ); ?>

      <ul>

        <?php

        while ( $insight_query->have_posts() ) : $insight_query->the_post(); ?>

          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

          <?php

        endwhile; wp_reset_query();

        ?>

      </ul>
    </div>

  <?php }
  // Else, show regular blog sidebar
  else { ?>
  <div role="complementary" id="fathom-sidebar" class="threecol push-right">

    <div class="sidebar-latest-posts">
      <h2>Latest Fathom Posts</h2>

      <ul>

      <?php

      // get Fathom posts exclude Insight
      $fathom_args = array(
          'post_type' => 'fathom',
          'post_status' => 'publish',
          'posts_per_page' => 5,
          'tax_query' => array(
            array(
              'taxonomy' => 'fathom-type',
              'field'    => 'slug',
              'terms'    => 'insight',
              'operator' => 'NOT IN'
            ),
          )
      );

      $fathom_query = new WP_Query( $fathom_args );

      while ( $fathom_query->have_posts() ) : $fathom_query->the_post(); ?>

        <li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>

        <?php

      endwhile; wp_reset_query();

      ?>

      </ul>
    </div>

  <?php } // end else ?>

  <div>
    <div id="sidebar-contact_us">
      <a href="/start-dialogue/" onClick="__gaTracker('send', 'event', 'Contact Us Today', '‘Click’', ‘Start Dialogue from Click');">Contact Us Today</a>
      
    </div>
  </div>

  <h2>Share</h2>
  <div id="share-links">
      <a href="http://twitter.com/share?text=<?php the_title(); ?>&amp;url=<?php the_permalink(); ?>" title="Share on Twitter" target="_blank">
        <span class="fa fa-twitter-square fa-2x" aria-hidden="true"></span> 
      </a>
      <a title="Share on LinkedIn" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&source=<?php the_permalink(); ?>">
        <span class="fa fa-linkedin-square fa-2x" aria-hidden="true"></span> 
      </a>
      <a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" title="Share on Facebook" target="_blank">
        <span class="fa fa-facebook-square fa-2x" aria-hidden="true"></span>
      </a>

  </div>


</div>

