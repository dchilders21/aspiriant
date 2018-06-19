/*
Bones Scripts File
Author: Eddie Machado, Dogmo Studios (@cchanse, @intrinsi, @ewee)

This file should contain any js scripts you want to add to the site.
Instead of calling it in the header or throwing it inside wp_head()
this file will be called automatically in the footer so as not to
slow the page load.

*/

// IE8 ployfill for GetComputed Style (for Responsive Script below)
if (!window.getComputedStyle) {
    window.getComputedStyle = function(el, pseudo) {
        this.el = el;
        this.getPropertyValue = function(prop) {
            var re = /(\-([a-z]){1})/g;
            if (prop == 'float') prop = 'styleFloat';
            if (re.test(prop)) {
                prop = prop.replace(re, function () {
                    return arguments[2].toUpperCase();
                });
            }
            return el.currentStyle[prop] ? el.currentStyle[prop] : null;
        }
        return this;
    }
}

//removing the last pipe in footer menu
jQuery("ul#menu-footer-links li span:last").remove();
jQuery('.secondary-nav li span:last').hide();


//removing the last pipe in tertiary menu
jQuery("ul#tertiary li span:last, ul#menu-why-we-are-unusual li span:last, ul#menu-our-services li span:last, ul#menu-locations li span:last, ul#menu-start-dialogue li span:last").remove();

jQuery("ul#menu-library-1 li span:last").remove();
jQuery("ul#menu-library li span:last").remove();

jQuery(".menu-locations-container ul#tertiary, ul#menu-by-office").hide();

jQuery(".menu-item-104").click(function () {
     jQuery(".menu-locations-container ul#tertiary, ul#menu-by-office").toggle();
     if (jQuery('a:contains(&#9650;)')) {
        var unicode_char = jQuery('a:contains(&#9650;)');
        unicode_char.text(unicode_char.text().replace('&#9650;', '&#9660;'));
    };

    if (jQuery(".menu-locations-container ul.tertiary-nav, ul#menu-by-office").is(':visible')) {
        jQuery("li.menu-item-104").addClass('active-trail');

    } else if(jQuery(".menu-locations-container ul#tertiary, ul#menu-by-office").css('display') == 'none') {
        jQuery("li.menu-item-104").removeClass('active-trail');
    }
});


