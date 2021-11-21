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
	var editor = new ContextEditor('#menu-editor', 'menu');

	editor.contextMenu.push(['New/Same tab', toggleTabState]);
	editor.contextMenu.push(['Edit Link', editLink]);
	editor.contextMenu.push(['Rena<u>m</u>e', rename]);
	editor.contextMenu.push([enableDisableLabel, enableDisable]);
	editor.contextMenu.push(['Delete', deleteNode]);
	editor.save.click(onSave);

	// allow add button to get input
	$('#menu-editor .add').click(function() {
		var label= prompt('Enter label:');
		if (!label) return;

		var href = prompt('Enter link:', 'http://');
		if (!href) return;

		editor.enable();
		$(
			"<div class='node'><div class='legend pad'><span name='label'>"
			+ label
			+ "</span>"
			+ "<span name='active'></span>"
			+ "<span name='open new tab' disabled></span>"
			+ "<code name='href'>"
			+ href
			+ "</code><div class='menu-button'></div>"
			+ "</div></div>"
		).insertBefore($(this));
		editor.root.sortable();
	});

	// toggle if the current menu item opens in new tab or on the same tab 
	function toggleTabState() {
		var state = $("[name='open new tab']", editor.legend);
		if (state.is('[disabled]')) state.removeAttr('disabled');
		else state.attr('disabled', '');
		editor.enable();
	}

	// edit href link of menu item
	function editLink() {
		var href = $('[name=href]', editor.legend);
		var path = getNodePath(editor.node);
		var text = prompt('Edit link of [' + path + ']:', href.length ? href.html() : 'http://');
		if (text) {
			href.html(text);
			editor.enable();
		}
	}


	// rename label of menu item
	function rename() {
		var label = $('[name=label]', editor.legend);
		var path = getNodePath(editor.node);
		var text = prompt('Enter new name of [' + path + ']:', label.html());
		if (text) {
			label.html(text);
			editor.enable();
		}
	}

	// enable/disable label
	function enableDisableLabel() {
		return $('[name=active][disabled]', editor.legend).length ? 'Enable' : 'Disable';
	}

	// enable/disable function proper
	function enableDisable() {
		var active = $('[name=active]', editor.legend);
		if (active.is('[disabled]'))
			active.removeAttr('disabled');
		else
			active.attr('disabled', '');
		editor.enable();
	}


	// delete current node
	function deleteNode() {
		editor.node.remove();
		editor.enable();
	}

	// save configuration to json file
	function onSave() {
		if ($(this).is('[disabled]')) return;
		if (!confirm('Are you sure you want to save?')) return;

		var result = [];
		editor.root.children('.node').each(function() {
			var node = $(this);
			var legend = node.children('.legend');
			var dict = {};
			dict['label'] = $('[name=label]', legend).html();
			dict['href'] = $('[name=href]', legend).html();
			dict['active'] = !$('[name=active][disabled]', legend).length;
			dict['open new tab'] = !$('[name="open new tab"][disabled]', legend).length;
				// dict['submenu'] = node.children('.tree').length ? dfs(node.children('.tree')) : null;
			result.push(dict);	
		});
		editor.ajax(result);
	}


});
