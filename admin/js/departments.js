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
	var depEditor = $('#dep-editor');
	var editor = new ContextEditor(depEditor, 'departments');

	// enable save buttons when images are uploaded 
	depEditor.on('change', '.uploadable-form>input[type=file]', function(e) {
		editor.enable();
	});

	// create function objects for text prompts
	var editDepartment = editable('name', 'Edit department name');
	var editDescription = editable('description', 'Edit description');
	var editOfficers = editable('officers', 'Edit officers (separate with slash "/")');

	// add options to context menu
	editor.contextMenu.push(['Upload Image', changeImage]);
	editor.contextMenu.push(['Edit Name of Department', editDepartment]);
	editor.contextMenu.push(['Edit Description', editDescription]);
	editor.contextMenu.push(['Edit Officers', editOfficers]);
	editor.contextMenu.push(['Delete', deleteNode]);
	editor.save.click(onSave);

	// add a new slide
	$('.add', depEditor).click(function() {
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
				+ "<span name='name'></span>"
				+ "<span name='description'></span>"
				+ "<span name='officers'></span>"
				+ "</div></div>"
			);
			editor.legend = $('.legend', node);
			if (editDepartment() && editDescription() && editOfficers()) {
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
			dict['name'] = $('[name=name]', legend).html();
			dict['description'] = $('[name=description]', legend).html();
			var officers = $('[name=officers]', legend).html().split('/');
			dict['officers'] = officers.map(function(e) {return {name: $.trim(e)};})
			result.push(dict);
		});
		editor.ajax(result);
	}

	// perform save
	function onSave() {
		if ($(this).is('[disabled]')) return;
		if (!confirm('Are you sure you want to save?')) return;
		window.uploadImages(depEditor, 'about/departments', finalSave);
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

});