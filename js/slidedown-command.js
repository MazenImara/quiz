(function ($, window, Drupal, drupalSettings) {
	'use strict';

	//command to slide down page elements.
	Drupal.AjaxCommands.prototype.slideDown = function (ajax, response, status) {
		// get duration if sent, else use default of slow 
		var druation = response.duration ? response.duration : 'slow';
		// slide down the selected element (s)
		$(response.selector).slideDown(duration);
	}
})(jQuery, this, Drupal, drupalSettings);