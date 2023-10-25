(function($) {

	// Create wsf-rendered event handler
	$(document).on('wsf-rendered', function(e, form, form_id, instance_id) {

		// Find all custom field wrappers
		var custom_field_wrappers = $('[data-type="my_custom_field"]');

		// Process each custom field wrapper
		custom_field_wrappers.each(function() {

			// Output the custom field wrapper ID to the browser inspector console
			console.log('Found custom field wrapper - ID: ' + $(this).attr('id'));
		});
	});	

})(jQuery);