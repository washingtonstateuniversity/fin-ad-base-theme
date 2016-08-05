var dev = false;
    if( -1 !== window.location.host.indexOf("wp.wsu.dev") ){
        dev = true;
    }
function createCookie(name,value,days) {
    console.log("createCookie");
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = " expires="+date.toGMTString() + ";";
    }
    else var expires = "";
    document.cookie = name+"="+value+";"+expires+" path=/;domain="+(dev?".wsu.dev":".wsu.edu");
}

function readCookie(name) {
    console.log("readCookie");
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
console.log("custom");
function eraseCookie(name) {
    createCookie(name,"",-1);
}

(function($){
    var init, setupExamples, setupHeroSelect, navSetup, setupDrops, _Drop;
    _Drop = Drop.createContext({
        classPrefix: "drop"
    });



var url = readCookie('last_location');

console.log("url");
console.log(url);
console.log("pathname");
console.log(window.location.pathname);

if( "/feedback-form/" === window.location.pathname ){
	var url = readCookie('last_location read');
	jQuery(".gform_confirmation_wrapper").append("<a href='"+url+"'>Go back</a>");
}else{
    console.log("current_location create");
	createCookie("current_location",window.location,500);
	jQuery(window).bind('beforeunload', function(){
        console.log("last_location create");
		createCookie("last_location",window.location,500);
	});
}

function set_up_form_ui(){
//d7085d8a0a
//document.cookie = 'mycookie=valueOfCookie;expires=DateHere;path=/'

//wpdev/gravityformsapi/'

	/*$("#contact-form form").off().on("submit",function(e) {
	//$("#contact-form [type='submit']").off().on("click", function(e){
		//e.preventDefault();
		//e.stopPropagation();
		//apiVars = gf_web_api_demo_1_strings;
		var inputValues = {};
		$("#contact-form form :input").each(function(idx,input){
			var name = ""+$(input).attr("name");
			inputValues[name]=$(input).val();
		});

		var data = {
			input_values: inputValues
		};
console.log(data);
		var form_id = $("#contact-form form").attr("id").split("_")[1];
		$.ajax({
			type: "POST",
			url: 'https://stage.baf.wsu.edu/gravityformsapi/forms/' + form_id +  '/submissions',
			//'https://stage.baf.wsu.edu/forms/' + form_id +  '/submissions',
			data: data,
			contentType:"multipart/form-data",
		}).done(function(data){
			console.log(data);
			var html = $("<div>");
			html.html(page_object.content.rendered);
			if(html.find(".validation_error").length>0){
				$("#contact-form").find(".gf_browser_chrome gform_wrapper").remove();
				html = html.find(".gf_browser_chrome gform_wrapper").html();
				$("#contact-form").append(html);
				set_up_form_ui();
			}else{
				$("#contact-form").find(".gf_browser_chrome gform_wrapper").remove();
				html = $("<div><h2>Thank you for the feedback</h2></div>");
				$("#contact-form").append(html.html());
			}
		});
	});*/
	$("#take_shot").off().on("click",function(e){
		e.preventDefault();
		e.stopPropagation();
		html2canvas(document.body, {
			onrendered: function(canvas) {
				//$('#img_val').val(canvas.toDataURL("image/png"));
				//$('#screen_image')(canvas);
				document.getElementById("screen_image").appendChild(canvas);
				$("#screen_image canvas").css({"width":100,"height":"auto"});
				$('body,html').animate({
					scrollTop: $(window).scrollTop() + 1
				}, 5);
			}
		});
	});
	$("#close_form").off().on("click", function(e){
		e.preventDefault();
		e.stopPropagation();
		$("#contact-form").animate({
			opacity:0,
			"z-index":0
		}, 50);
	});
}


	$(function(){




		if ($.browser.msie && $.browser.version == 9) {
			$('html').addClass('ie9');
		}
		if ($.browser.msie || (!(window.ActiveXObject) && "ActiveXObject" in window) || /x64|x32/ig.test(window.navigator.userAgent)) {
			$('html').addClass('ie');
		}

		if( $('.page.type-page #content_area').length <= 0 ){
			$('.page.type-page').addClass("content_less");
		}

		if( !$("#hidden").length ){
			$("body").append("<div id='hidden'>");
		}


		if( !$("#contact-form").length ){
			$("#hidden").append("<div id='contact-form'>");
           $.ajax({
                url:"https://stage.baf.wsu.edu/wp-json/wp/v2/pages?slug=feedback-form&_jsonp=feedback",
                dataType:"jsonp",
                jsonpCallback:"feedback"
            }).done(function(data){
				$("#contact-form").prepend("<span id='close_form'>");
                $("#contact-form").append(data[0].content.rendered);
				$("#contact-form").find(".ginput_container_fileupload").css("display","none");
				$("#contact-form").find(".ginput_container_fileupload").prev("label").css("display","none");
				$("#contact-form").find(".ginput_container_fileupload").after("<button id='take_shot'>Take Screen Shot</button><span id='screen_area'><span id='remove_screen'></span><span id='screen_image'></span></span>");
				$("#contact-form").addClass("pad-airy");
				$("#contact-form form").attr("action","https://stage.baf.wsu.edu/feedback-form/");
				set_up_form_ui();


            });
        };



			//$("#hidden").append("<div id='contact-form' class='pad-airy'><input type='hidden' name='img_val' id='img_val' /><span id='close_form'></span><h2>Provide Feed back</h2><form><label>Name<br/><input type='text' /></label><br/><label>Email<br/><input type='email' /></label><br/><label>Feedback<br/></label><textarea style'width:100%'></textarea><br/><button id='take_shot'>Take Screen Shot</button><span id='screen_area'><span id='remove_screen'></span><span id='screen_image'></span></span><br/><br/><button type='submit'>Submit</button>");
		//}

		var spine_color = "";
		var spine_class = $("#spine").attr("class");
		if( spine_class.indexOf("dark ") > -1 ){
			spine_color = "dark";
		}else if( spine_class.indexOf("darker") > -1 ){
			spine_color = "darker";
		}else if( spine_class.indexOf("crimson") > -1 ){
			spine_color = "crimson";
		}
		$("body").addClass(spine_color);
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


		$("#contact-form").css({"opacity":0,"z-index":0});
		$(".inline-form.menu-item").on("click", function(e){
			e.preventDefault();
			e.stopPropagation();
			$("#contact-form").animate({
				opacity:1,
				"z-index":999999
			}, 50, function() {
				$('body,html').scroll();
				$('body,html').animate({ scrollTop: $(window).scrollTop() + 1 }, 5);
			});
		});




		$('body,html').scroll();
	});



}(jQuery));
