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
	var bgEditor = $('.bg-editor'); // mandatory: name
	if (bgEditor.length != bgEditor.filter('[name]').length)
		window.alert('Warning: all .bg-editor should have a name corresponding to the meta property to be modified in meta.json');
	var buttons = $('.button', bgEditor);
	var save = buttons.filter('.save');
	var reset = buttons.filter('.reset');
	bgEditor.on('change', '.uploadable-form>input[type=file]', function() {buttons.removeAttr('disabled');});
	reset.click(function() {
		if ($(this).is('[disabled]')) return;
		save.attr('disabled', '');
		window.location.reload();
	});
	$('.img-upload-btn', bgEditor).click(function() {
		$(this).parent().children('.img').trigger('upload');
	});
	save.click(function() {
		var button = $(this);
		if (button.is('[disabled]')) return;
		var editor = button.closest('.bg-editor');
		var name = editor.attr('name');
		window.uploadImages(bgEditor, 'bg', function() {
			var data = {}; data[name] = $('.img', editor).attr('src');
			var message = $('.message', editor);
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
					$('.button', editor).attr('disabled', '');
				}
			});
		});
	});
});