// as the page loads, call these scripts
jQuery(document).ready(function($) {

    /*
    Responsive jQuery is a tricky thing.
    There's a bunch of different ways to handle
    it, so be sure to research and find the one
    that works for you best.
    */

    /* getting viewport width */
    var responsive_viewport = $(window).width();

    /* if is below 481px */
    if (responsive_viewport < 481) {
        //move sidebar image above text
       // jQuery('.sidebar-container img').prependTo("section.entry-content");
    } /* end smallest screen */

    /* if is larger than 481px */
    if (responsive_viewport > 481) {} /* end larger than 481px */

    /* if is above or equal to 768px */
    if (responsive_viewport >= 768) {
        $('body').removeClass('mobile');
        $('.mobile').remove();

        // if on home or the-client-experience page, collapse nav
        if ($('body').hasClass('home') || window.location.href.indexOf("the-client-experience") > -1)
        {
            $('#everything-else').hide();
        }
        // else, hide "Everything Else" nav link
        else
        {
            $('#menu-item-91').hide();
        }

        // homepage - clicking on 'experience'
        $('#menu-item-91').click(function () {
            $('.menu-item-91').hide();
        });

        /* load gravatars */
        $('.comment img[data-gravatar]').each(function(){
            $(this).attr('src',$(this).attr('data-gravatar'));
        });

    }

    /* off the bat large screen actions */
    if (responsive_viewport > 1030) {}

    // homepage - clicking on 'experience'
    $('#menu-item-91').click(function () {
        $('nav').addClass('full-nav');
        $('#everything-else').toggle();
    });

    // full bio
    $('#full-bio').hide();
    // $('.read-more').text('Read Full Bio');

    $('body.single-bio .read-more').click(function () {

        // $(this).text("Return to Profile");

        $('#bio').toggle('fast');
        $('#full-bio').toggle('fast');

        if ($(this).text() == "Return to Profile")
           $(this).text("Read full bio")
        else
           $(this).text("Return to Profile");

        // var txt = $("#full-bio").is(':visible') ? 'Return to profile' : 'Read full bio';
        // $(this).text(txt);

        // $('.read-more').toggle(function() {
        //      $('.read-more').text('Return to profile');
        // }, function() {
        //         $('.read-more').text('Read full bio');
        //     }
        // );


        // if($('.read-more:contains("Read Full Bio")')) {
        //                 console.log('true');

        //     $('.read-more:contains("Read Full Bio")').text('Return to profile');

        // } else if($('.read-more:contains("Return to profile")')){
        //     console.log('true that');
        //     $('.read-more:contains("Return to profile")').text('Read Full Bio');

        // }

    });

    // load jQuery UI datepicker on News & Intel admin page
    $('#news_intel_article_date').datepicker();

    // //removing the last pipe in secondary nav menu
    // $("ul#menu-footer-links li span:last").remove();

    // //removing the last pipe in footer nav menu
    // $("ul#menu-our-exceptional-people li span:last, ul#menu-why-we-are-unusual li span:last, ul#menu-our-services li span:last, ul#menu-locations li span:last, ul#menu-start-dialogue li span:last").remove();

    // Hamburger menu for Fathom blog
    $('.small-scrn #hamburger #icon').on('click', function(e) {
      e.preventDefault();
      $('.small-scrn #hamburger #menu').slideToggle();
    });
    $('.medium-scrn #hamburger #icon').on('click', function(e) {
      e.preventDefault();
      $('.medium-scrn #hamburger #menu').slideToggle();
    });
    $('.large-scrn #hamburger #icon').on('click', function(e) {
      e.preventDefault();
      $('.large-scrn #hamburger #menu').slideToggle();
    });

    /*
     * Add Insight sidebar content
     */

    // Give each heading an id starting with 1
    $(".entry-content h2").each(function(index){
      $(this).attr("id", "heading_" + (index + 1));
      $('.insight_contents').append('<li>' + '<a href="#heading_' + (index + 1) + '">' + jQuery(this).text() + '</a></li>');
    });
    
    // Show Insight newsletter
    // $(".insight_newsletter_container_sidebar a").on("click", function(e){
    //     console.log("clicked!");
    //     e.preventDefault();
    //     var form = $(".insight_newsletter_container_sidebar .insight_newsletter_modal");
    //     if ( form.is(':hidden') ) {
    //         form.show();
    //     }
    //     else {
    //         form.hide();
    //     }
    // });

}); /* end of as page load scripts */

/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w){
    // This fix addresses an iOS bug, so return early if the UA claims it's something else.
    if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ){ return; }
    var doc = w.document;
    if( !doc.querySelector ){ return; }
    var meta = doc.querySelector( "meta[name=viewport]" ),
        initialContent = meta && meta.getAttribute( "content" ),
        disabledZoom = initialContent + ",maximum-scale=1",
        enabledZoom = initialContent + ",maximum-scale=10",
        enabled = true,
        x, y, z, aig;
    if( !meta ){ return; }
    function restoreZoom(){
        meta.setAttribute( "content", enabledZoom );
        enabled = true; }
    function disableZoom(){
        meta.setAttribute( "content", disabledZoom );
        enabled = false; }
    function checkTilt( e ){
        aig = e.accelerationIncludingGravity;
        x = Math.abs( aig.x );
        y = Math.abs( aig.y );
        z = Math.abs( aig.z );
        // If portrait orientation and in one of the danger zones
        if( !w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ){
            if( enabled ){ disableZoom(); } }
        else if( !enabled ){ restoreZoom(); } }
    w.addEventListener( "orientationchange", restoreZoom, false );
    w.addEventListener( "devicemotion", checkTilt, false );
})( this );
