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

// generic context editor prep function
function ContextEditor(editor, json) {
	editor = $(editor);
	var self = this;
	self.save = $('.save', editor);
	self.reset = $('.reset', editor);
	self.root = editor.children('.tree');
	self.update = function(){};
	self.contextMenu = [];
	self.json = json;

	// function that enables save and reset buttons
	var enable = self.enable = function() {self.save.add(self.reset).removeAttr('disabled');}

	// make nodes sortable
	$('.tree', editor).sortable({
		update: function() {self.enable(); self.update();}
	});

	// interaction with menu editor
	editor.on('click', '.node>.legend>.menu-button', function() {
		// don't do anything if context menu is already showing

		self.legend = $(this).closest('.legend');
		self.node = self.legend.closest('.node');
		if ($('#context-menu:not(.hidden)').length)
			$('#context-menu').click();
		createContextMenu(self.contextMenu, $(this));
	});

	// default ajax function
	self.ajax = function(result) {
		$.post('/admin/php/json.ajax.php', {
			json: self.json,
			datastr: JSON.stringify(result)
		}).done(function(data) {
			if (data.startsWith('ERROR'))
				alert('ERROR: ' + data);
			else {
				self.save.attr('disabled', '');
				window.location.reload();
			}
		});
	}

	// reload on reset
	self.reset.click(function() {
		if ($(this).is('[disabled]')) return;
		self.save.attr('disabled', '');
		window.location.reload();
	});

}

function getNodePath(node) {
	var curNode = node.hasClass('node') ? node : node.closest('.node');
	if (!curNode.length) return '';
	var prevNodes = getNodePath(curNode.parent());
	if (prevNodes == '') return $('[name=label]', curNode.children('.legend')).html();
	else return prevNodes + '/' + $('[name=label]', curNode.children('.legend')).html();
}