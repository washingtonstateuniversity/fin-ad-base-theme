(function($){
    var init, setupExamples, setupHeroSelect, navSetup, setupDrops, _Drop;
    _Drop = Drop.createContext({
        classPrefix: "drop"
    });
	$(function(){
		if ($.browser.msie && $.browser.version == 9) {
			$('html').addClass('ie9');
		}

		if( $('.page.type-page #content_area').length <= 0 ){
			$('.page.type-page').addClass("content_less");
		}

		if( !$("#hidden").length ){
			$("body").append("<div id='hidden'>");
		}
		if( !$("#contact-form").length ){
			$("#hidden").append("<div id='contact-form' class='pad-airy'><input type='hidden' name='img_val' id='img_val' /><span id='close_form'></span><h2>Provide Feed back</h2><form><label>Name<input type='text' /></label><button id='take_shot'>Take Screen Shot</button><span id='screen_area'><span id='remove_screen'></span><span id='screen_image'></span></span><button type='submit'>Submit</button>");
		}



		//http://www.kubilayerdogan.net/html2canvas-take-screenshot-of-web-page-and-save-it-to-server-javascript-and-php/
		// NOTE THIS IS WHERE WE NEED TO REFERBACK


		//break out later
		///set up line form
		(function() {
			return new Tether({
				element: $("#contact-form")[0],
				target: $(".inline-form.menu-item")[0],
				attachment: 'center left',
				targetAttachment: 'top left',
				constraints: [
					{
						to: 'window',
						attachment: 'together',
						pin: true
					}
				]
			});
		}());


		$("#contact-form").hide();
		$(".inline-form.menu-item").on("click", function(e){
			e.preventDefault();
			e.stopPropagation();
			$("#contact-form").toggle( "fast", function() {
				$('body,html').scroll();
				$('body,html').animate({
					scrollTop: $(window).scrollTop() + 1
				}, 5);
			});

		});
		$("#close_form").on("click", function(e){
			e.preventDefault();
			e.stopPropagation();
			$("#contact-form").toggle( "fast", function() {
				$('body,html').scroll();
				$('body,html').animate({
					scrollTop: $(window).scrollTop() + 1
				}, 5);
			});
		});

		$("#take_shot").on("click",function(e){
			e.preventDefault();
			e.stopPropagation();
			html2canvas(document.body, {
				onrendered: function(canvas) {
					//$('#img_val').val(canvas.toDataURL("image/png"));
					//$('#screen_image')(canvas);
					document.getElementById("screen_image").appendChild(canvas);
					$("#screen_image canvas").css({"width":100,"height":"auto"});
				}
			});
		});


		$('body,html').scroll();
	});



}(jQuery));
