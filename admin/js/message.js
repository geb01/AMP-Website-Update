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
	// enable editor message popup trigger
	$('.editor .message').bind('popup', function(e, text) {
		var message = $(this);
		message.html(text);
		message.css('width', this.scrollWidth + 40 + 'px');
		setTimeout(function() {message.css('width', '0px');}, 5000);
	});

	// add save prompt if leaving page
	window.addEventListener("beforeunload", function (e) {
		if ($('.editor .save:not([disabled])').length) {
		    var confirmationMessage = 'You have unsaved changes. Are you sure you want to exit?';
		    (e || window.event).returnValue = confirmationMessage; //Gecko + IE
		    return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
		}
	});
});
