$(document).ready(function(){




///HREF
  $("[href]").each(function() {
    if (this.href == window.location.href) {
        $(this).parent().addClass("active");
        }
    });

///END HREF


///MENU SCROLL ONTOP
$(window).scroll(function() {
	var h = $(window).scrollTop();
	var width = $(window).width();
	if(width > 767){
		if(h > 35){
			$('.menu_main').addClass('menu_main_header_ontop');
			}else{
			$('.menu_main').removeClass('menu_main_header_ontop');
			}
	}



});


});(jQuery)