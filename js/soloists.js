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
	var roleList = $('#role-list');
	roleList.children('li:first-child').addClass('active');
	$('.role-name').click(function() {
		roleList.children('.active').removeClass('active');
		$(this).parent().addClass('active');
	});
});