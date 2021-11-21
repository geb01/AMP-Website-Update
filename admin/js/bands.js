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
	var bandsEditor = $('#bands-editor');
	var editor = new ContextEditor(bandsEditor, 'bands');

	// enable save buttons when images are uploaded 
	bandsEditor.on('change', '.uploadable-form>input[type=file]', function() {
		editor.enable();
	});

	// upload image strip button
	bandsEditor.on('click', '.img-upload-btn', function() {
		$('[name="image strip"]', $(this).parent()).trigger('upload');
	});

	// enable save button on change
	bandsEditor.on('change', 'input,textarea', function() {
		editor.enable();
	});

	// add new band member
	bandsEditor.on('click', '.member-add', function() {
		var member = 
			"<input type='text' name='member-name' title='member name' />"
			+ "<input type='text' name='member-role' title='separate with slashes (/)' />"
			+ "<div class='delete-btn'>x</div><br>";
		var elem = $(member);
		elem.insertBefore(this);
		editor.enable();
	});

	// delete band member
	bandsEditor.on('click', '.delete-btn', function() {
		var btn = $(this);
		var role = btn.prev();
		var name = role.prev();
		var br = btn.next();
		if (confirm('Are you sure you want to delete '+ name.val() + '?\n')) {
			role.add(name).add(btn).add(br).remove();
			editor.enable();
		}
	});

	// context menu
	editor.contextMenu.push(['Upload Image', changeImage]);
	editor.contextMenu.push(['Delete', deleteNode]);
	editor.save.click(onSave);

	// add a new slide
	$('.add', bandsEditor).click(function() {
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

			function gval(name) {
				var e = $('[name="' + name + '"]', legend);
				var val = e.is('img') ? e.attr('src') : e.val();
				return val && val.length ? val : null;
			}

			function eval(name) {
				var val = gval(name);
				if (val) dict[name] = val;
			}

			function cval(name) {
				var val = $('[name="' + name + '"]', legend).val();
				var arr = val.split(',').map(function(s) {return s.trim();}).filter(function(s) {return s.length;});
				if (arr.length)
					dict[name] = arr;
			}

			eval('name');
			eval('description');
			eval('image');
			eval('image strip');
			cval('contact');

			function rmprefix(s, f) {
				return s.startsWith(f) ? s.substring(f.length) : s;
			}

			// get links
			var links = [
				gval('link-facebook'),
				gval('link-twitter'),
				gval('link-youtube'),
				gval('link-soundcloud')
			].filter(function(e) {return e;}).map(function(e) {
				e = e.toLowerCase();
				return 'https://' + rmprefix(rmprefix(rmprefix(e.trim(), 'https://'), 'http://', 'www.'));
			});

			if (links.length)
				dict['links'] = links;

			// get members
			var members = {};
			var names = $('[name=member-name]', legend);
			var roles = $('[name=member-role]', legend);
			for (var i = 0; i < names.length; ++i) {
				var name = names[i].value.trim(); // note these are DOM objects
				var role = roles[i].value;
				var roleList = role.split('/').map(function(s) {return s.trim();}).filter(function(s) {return s.length});
				if (name.length || roleList.length) {
					// member is not empty, add this member
					if (name in members) {
						var mems = members[name];
						for (var j = 0; j < roleList.length; ++j) {
							var r = roleList[j];
							if (mems.indexOf(r) == -1)
								mems.push(r);
						}
					}
					else
						members[name] = roleList;
				}
			}
			dict['members'] = members;
			
			result.push(dict);
		});
		editor.ajax(result);
	}

	// perform save
	function onSave() {
		if ($(this).is('[disabled]')) return;
		if (!confirm('Are you sure you want to save?')) return;

		// check first if there are uploaded images
		window.uploadImages(bandsEditor, 'artists', finalSave);
	}

	// add an image uploading feature to context menu
	function changeImage() {
		var img = $('[name=image]', editor.legend);
		img.trigger('upload');
	}

	// delete current node
	function deleteNode() {
		if (confirm('Are you sure you want to delete ' + $('[name=name]', editor.node).val() + '?\n'
			+ 'Warning: this cannot be undone after save')) {
			editor.node.remove();
			editor.enable();
			return true;
		}
		return false;
	}

});