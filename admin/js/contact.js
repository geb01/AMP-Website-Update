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
$(function() {
	var editor = $('#contact-editor');
	var input = $('input', editor);
	var buttons = $('.button', editor);
	var save = buttons.filter('.save');
	var reset = buttons.filter('.reset');
	var message = $('.message', editor);
	input.change(function() {
		buttons.removeAttr('disabled');
	});
	reset.click(function() {
		if ($(this).is('[disabled]')) return;
		save.attr('disabled', '');
		window.location.reload();
	});
	save.click(function() {
		if ($(this).is('[disabled]')) return;
		// all good. perform ajax request
		var data = {};
		input.each(function() {
			var $this = $(this);
			var entry = 'contact-' + $this.attr('name');
			data[entry] = $this.val().trim();
		});
		$.post('/admin/php/json.ajax.php', {
			json: 'meta',
			inplace: 'true',
			datastr: JSON.stringify(data)
		}).done(function(data) {
			if (data.startsWith('ERROR')) {
				message.addClass('warning');
				message.trigger('popup', 'There was a problem in the request. Try reloading the page.');
			} else {
				message.removeClass('warning');
				message.trigger('popup', 'Successfully saved');
				buttons.attr('disabled', '');
			}
		});
	});
});