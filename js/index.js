$(window).scroll(function() {
    if ($(this).scrollTop() > 500 )
        $('.back-to-top-holder:hidden').stop(true, true).fadeIn();
    else
        $('.back-to-top-holder').stop(true, true).fadeOut();
});
$(function(){
    $(".back-to-top-button").click(function(){
        $("html,body").animate({
            scrollTop:$("#ucfhb").offset().top
        },"1000");
        return false
    });
});