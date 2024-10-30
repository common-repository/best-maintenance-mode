(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-specific JavaScript source
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

	// Redirect user to pro version website
	jQuery(document).on('click', '.form-group.main-grayed', function() {
		window.location.href = "https://cohhe.com/project-view/maintenance-mode-pro/";
	});

	jQuery(document).on('click', '.main-maintenance-checkbox:not(.disabled)', function() {
		jQuery(this).toggleClass('active')
		if ( jQuery(this).find('input').is(':checked') ) {
			jQuery(this).find('input').prop('checked', false);
		} else {
			jQuery(this).find('input').prop('checked', true);
		}
	});

	jQuery('.main-maintenance-checkbox input').each(function() {
		if ( jQuery(this).is(':checked') ) {
			jQuery(this).parent().addClass('active');
		}
	});

	jQuery(document).on('click', '#main-page-headline, #main-page-description', function() {
		jQuery('.text-styling-row').show();
		jQuery('#main-edited-text').val( '#' + jQuery(this).attr('id') );
		jQuery('#main-page-headline, #main-page-description').removeClass('active');
		jQuery(this).addClass('active');

		jQuery('#main-font-size').val( jQuery(this).css('font-size').replace('px', '') );
		jQuery('#main-line-height').val( jQuery(this).css('line-height').replace('px', '') );
		jQuery('#main-font-weight').val( jQuery(this).css('font-weight') );
		jQuery('.wp-colorpicker').wpColorPicker('color', jQuery(this).css('color'));
	});

	jQuery('.wp-colorpicker').wpColorPicker({
		change: function(event, ui) {
			jQuery( jQuery('#main-edited-text').val() ).css( 'color', ui.color.toString() );
		},
		clear: function() {
			jQuery( jQuery('#main-edited-text').val() ).css( 'color', '#fff' );
		}
	});

	jQuery('.main-number-field span').on('click', function() {
		if ( jQuery(this).hasClass('main-number-minus') ) {
			jQuery(this).parent().find('input').val( parseInt( jQuery(this).parent().find('input').val() ) - 1 );
		} else {
			jQuery(this).parent().find('input').val( parseInt( jQuery(this).parent().find('input').val() ) + 1 );
		}
		jQuery(this).parent().find('input').trigger('input');
	});

	jQuery('.main-number-field input').on('input', function() {
		jQuery( jQuery('#main-edited-text').val() ).css( jQuery(this).attr('id').replace('main-', ''), jQuery(this).val() + 'px' );
	});

	jQuery('#main-font-weight').on('change', function() {
		jQuery( jQuery('#main-edited-text').val() ).css( jQuery(this).attr('id').replace('main-', ''), jQuery(this).val() );
	});

	jQuery('.input-wrapper.file-upload a.choose-image').on('click', function() {
		var $wpimageupload = jQuery(this).parent().find('input');
		var $wpimageparent = jQuery(this).parent();
		var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $wpimageupload.val(image_url).trigger('input');
            $wpimageparent.addClass('remove-active');
        });
	});

	jQuery(document).on('click', '.file-upload .delete-image', function() {
		jQuery(this).parent().find('input').val('');
		jQuery(this).parent().removeClass('remove-active');
	});

	jQuery(document).on('click', '.save-main-maintenance', function() {
		var main_settings = '{';
		// Text inputs, selects
		jQuery('.main-maintenance-wrapper input[type=text]:not(.profeature), .main-maintenance-wrapper select:not(.text-styling):not(.profeature)').each(function() {
			var input_value = jQuery(this).val();
			if ( typeof jQuery(this).val() == 'string' ) {
				input_value = jQuery(this).val().replace(/"/g, "'");
			}
			main_settings += '"'+jQuery(this).attr('id').replace('main-', '')+'":"'+input_value+'",';
		});
		// Checkboxes
		jQuery('.main-maintenance-wrapper input[type=checkbox]:not(.profeature)').each(function() {
			if ( jQuery(this).is(':checked') ) {
				var checkbox_val = 'true';
			} else {
				var checkbox_val = 'false';
			}
			main_settings += '"'+jQuery(this).attr('id').replace('main-', '')+'":"'+checkbox_val+'",';
		});
		jQuery('#main-page-headline, #main-page-description').each(function() {
			main_settings += '"'+jQuery(this).attr('id').replace('main-', '')+'":"'+jQuery(this).html()+'",';
			main_settings += '"'+jQuery(this).attr('id').replace('main-', '')+'-style":"'+jQuery(this).attr('style')+'",';
		});
		if ( jQuery('#main-background-video').length ) {
			main_settings += '"'+jQuery('#main-background-video').attr('id').replace('main-', '')+'":"'+jQuery('#main-background-video').val()+'",';
		}
		if ( jQuery('#main-countdown').length ) {
			main_settings += '"'+jQuery('#main-countdown').attr('id').replace('main-', '')+'":"'+jQuery('#main-countdown').val()+'",';
		}
		// Analytics
		main_settings += '"google-analytics-code":"'+jQuery('#main-google-analytics-code').val()+'",';
		// Exclude
		main_settings += '"exclude":"'+jQuery('#main-exclude').val().replace(/\n/g, "|")+'",';
		// MailChimp
		if ( jQuery('#main-mailchimp').length ) {
			var old_code = jQuery('#main-mailchimp').val();
			var old_html = jQuery('<div>'+old_code+'</div>');
			old_html.find('link[type="text/css"]').remove();
			if ( old_html.find('#mce-responses').length ) {
				var response = old_html.find('#mce-responses')["0"].outerHTML;
				old_html.find('#mce-responses').remove();
				old_html.find("#mc_embed_signup_scroll .clear").after(response);
				// console.log(old_html.html());
			}
			old_html = old_html.html();
			main_settings += '"mailchimp":'+JSON.stringify(old_html.replace(/"/g, "'")).replace(/\\n/g, '').replace(/\\t/g, '')+',';
		}
		// Access by ip
		if ( jQuery('#main-access-by-ip').length ) {
			main_settings += '"access-by-ip":"'+jQuery('#main-access-by-ip').val().replace(/\n/g, "|")+'",';
		}
		// Template
		if ( jQuery('#maintenance-template').length ) {
			main_settings += '"template":"'+jQuery('.main-fake-select').attr('data-selected')+'",';
		}
		// Countdown translate
		if ( jQuery('#main-countdown-hours').length ) {
			main_settings += '"countdown-hours":"'+jQuery('#main-countdown-hours').val()+'",';
			main_settings += '"countdown-minutes":"'+jQuery('#main-countdown-minutes').val()+'",';
			main_settings += '"countdown-seconds":"'+jQuery('#main-countdown-seconds').val()+'",';
			main_settings += '"countdown-days":"'+jQuery('#main-countdown-days').val()+'",';
		}
		// reCAPTCHA
		if ( jQuery('#main-recaptcha-key').length ) {
			main_settings += '"recaptcha-key":"'+jQuery('#main-recaptcha-key').val()+'",';
			main_settings += '"recaptcha-secret":"'+jQuery('#main-recaptcha-secret').val()+'",';
		}
		if ( jQuery('#main-after-countdown').length ) {
			main_settings += '"main-after-countdown":"'+jQuery('#main-after-countdown').val()+'",';
		}
		// CSS
		if ( jQuery('#main-maintenance-css').length ) {
			main_settings += '"maintenance-css":"'+jQuery('#main-maintenance-css').val().replace(/"/g, "'")+'",';

		}
		main_settings = main_settings.slice(0, -1);
		main_settings += '}';
		jQuery.ajax({
			type: 'POST',
			url: maintenance_main.ajaxurl,
			data: { 
				'action': 'main_save_maintenance_settings',
				'main_settings': main_settings
			},
			success: function(data) {
				location.reload();
			}
		});
	})

	jQuery(document).on('click', '.main-maintenance-dismiss', function() {
		jQuery.ajax({
			type: 'POST',
			url: maintenance_main.ajaxurl,
			data: { 
				'action': 'main_dismiss_notice'
			},
			success: function(data) {
				jQuery('.main-maintenance-notice').remove();
			}
		});
	});

	jQuery(document).on('click', '.main-fake-select', function() {
		jQuery(this).find('li').each(function() {
			if ( jQuery(this).hasClass('selected') ) {
				jQuery(this).trigger('mouseenter');
			}
		});
		jQuery(this).toggleClass('opened');
		jQuery(this).find('ul').width( jQuery(this).width() + 25 );
		if ( jQuery(this).hasClass('opened') ) {
			jQuery(this).parent().find('#maintenance-template').addClass('opened');
		} else {
			jQuery(this).parent().find('#maintenance-template').removeClass('opened');
		}
		
	});

	var template_defaults = {
		'default': {
			'headline': 'color: #fff;font-size: 55px;line-height: 66px;font-weight: bold;',
			'description': 'color: #fff;font-size: 18px;line-height: 32px;font-weight: 300;'
		},
		'style2': {
			'headline': 'color: #fff;font-size: 55px;line-height: 66px;font-weight: bold;',
			'description': 'color: #fff;font-size: 18px;line-height: 20px;font-weight: normal;'
		},
		'style3': {
			'headline': 'color: #fff;font-size: 32px;line-height: 34px;font-weight: bold;',
			'description': 'color: #fff;font-size: 18px;line-height: 20px;font-weight: normal;'
		},
		'style4': {
			'headline': 'color: #fff;font-size: 64px;line-height: 64px;font-weight: normal;',
			'description': 'color: #fff;font-size: 16px;line-height: 26px;font-weight: 400;'
		},
		'style5': {
			'headline': 'color: #fff;font-size: 55px;line-height: 66px;font-weight: bold;',
			'description': 'color: #fff;font-size: 18px;line-height: 26px;font-weight: 300;'
		},
		'style6': {
			'headline': 'color: #fff;font-size: 64px;line-height: 64px;font-weight: bold;',
			'description': 'color: #fff;font-size: 16px;line-height: 26px;font-weight: normal;'
		},
		'style7': {
			'headline': 'color: #fff;font-size: 35px;line-height: 38px;font-weight: 300;',
			'description': 'color: #fff;font-size: 16px;line-height: 20px;font-weight: normal;'
		},
		'style8': {
			'headline': 'color: #fff;font-size: 38px;line-height: 160px;font-weight: 300;',
			'description': 'color: #fff;'
		},
		'style9': {
			'headline': 'color: #fff;font-size: 40px;line-height: 66px;font-weight: bold;',
			'description': 'color: #fff;font-size: 18px;line-height: 22px;font-weight: normal;'
		},
		'style10': {
			'headline': 'color: #fff;font-size: 100px;line-height: 100px;font-weight: 400;',
			'description': 'color: #fff;font-size: 18px;line-height: 24px;font-weight: 300;'
		},
		'style11': {
			'headline': 'color: #fff;font-size: 36px;line-height: 38px;font-weight: 300;',
			'description': 'color: #fff;font-size: 14px;line-height: 25px;font-weight: 400;'
		}
	}

	jQuery(document).on('click', '#main-reset-defaults', function() {
		if ( confirm('Are you sure you want to reset to default text styles?') ) {
			var template_styles = jQuery.makeArray( template_defaults );
			var current_template = jQuery('.main-fake-select').attr('data-selected');
			jQuery('#main-page-headline').attr('style', template_styles['0'][current_template]['headline']).addClass('allow-change');
			jQuery('#main-page-description').attr('style', template_styles['0'][current_template]['description']).addClass('allow-change');
			jQuery('#main-reset-defaults').hide();
		}
	});


	if ( ( jQuery('#main-page-headline').length && jQuery('#main-page-headline').attr('style') != '' ) || ( jQuery('#main-page-description').length && jQuery('#main-page-description').attr('style') != '' ) ) {
		var template_styles = jQuery.makeArray( template_defaults );
		var current_template = jQuery('.main-fake-select').attr('data-selected');

		if ( typeof template_styles['0'][current_template] != 'undefined' ) {
			if ( jQuery('#main-page-headline').attr('style') == template_styles['0'][current_template]['headline'] ) {
				jQuery('#main-page-headline').addClass('allow-change');
			}
			if ( jQuery('#main-page-description').attr('style') == template_styles['0'][current_template]['description'] ) {
				jQuery('#main-page-description').addClass('allow-change');
			}
			if ( jQuery('#main-page-headline').attr('style') == template_styles['0'][current_template]['headline'] && jQuery('#main-page-description').attr('style') == template_styles['0'][current_template]['description'] ) {
				jQuery('#main-reset-defaults').hide();
			}
		}
	}

	jQuery(document).on('click', '.main-fake-select li:not(.cant-select)', function() {
		var selected_element = jQuery(this);
		setTimeout(function() {
			selected_element.parent().parent().attr('data-selected', selected_element.attr('data-value'));
			selected_element.parent().parent().removeClass('opened');
			selected_element.parent().find('li').removeClass('selected');
			selected_element.addClass('selected');
			selected_element.parent().parent().parent().find('#maintenance-template').removeClass('opened');
			jQuery('#main-background-image').attr('value', maintenance_main.img_folder + selected_element.attr('data-value') + '-bg.jpg' );
			if ( selected_element.attr('data-value') == 'style5' || selected_element.attr('data-value') == 'style4' ) {
				jQuery('.form-group.second-bg').show();
			} else {
				jQuery('.form-group.second-bg').hide();
			}
			var template_styles = jQuery.makeArray( template_defaults );
			if ( jQuery('#main-page-headline').attr('style') == '' || jQuery('#main-page-headline').hasClass('allow-change') ) {
				jQuery('#main-page-headline').addClass('allow-change');
				jQuery('#main-page-headline').attr('style', template_styles['0'][selected_element.attr('data-value')]['headline']);
			}
			if ( jQuery('#main-page-description').attr('style') == '' || jQuery('#main-page-description').hasClass('allow-change') ) {
				jQuery('#main-page-description').addClass('allow-change');
				jQuery('#main-page-description').attr('style', template_styles['0'][selected_element.attr('data-value')]['description']);
			}
		}, 100);
	});

	jQuery(document).on('mouseenter', '.main-fake-select li', function() {
		jQuery(this).parent().parent().parent().find('#maintenance-template').addClass('opened');
		jQuery(this).parent().parent().parent().find('#maintenance-template').css('background', 'url(' + jQuery(this).attr('data-image') + ') no-repeat #fff');
	});

	if ( jQuery('.main-fake-select').attr('data-selected') == 'style5' || jQuery('.main-fake-select').attr('data-selected') == 'style4' ) {
		jQuery('.form-group.second-bg').show();
	}

});