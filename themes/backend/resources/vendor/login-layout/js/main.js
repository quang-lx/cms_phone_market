$(document).ready(function() {
	function StickyMenu() {
        var mainmenu = $('header');
        var scrolltop = $('.gotop');
        $(window).scroll(function() {
            var st = $(this).scrollTop();
            if ( st < 350 ){
                mainmenu.removeClass('fixed');
                scrolltop.removeClass('active');
            } else {
                if(st > 350){
                    mainmenu.addClass('fixed');
                    scrolltop.addClass('active');
                }
            }
        });
    }
    function slider(){
        $('#slider-banner').bsTouchSlider();
    }
    function topFunction() {
        $('.gotop').click(function () {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        })

    }
    window.onload = function() {
        //Animate
        new WOW().init();
        StickyMenu();
        slider();
        topFunction();
	};
   
    
});