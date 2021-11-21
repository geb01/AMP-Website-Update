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
	var resultsEditor = $('#results-editor');
	var editor = new ContextEditor(resultsEditor, 'results');

	// enable buttons when images are uploaded 
	resultsEditor.on('change', '.uploadable-form>input[type=file]', editor.enable);

	editor.contextMenu.push(['Change Image', changeImage]);
	editor.contextMenu.push(['Delete', deleteNode]);
	editor.save.click(onSave);

	// add a new slide
	$('.add', resultsEditor).click(function() {
		var add = $(this);

		// create a dummy file input first
		var input = $("<input type='file' name='file' />").click();
		var first_creation = true;

		input.change(function() {
			// a picture was uploaded
			if (!first_creation) return;
			first_creation = false;
			var dummy = $('#dummy', resultsEditor);
			var node = dummy.clone();
			node.removeAttr('id');
			node.insertBefore(dummy);
			editor.legend = $('.legend', node);
			// @see uploadable.js
			node.insertBefore(add);
			var form = $("<form class='uploadable-form' enctype='multipart/form-data'></form>");
			form.append(input);
			form.append($("<input type='text' name='upload_dir' value='' />"));
			$('.img', node).after(form);
			input.trigger('change');
		});

	});

	// save configuration to json file
	function finalSave() {
		var result = [];
		editor.root.children('.node:not(#dummy)').each(function() {
			var node = $(this);
			var legend = node.children('.legend');
			result.push($('.img', legend).attr('src'));
		});
		editor.ajax(result);
	}

	// perform save
	function onSave() {
		if ($(this).is('[disabled]')) return;
		if (!confirm('Are you sure you want to save?')) return;

		// check first if there are uploaded images
		window.uploadImages(resultsEditor, 'results', finalSave);
	}

	// add an image uploading feature to context menu
	function changeImage() {
		var img = $('.img', editor.legend);
		img.trigger('upload');
	}

	// delete current node
	function deleteNode() {
		if (confirm('Are you sure you want to delete this image?\n'
			+ 'Warning: this cannot be undone after save')) {
			editor.node.remove();
			editor.enable();
			return true;
		}
		return false;
	}

});