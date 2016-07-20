(function($){
	$(function(){
	if ($.browser.msie && $.browser.version == 9) {
		$('html').addClass('ie9');
	}

	if( $('.page.type-page #content_area').length <= 0 ){
		$('.page.type-page').addClass("content_less");
	}


	});



}(jQuery));
