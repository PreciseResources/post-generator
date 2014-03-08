(function ($) {
	"use strict";
	$(function () {
		// Place your administration-specific JavaScript here
		$('#pg-create').click( function() {
			
			var data = { action: 'pg_create_posts' };
			
			$.post(ajaxurl, data, function(response) { 
				alert( response );
			});
		});
	});
}(jQuery));