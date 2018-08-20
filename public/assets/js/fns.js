jQuery(document).ready(function () {
    if (jQuery('.matchHeight').length)
        jQuery('.matchHeight').matchHeight({property: 'min-height'});
    jQuery('.selectpicker').selectpicker();

    var toggles = document.querySelectorAll(".c-hamburger");
    for (var i = toggles.length - 1; i >= 0; i--) {
        var toggle = toggles[i];
        toggleHandler(toggle);
    }

    jQuery('#nav-content').bind('cssClassChanged', function () {
        
    });

    jQuery(document).on('click', '.modal-link', function (e) {
        e.preventDefault();
        jQuery('#modal_popup').modal('show').find('.modal-content').load(jQuery(this).attr('href'));
        if(jQuery('.modal_popup' + jQuery(this).attr('data-target')).find('.modal-content').text() == '') jQuery('.modal_popup' + jQuery(this).attr('data-target')).modal('show').find('.modal-content').load(jQuery(this).attr('href'));
    });

    function toggleHandler(toggle) {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
        });
    }
});

jQuery.showLoader = function showLoader() {
    var html = '<div class="page-overlay" style="display:none;"><div class="page-background"></div><div class="loader"></div></div>';
    if (!jQuery('.page-overlay').length)
        jQuery('body').append(html);

    jQuery('.page-overlay').show();
}

jQuery.hideLoader = function hideLoader() {
    jQuery('.page-overlay').hide();
}