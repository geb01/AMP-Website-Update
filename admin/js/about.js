/*
	Ateneo Musician's Pool
	by Rico Tiongson
	
	All rights reserved for Ateneo Musician's Pool Design commitee.
	Ateneo Musician's Pool Design commitee members, including alumni,
	have full ownership of this code. Transfer of ownership is only
	through jurisdiction of both the author and the organization.

	Uncited referencing of this code will be considered plagiarized.
	Full documentation will be published once available.

	(c) 2015-2016
*/

$(document).ready(function() {
	aboutAmp();
});

function aboutAmp() {
	// perform ajax queries to save about amp
	var amp = $('#about-amp');
	var heading = $('#about-heading', amp);
	var description = $('#about-description', amp);
	var mission = $('#about-mission', amp);
	var vision = $('#about-vision', amp);
	var message = $('.message', amp);
	var save = $('.save', amp);
	var reset = $('.reset', amp);
	var buttons = $('.button');
	buttons.attr('disabled', '');
	$('input,textarea', amp).change(function() {
		buttons.removeAttr('disabled');
	});
	reset.click(function() {
		if ($(this).is('[disabled]')) return;
		buttons.attr('disabled', '');
		window.location.reload();
	});
	save.click(function() {
		if ($(this).is('[disabled]')) return;
		// all good. perform ajax request
		$.post('/admin/php/json.ajax.php', {
			json: 'meta',
			inplace: 'true',
			datastr: JSON.stringify({
				'about-heading': heading.val(),
				'about-description': description.val(),
				mission: mission.val(),
				vision: vision.val()
			})
		}).done(function(data) {
			if (data.startsWith('ERROR')) {
				message.addClass('warning');
				message.trigger('popup', 'There was a problem in the request. Try reloading the page.');
			} else {
				message.removeClass('warning');
				message.trigger('popup', 'Successfully saved');
				buttons.attr('disabled', '');
				// window.location.href = 'index.php';
			}
		});
	});
}