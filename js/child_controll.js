/*
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
*/



(function ($) {
    "use strict";
		// to clear a11y issues
        jQuery("#wsu-global-links > UL > .copyright-link > A").attr("title", "Copyright information");
        jQuery("#wsu-global-links > UL > .copyright-link > A").append("<span class='sr-only'>Copyright information</sapn>");


		jQuery.ui.spine.prototype.start_search = function( request, callback ) {
			var self, term, queries = [];
			self = this;//Hold to preserve scop

			term = $.trim( request.term );
			self.search_options.data = [];
			$.each( self.search_options.providers, function( i, provider ) {
				$.ui.autocomplete.prototype.options.termTemplate = ( typeof( provider.termTemplate ) !== undefined && provider.termTemplate !== "" ) ? provider.termTemplate : undefined;
				queries.push( self.run_query( term, provider ) );
			} );

			$.when.apply( $, queries ).done(
			function() {
				$.each( arguments, function( i, v ) {
					var data, proData;
					if ( v !== undefined ) {
						data = v[ 0 ];
						if( data.length > 0 && "undefined" !== typeof data[ "0" ].error){
							data = [ ];
						}
						if ( data !== undefined && data.length > 0 ) {
							proData = self.setup_result_obj( term, data );
							self.search_options.data = $.merge( self.search_options.data, proData );
						}
					}
				} );
				self._call( callback, self.search_options.data );
			} );
		};

	jQuery.ui.spine.prototype.search_options={
		data:[],
		providers:{
			wp:{
				name:"SiteInternal",
				url: "/wp-json/wsuwp_search/v1/byterm/search/?_jsonp=?",
				urlSpaces:"+",
				dataType: "json",
				featureClass: "s",
				style: "full",
				maxRows: 12,
				termTemplate:"<strong><%this.term%></strong>",
				resultTemplate:""
			},
            nav:{
				name:"From Navigation",
				nodes: ".spine-navigation",
				dataType: "html",
				maxRows: 12,
				urlSpaces:"%20"
			},
			atoz:{
				name:"WSU A to Z index",
				url: "https://search.wsu.edu/2013service/searchservice/search.asmx/AZSearch",
				urlSpaces:"+",
				dataType: "jsonp",
				featureClass: "P",
				style: "full",
				maxRows: 12,
				termTemplate:"<strong><%this.term%></strong>",
				resultTemplate:""
			},/**/

		},
		search:{
			minLength: 2,
			maxRows: 12,
			getRelated:true,
			urlSpaces:"+",
			tabTemplate: "<section id='wsu-search' class='spine-search spine-action closed'>" +
							"<form id='default-search'>" +
								"<input name='term' type='text' value='' placeholder='search'>" +
								"<button type='submit'>Submit</button>" +
							"</form>" +
							"<div id='spine-shortcuts' class='spine-shortcuts'></div>" +
						"</section>"
		},
		result:{
			appendTo: "#spine-shortcuts",
			showRelated:true,
			target:"_blank",
			relatedHeader:"",//"<b class='related_sep'>Related</b><hr/>",
			providerHeader:"<b class='provider_header'><%this.provider_name%></b><hr/>",
			termTemplate:"<b><%this.term%></b>",
			template:"<li><%this.searchitem%></li>"
		}
	};


    var init, setupExamples, setupHeroSelect, navSetup, setupDrops, _Drop;
    /*_Drop = Drop.createContext({
        classPrefix: "drop"
    });*/

/*


var url = readCookie('last_location');

console.log("url");
console.log(url);
console.log("pathname");
console.log(window.location.pathname);

if( "/feedback-form/" === window.location.pathname ){
	var _url = readCookie('last_location');
	jQuery(".gform_confirmation_wrapper").append("<a href='" + _url + "' class='button' id='feedback_back_btn'>Go back</a>");
}else{
    console.log("current_location create");
	createCookie("current_location",window.location,500);
	jQuery(window).bind('beforeunload', function(){
        console.log("last_location create");
		createCookie("last_location",window.location,500);
	});
}

function set_up_form_ui(){*/
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


	/*
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
}*/

	$(function(){


		$('html').attr('data-useragent',navigator.userAgent);

		$(".folding>*:nth-child(odd)").on("click",function(){
			if( $(this).closest(".folding").is(".one-at-time") ){
				$(".unfolded").removeClass("unfolded");
				$(this).toggleClass("unfolded");
			}else{
				$(this).toggleClass("unfolded");
			}

		});

		var icons = {
          header: "ui-icon-circle-arrow-e",
          activeHeader: "ui-icon-circle-arrow-s"
        };

        $( ".accordion" ).each(function(idx, val){
            $(this).accordion({
      			collapsible: true,
                icons: icons,
                heightStyle: "content",
                header: "h3",
                active: false
    		});
        });

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
        if( $(".WSU_MAPS_NS").length ){
            setTimeout(function(){ $.wsu_maps.state.map_inst.setOptions({'zoom':15}); }, 1000);
            $(window).resize(function(){
                setTimeout(function(){ $.wsu_maps.state.map_inst.setOptions({'zoom':15}); }, 1000);
            });
        }


/*
		if( !$("#contact-form").length ){
			$("#hidden").append("<div id='contact-form'>");
           $.ajax({
                url:"https://stage.baf.wsu.edu/wp-json/wp/v2/pages?slug=feedback-form&_jsonp=feedback",
                dataType:"jsonp",
                jsonpCallback:"feedback"
            }).done(function(data){
				var html = $("<div>");
				html.append(data[0].content.rendered);
				$("#contact-form").prepend("<span id='close_form'>");
                $("#contact-form").append(html.find(".gform_wrapper").html());
				$("#contact-form").find(".ginput_container_fileupload").css("display","none");
				$("#contact-form").find(".ginput_container_fileupload").prev("label").css("display","none");
				$("#contact-form").find(".ginput_container_fileupload").after("<button id='take_shot'>Take Screen Shot</button><span id='screen_area'><span id='remove_screen'></span><span id='screen_image'></span></span>");
				$("#contact-form").addClass("pad-airy");
				$("#contact-form form").attr("action","https://stage.baf.wsu.edu/feedback-form/");
				set_up_form_ui();


            });
        };
*/


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

/*
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




		$('body,html').scroll();*/
	});



}(jQuery));
