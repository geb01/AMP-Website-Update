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
	infoEditor();
	memberEditor();
});

function infoEditor() {
	var infoEditor = $('#eb-info-editor');
	var description = $('#eb-description', infoEditor);
	var groupPhoto = $('#eb-group-photo');
	var buttons = $('.button', infoEditor);
	var message = $('.message', infoEditor);
	description.change(function() {
		buttons.removeAttr('disabled');
	});
	groupPhoto.click(function() {
		groupPhoto.trigger('upload');
		buttons.removeAttr('disabled');
	});
	$('.save', infoEditor).click(function() {
		if ($(this).is('[disabled]')) return;
		window.uploadImages(infoEditor, 'about', function() {
			$.post('/admin/php/json.ajax.php', {
				json: 'meta',
				inplace: 'true',
				datastr: JSON.stringify({
					'executive board': description.val(),
					'eb group photo': groupPhoto.attr('src')
				})
			}).done(function(data) {
				console.log(data);
				if (data.startsWith('ERROR')) {
					message.addClass('warning');
					message.trigger('popup', 'There was a problem in the request. Try reloading the page.');
				} else {
					message.removeClass('warning');
					message.trigger('popup', 'Successfully saved');
					buttons.attr('disabled','');
					// window.location.href = 'index.php';
				}
			});
		});
	});
	$('.reset', infoEditor).click(function() {
		if ($(this).is('[disabled]')) return;
		console.log('here');
		$('.save', infoEditor).attr('disabled', '');
		window.location.reload();
	});
}

function memberEditor() {

	var ebEditor = $('#eb-editor');
	var editor = new ContextEditor(ebEditor, 'executive board');

	// enable save buttons when images are uploaded 
	ebEditor.on('change', '.uploadable-form>input[type=file]', function(e) {
		editor.enable();
	});

	// create function objects for text prompts
	var editPosition = editable('position', 'Edit position name');
	var editName = editable('member-name', 'Edit member name');
	var editYear = editable('member-year', 'Edit enrollment year of member');
	var editCourse = editable('member-course', 'Edit member\'s course');
	var editDescription = editable('description', 'Edit description');

	// add options to context menu
	editor.contextMenu.push(['Upload Image', changeImage]);
	editor.contextMenu.push(['Edit Position Name', editPosition]);
	editor.contextMenu.push(['Edit Member Name', editName]);
	editor.contextMenu.push(['Edit Enrollment Year', editYear]);
	editor.contextMenu.push(['Edit Course', editCourse]);
	editor.contextMenu.push(['Edit Description', editDescription]);
	editor.contextMenu.push(['Delete', deleteNode]);
	editor.save.click(onSave);

	// add a new slide
	$('.add', ebEditor).click(function() {
		var add = $(this);

		// create a dummy file input first
		var input = $("<input type='file' name='file' />").click();
		var first_creation = true;

		input.change(function() {
			// a picture was uploaded
			if (!first_creation) return;
			first_creation = false;
			var node = $(
				"<div class='node'><div class='legend'>"
				+ "<div class='menu-button'></div>"
				+ "<img class='img uploadable' name='image'>"
				+ "<span name='position'></span>"
				+ "<span name='description'></span>"
				+ "<div class='member-wrapper'>"
				+ "<div name='member-name'></div>"
				+ "<div name='member-year'></div>"
				+ "<div name='member-course'></div>"
				+ "</div>"
				+ "</div></div>"
			);
			editor.legend = $('.legend', node);
			if (editPosition() && editDescription() && editName() && editYear() && editCourse()) {
				// @see uploadable.js
				node.insertBefore(add);
				var form = $("<form class='uploadable-form' enctype='multipart/form-data'></form>");
				form.append(input);
				form.append($("<input type='text' name='upload_dir' value='' />"));
				$('.img', node).after(form);
				input.trigger('change');
			}
		});

	});

	// save configuration to json file
	function finalSave() {
		var result = [];
		editor.root.children('.node').each(function() {
			var node = $(this);
			var legend = node.children('.legend');
			var dict = {};
			dict['image'] = $('[name=image]', legend).attr('src');
			var member = {};
			var name = $('[name=member-name]', legend).html();
			var year = $('[name=member-year]', legend).html();
			var course = $('[name=member-course]', legend).html();
			if (name.length)
				member['name'] = name;
			if (year.length)
				member['year'] = parseInt(year);
			if (course.length)
				member['course'] = course;
			dict['member'] = member;
			dict['position'] = $('[name=position]', legend).html();
			dict['description'] = $('[name=description]', legend).html();
			result.push(dict);
		});
		editor.ajax(result);
	}

	// perform save
	function onSave() {
		if ($(this).is('[disabled]')) return;
		if (!confirm('Are you sure you want to save?')) return;
		window.uploadImages(ebEditor, 'about/eb', finalSave);
	}

	// add an image uploading feature to context menu
	function changeImage() {
		var img = $('.img', editor.legend);
		img.trigger('upload');
	}

	// a generic function that returns a prompting function object
	function editable(property, promptText) {
		return function() {
			var content = $('[name=' + property + ']', editor.legend);
			var text = prompt(promptText + ':\n', content.html());
			if (text !== null) {
				content.html(text);
				editor.enable();
				return true;
			}
			return false;
		};
	}

	// delete current node
	function deleteNode() {
		editor.node.remove();
		editor.enable();
		return true;
	}
}