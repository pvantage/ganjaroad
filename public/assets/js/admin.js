$.showLoader = function showLoader() {
    var html = '<div class="page-overlay" style="display:none;"><div class="page-background"></div><div class="loader"></div></div>';
    if (!$('.page-overlay').length)
        $('body').append(html);

    $('.page-overlay').show();
}

$.hideLoader = function hideLoader() {
    $('.page-overlay').hide();
}