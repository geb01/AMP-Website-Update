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
	var editor = $('.editor');
	var buttons = $('.button', editor);
	var save = $('.save', editor);
	editor.on('change', '.uploadable-form>input[type=file]', function() {
		buttons.removeAttr('disabled');
	});
	$('.img', editor).click(function() {
		$(this).trigger('upload');
	});
	$('.reset', editor).click(function() {
		if ($(this).is('[disabled]')) return;
		save.attr('disabled', '');
		window.location.reload();
	});
	save.click(function() {
		if ($(this).is('[disabled]')) return;
		window.uploadImages(editor, 'artists', function() {
			$.post('/admin/php/json.ajax.php', {
				json: 'artists',
				datastr: JSON.stringify({
					bands: $('#bands-img').attr('src'),
					soloists: $('#soloists-img').attr('src')
				})
			}).done(function(data) {
				if (data.startsWith('ERROR'))
					alert('ERROR: ' + data);
				else {
					save.attr('disabled', '');
					window.location.reload();
				}
			});
		});
	});
});