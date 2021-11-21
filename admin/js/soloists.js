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
	var soloistsEditor = $('#soloists-editor');
	var editor = new ContextEditor(soloistsEditor, 'soloists');

	// enable save buttons when images are uploaded 
	soloistsEditor.on('change', '.uploadable-form>input[type=file]', function() {
		editor.enable();
	});

	// upload image strip button
	soloistsEditor.on('click', '.img-upload-btn', function() {
		$('[name="image strip"]', $(this).parent()).trigger('upload');
	});

	// enable save button on change
	soloistsEditor.on('change', 'input,textarea', function() {
		editor.enable();
	});

	// add new member
	soloistsEditor.on('click', '.member-add', function() {
		var member = 
			"<input type='text' name='member-name' title='member name' />"
			+ "<input type='text' name='member-genre' title='genre/instrument' />"
			+ "<img src='/images/icons/soundcloud-icon.png' height='32' style='float:left'>"
			+ "<input type='text' name='member-soundcloud' title='soundcloud' />"
			+ "<div class='delete-btn'>x</div><br>";
		var elem = $(member);
		elem.insertBefore(this);
		editor.enable();
	});

	// delete member
	soloistsEditor.on('click', '.delete-btn', function() {
		var btn = $(this);
		if (confirm('Are you sure you want to delete '+ btn.prev().prev().prev().prev().val() + '?\n')) {
			for (var i = 0; i < 4; ++i)
				btn.prev().remove();
			btn.add(btn.next()).remove();
			editor.enable();
		}
	});

	// context menu
	editor.contextMenu.push(['Delete', deleteNode]);
	editor.save.click(onSave);

	// add a new slide
	$('.add', soloistsEditor).click(function() {
		var add = $(this);
		var dummy = $('#dummy.node');
		var node = dummy.clone();
		node.removeAttr('id');
		node.insertBefore(dummy);
		editor.enable();
	});

	// save configuration to json file
	function finalSave() {
		var result = [];
		editor.root.children('.node:not(#dummy)').each(function() {
			var node = $(this);
			var legend = node.children('.legend');
			var dict = {};
			dict['role'] = $('[name=role]', legend).val();
			dict['image strip'] = $('[name="image strip"]', legend).attr('src');

			// get members
			var members = [];
			var names = $('[name=member-name]', legend);
			var genres = $('[name=member-genre]', legend);
			var soundclouds = $('[name=member-soundcloud]', legend);
			for (var i = 0; i < names.length; ++i) {
				var name = names[i].value.trim(); // note these are DOM objects
				var genre = genres[i].value.trim().replace(/\s*\/\s*/g, '/').replace(/\s+/g, ' ').replace(/\s*,\s*/g, ', ');
				var soundcloud = soundclouds[i].value.trim().replace(/https?:\/\/soundcloud.com\/?/g, '');
				if (name.length) {
					var member = {name: name};
					if (genre.length) member['genre'] = genre;
					if (soundcloud.length) member['soundcloud'] = 'https://soundcloud.com/' + soundcloud;
					members.push(member);
				}
			}
			dict['artists'] = members;
			result.push(dict);
		});
		editor.ajax(result);
	}

	// perform save
	function onSave() {
		if ($(this).is('[disabled]')) return;
		if (!confirm('Are you sure you want to save?')) return;

		// check first if there are uploaded images
		window.uploadImages(soloistsEditor, 'artists', finalSave);
	}

	// delete current node
	function deleteNode() {
		if (confirm('Are you sure you want to delete ' + $('[name=role]', editor.node).val() + '?\n'
			+ 'Warning: this cannot be undone after save')) {
			editor.node.remove();
			editor.enable();
			return true;
		}
		return false;
	}

});