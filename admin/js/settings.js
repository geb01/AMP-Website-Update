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
	// perform ajax queries to save new password
	var changer = $('#change-password');
	var currentPass = $('#current-pass', changer);
	var newPass = $('#new-pass', changer);
	var confirmPass = $('#confirm-pass', changer);
	var message = $('.message', changer);
	var save = $('.change', changer);
	save.click(function() {
		// check if password is non-empty
		if (newPass.val().length == 0) {
			message.css('background-color', '#990');
			message.trigger('popup', "Password must have at least 1 character");
			newPass.blur().select();
		}
		// check if confirm password is validated
		else if (newPass.val() != confirmPass.val()) {
			message.css('background-color', 'red');
			message.trigger('popup', "Passwords don't match");
			confirmPass.blur().select();
		}
		// all good. perform ajax request
		else {
			$.post(
				window.location,
				{current_pass: currentPass.val(), new_pass: newPass.val()},
				function(data) {
					console.log(data);
					if (data == 'invalid') {
						message.css('background-color', 'red');
						message.trigger('popup', 'Invalid credentials');
						currentPass.blur().select();
					} else if (data != '1') {
						alert('There was a problem in the request. Please contact the administrator.');
					} else {
						alert('Password was successfully changed.\nYou will be redirected to the login page.');
						window.location.href = 'index.php';
					}
				}
			);
		}
	});
	// save on enter
	$(changer).keydown(function(e) {
		if (e.keyCode == 13) { // ENTER
			save.click();
			e.preventDefault();
		}
	});
});
