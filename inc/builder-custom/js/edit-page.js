/*!
 * Script for adding functionality to the Edit Page screen.
 *
 * @since 1.0.0
 */
/* global jQuery, ttfmakeEditPageData */
(function($){
	$.fn.stripClass = function (partialMatch, endOrBegin) {
		console.log("stripClass----------->>>>>>>>>>>");
		var regx;
		regx = new RegExp((!endOrBegin ? "\\b" : "\\S+") + partialMatch + "\\S*", "g");
		//console.log(regx);
		this.attr("class", function (i, classlist) {
			//console.log("classlist-----------");
			//console.log(classlist);
			if (!classlist) {
				return;
			}
			var output = classlist.replace(regx, "");
			//console.log(output);console.log("^^^^^^^^^-----output------");
			return output;
		});
		return this;
	};

	var ttfmakeEditPage = {
		cache: {
			$document: $(document)
		},

		init: function() {
			this.cacheElements();
			this.bindEvents();
		},


		cleanTarget: function( target, callback){
			var clearOf = ["grid-", "justify-", "flex-", "order-", "full-width-",
						   "thirds-", "fours-", "fifths-", "sixths-", "eigths-",
						   "ninths-", "tenths-", "twelfths-", "wrap", "space-",
						   "center", "column-", "row-", "hide-below-", "full-width",
						   "pad-", "round-", "items-", "content-"];

			$.each(clearOf,function(i,val){
				target.stripClass(val,false);
			});
			callback();

			/*$.when.apply($, $.map(clearOf, function(idx,val) {
				target.stripClass(val,false);
			})).done(function() {
				callback();
			});*/
		},

		cacheElements: function() {
			this.cache.$pageTemplate = $('#page_template');
			this.cache.$builderToggle = $('#use-builder');
			this.cache.$mainEditor = $('#postdivrich');
			this.cache.$builder = $('#ttfmake-builder');
			this.cache.$duplicator = $('.ttfmake-duplicator');
			this.cache.$builderHide = $('#ttfmake-builder-hide');
			this.cache.$commentstatus = $('#comment_status');
			this.cache.$pingstatus = $('#ping_status');
			this.cache.$body = $('body');

			this.cache.$start_add_fw_class = $('#start_add_fw_class');
			this.cache.$fw_type = $('#flexwork-type');
			this.cache.$fw_area_type = $('#flexwork-area_type');
			this.cache.$fw_wrapping = $('#flexwork-wrapping');
			this.cache.$fw_content_justification = $('#flexwork-content_justification');
			this.cache.$fw_content_alignment = $('#flexwork-content_alignment');
			this.cache.$fw_items_positioning = $('#flexwork-items_positioning');
			this.cache.$fw_pad = $('#flexwork-pad');
			this.cache.$fw_pad_type = $('#flexwork-pad-type');
			this.cache.$fw_pad_position = $('#flexwork-pad-position');
			this.cache.$fw_round = $('#flexwork-round');
			this.cache.$fw_round_type = $('#flexwork-round-type');
			this.cache.$fw_round_position = $('#flexwork-round-position');
			this.cache.$fw_class_at = $('#fw_class_at');
			this.cache.$fw_at_sizes = $('#flexwork-at-sizes');
		},

		bindEvents: function() {
			var self = this;
				$(".ttfmake-section-faiswsuwpsidebarright").each(function(idn,val){
					//$(this).find(".wsuwp-spine-halves-stage").addClass("flex-row");
					$(this).find(".wsuwp-spine-builder-column-position-1").addClass("grid-part fifths-3");
					$(this).find(".wsuwp-spine-builder-column-position-2").addClass("grid-part fifths-2");

				});

				$(".ttfmake-section-faiswsuwpsidebarleft").each(function(idn,val){
					//$(this).find(".wsuwp-spine-halves-stage").addClass("flex-row");
					$(this).find(".wsuwp-spine-builder-column-position-1").addClass("grid-part fifths-2");
					$(this).find(".wsuwp-spine-builder-column-position-2").addClass("grid-part fifths-3");
				});


				$(".ttfmake-section").each(function(){
					var _selection = $(this);

					_selection.find(".fw-builder").hide();
					_selection.find(".fb-type").closest(".flex-attr-area").hide();
					_selection.find("#fw_add_class").hide();

					_selection.find('#start_add_fw_class').show();
					_selection.find('#start_add_fw_class').on("click", function(e){
						e.preventDefault();
						_selection.find(".fb-type").find(":selected").attr("selected",false).removeAttr("selected");
						_selection.find(".fb-type").closest(".flex-attr-area").hide();
						_selection.find('#flexwork-type').show();
						_selection.find(".fw-builder").show();
					});

					_selection.find("select.flex-builder-selector").on("change", function(){
						var val = $(this).val();
						if( $(this).is(".fb-type-chooser") ){
							_selection.find(".fb-type").closest(".flex-attr-area").hide();
						}
						_selection.find("#flexwork-"+val).show();

						_selection.find("#fw_class_at").show();
						_selection.find("#fw_add_class").show();
					});
					_selection.find("#fw_class_at").on("click", function(e){
						e.preventDefault();
						_selection.find("#flexwork-at-sizes").show();
					});


					_selection.find("#fw_add_class").on("click", function(e){
						e.preventDefault();
						var tar = $(this);
						var _class = " ";
						var type_val = _selection.find(".fb-type-chooser").val();
						if( "pad" === type_val || "round" === type_val ){
							_class += type_val;
						}

						_selection.find(".fw-builder select.fb-type:visible").each(function(){
							var val = $(this).val();
							if( "_" !== val && "" !== val ){
								_class += (" " !== _class ? "-":" ") + val;
							}
						});
						_selection.find("#fexwork-classes").val( _selection.find("#fexwork-classes").val() + _class );
						self.flexSectionChange( tar.closest(".ttfmake-section").find(".wsuwp-spine-halves-stage"), function(){
							tar.closest(".ttfmake-section").find(".wsuwp-spine-halves-stage").addClass( _selection.find("#fexwork-classes").val() );
						});
						_selection.find(".fb-type-chooser").find(":selected").attr("selected",false).removeAttr("selected");
						_selection.find(".fb-type").find(":selected").attr("selected",false).removeAttr("selected");
						_selection.find(".fb-type").closest(".flex-attr-area").hide();
						_selection.find("#fw_add_class").hide();
						_selection.find("#fw_class_at").hide();
						_selection.find(".fw-builder").hide();

					});

					_selection.find("#fexwork-classes").on("change", function(){
						var tar = $(this);
						self.flexSectionChange( tar.closest(".ttfmake-section").find(".wsuwp-spine-halves-stage"), function(){
							tar.closest(".ttfmake-section").find(".wsuwp-spine-halves-stage").addClass( _selection.find("#fexwork-classes").val() );
						});
					});

				});





			// Setup the event for toggling the Page Builder when the page template input changes
			self.cache.$pageTemplate.on('change', self.templateToggle);
			self.cache.$builderToggle.on('click', self.templateToggle);

			// Change default settings for new pages
			if ( typeof ttfmakeEditPageData !== 'undefined' && 'post-new.php' === ttfmakeEditPageData.pageNow && 'page' === pagenow ) {
				// Builder template is selected by default
				self.cache.$pageTemplate.val('template-builder.php');

				// Comments and pings turned off by default
				self.cache.$commentstatus.prop('checked', '');
				self.cache.$pingstatus.prop('checked', '');
			}

			// Make sure screen is correctly toggled on load
			self.cache.$document.on('ready', function() {
				self.cache.$pageTemplate.trigger('change');
			});
		},

		flexSectionChange: function( jObj, callback ){
			this.cleanTarget(jObj, function(){
				if( $.isFunction(callback) ){
					callback();
				}
			});
		},

		templateToggle: function(e) {
			var self = ttfmakeEditPage,
				$target = $(e.target),
				val = $target.val();

			if ('template-builder.php' === val || $target.is(':checked')) {
				self.cache.$mainEditor.hide();



				self.cache.$builder.show();
				self.cache.$duplicator.show();
				self.cache.$builderHide.prop('checked', true).parent().show();
				self.cache.$body.addClass('ttfmake-builder-active').removeClass('ttfmake-default-active');
			} else {
				self.cache.$mainEditor.show();

				self.cache.$builder.hide();
				self.cache.$duplicator.hide();
				self.cache.$builderHide.prop('checked', false).parent().hide();
				self.cache.$body.removeClass('ttfmake-builder-active').addClass('ttfmake-default-active');
			}
		}
	};

	ttfmakeEditPage.init();
})(jQuery);
