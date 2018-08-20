jQuery(document).ready(function() {
    jQuery('#myCarousel').carousel({
        interval: 4000
    });

    var clickEvent = false;
    jQuery('#myCarousel').on('click', '.nav a', function() {
        clickEvent = true;
        jQuery('.nav li').removeClass('active');
        jQuery(this).parent().addClass('active');
    }).on('slid.bs.carousel', function(e) {
        if (!clickEvent) {
            var count = jQuery('.nav').children().length - 1;
            var current = jQuery('.nav li.active');
            current.removeClass('active').next().addClass('active');
            var id = parseInt(current.data('slide-to'));
            if (count == id) {
                jQuery('.nav li').first().addClass('active');
            }
        }
        clickEvent = false;
    });
    //Events that reset and restart the timer animation when the slides change
    jQuery("#transition-timer-carousel").on("slide.bs.carousel", function(event) {
        //The animate class gets removed so that it jumps straight back to 0%
        jQuery(".transition-timer-carousel-progress-bar", this)
            .removeClass("animate").css("width", "0%");
    }).on("slid.bs.carousel", function(event) {
        //The slide transition finished, so re-add the animate class so that
        //the timer bar takes time to fill up
        jQuery(".transition-timer-carousel-progress-bar", this)
            .addClass("animate").css("width", "100%");
    });

//Kick off the initial slide animation when the document is ready
    jQuery(".transition-timer-carousel-progress-bar", "#transition-timer-carousel")
        .css("width", "100%");
});
jQuery(document).ready(function() {
    jQuery("#carousel3").owlCarousel({
        navigation : false, // Show next and prev buttons
        paginationSpeed : 400,
        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false,
        autoplay:true
    });
    jQuery("#carousel4").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        items : 3,
        itemsDesktop : [1199,3],
        itemsDesktopSmall : [979,3]

    });
    jQuery("#carousel1").owlCarousel({
        autoPlay : 3000,
        stopOnHover : true,
        navigation:true,
        paginationSpeed : 1000,
        goToFirstSpeed : 2000,
        singleItem : true,
        autoHeight : true,
        transitionStyle:"fade"
    });
});