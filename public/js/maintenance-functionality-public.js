(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */

	
})( jQuery );

jQuery(document).ready(function($) {
	if ( jQuery('#main-template-data').length ) {
		var content_data = jQuery.parseJSON( jQuery('#main-template-data').html() );
		// Add text
		jQuery.each(content_data.texts, function(index, value) {
			jQuery('[data-content='+index+']').html( value );
		});
		// Add style
		jQuery.each(content_data.styles, function(index, value) {
			jQuery('[data-content='+index+']').attr( 'style', value );
		});
	}

	// jQuery('.main-maintenance-login #login_button').on('click', function(e) {
	// 	jQuery('.main-maintenance-login #login p.status').show().text(ajax_login_object.loadingmessage);
	// 	jQuery.ajax({
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		url: ajax_login_object.ajaxurl,
	// 		data: { 
	// 			'action': 'ajaxlogin',
	// 			'username': jQuery('.main-maintenance-login #login #username').val(), 
	// 			'password': jQuery('.main-maintenance-login #login #password').val(), 
	// 			'security': jQuery('.main-maintenance-login #login #security').val()
	// 		},
	// 		success: function(data) {
	// 			jQuery('.main-maintenance-login #login p.status').text(data.message);
	// 			if (data.loggedin == true) {
	// 				document.location.href = ajax_login_object.redirecturl;
	// 			}
	// 		}
	// 	});
	// 	e.preventDefault();
	// });

	if ( jQuery('.main-maintenance-login').length ) {
		jQuery('#user_login').attr('placeholder', jQuery('#user_login').parent().find('label').html() );
		jQuery('#user_pass').attr('placeholder', jQuery('#user_pass').parent().find('label').html() );
	}

	jQuery(document).on('click', '.main-maintenance-login-open', function() {
		jQuery(this).parent().toggleClass('opened');
	});

	jQuery(document).on('click', '.contact-us-open', function() {
		jQuery(this).parent().toggleClass('opened');
	});

	jQuery(document).on('click', '.maintenance-form button', function(e) {
		e.preventDefault();
		var invalid = 0;
		jQuery('.maintenance-form .maintenance-form-control').removeClass('invalid');

		if ( jQuery('.maintenance-form input[name="your-name"]').val() == '' ) {
			jQuery('.maintenance-form input[name="your-name"]').addClass('invalid');
			invalid++;
		}
		if ( jQuery('.maintenance-form input[name="your-email"]').val() == '' ) {
			jQuery('.maintenance-form input[name="your-email"]').addClass('invalid');
			invalid++;
		}
		if ( jQuery('.maintenance-form textarea').val() == '' ) {
			jQuery('.maintenance-form textarea').addClass('invalid');
			invalid++;
		}

		if ( jQuery('.maintenance-wrapper').hasClass('recaptcha') && invalid == 0 ) {
			jQuery.ajax({
				type: 'POST',
				url: mm_main.ajaxurl,
				data: { 
					'action': 'mm_check_recaptcha',
					'recaptcha_response': jQuery('#mm-contact-form-recaptcha #g-recaptcha-response').val(), 
				},
				success: function(data) {
					if ( data == "true" ) {
						jQuery("#mm-contact-form-recaptcha").removeClass("invalid");
						jQuery('.maintenance-form').submit();
					} else {
						jQuery("#mm-contact-form-recaptcha").addClass("invalid");
					}
				}
			});
		} else {
			if ( invalid == 0 ) { jQuery('.maintenance-form').submit(); }
		}
	});

	if ( jQuery('#multiscroll').length ) {
		jQuery('#multiscroll').show();
		jQuery('#multiscroll').multiscroll({
			navigation: true
		});
		$('#multiscroll > div > div').css('background-size', $(window).width()+'px');
	}

	if ( $('body').hasClass('contact-opened') ) {
		if ( $('.maintenance-wrapper').hasClass('template-style5') || $('.maintenance-wrapper').hasClass('template-style4') ) {
			$.fn.multiscroll.moveSectionDown();
		} else {
			$('.main-maintenance-contact-us').addClass('opened');
			$('html,body,.maintenance-wrapper').animate({
				scrollTop: $('.contact-us-notice').offset().top
			});
		}
		
	}

});

jQuery(window).load(function() {

});