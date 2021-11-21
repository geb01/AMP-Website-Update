<?php
	require_once '../php/global.php';
	// change password script
	if (isset($_POST['current_pass']) && isset($_POST['new_pass'])) {

		$current = md5($_POST['current_pass']);
		$new = md5($_POST['new_pass']);

		// perform authentication checks
		if (!authenticated() || !validPassword($current))
			die('invalid');
		// update database
		$query = "UPDATE about SET description='$new' WHERE header='auth_key'";
		$result = database()->exec($query);
		if ($result) {
			unset($_SESSION['auth_key']);
			die('1');
		}
		die('0');
	}
	createLogin();
	source('dashboard.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('settings.js'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('message.js'); ?>
		<?php source('bg-editor.js'); ?>
		<title>AMP Admin - Settings</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Admin'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Settings</h1>
					<fieldset id='change-password' class='editor fieldset-editor'>
						<legend>Change password</legend>
						<div>
							<table class='fieldset-table' cellspacing='10'><tr>
								<td>Current Password</td>
								<td><input type='password' class='entry' id='current-pass'></input><td>
							</tr><tr>
								<td>New Password</td>
								<td><input type='password' class='entry' id='new-pass'></input><td>
							</tr><tr>
								<td>Confirm Password</td>
								<td><input type='password' class='entry' id='confirm-pass'></input><td>
							</tr></table>
							<div class='change button'>Change Password</div>
							<span class='message'></span>
						</div>
					</fieldset>
					<fieldset class='editor bg-editor fieldset-editor' name='admin-bg'>
						<legend>Admin Background Image</legend>
						<div>
							<button class='img-upload-btn'>Change Image</button>
							<img class='img uploadable' src='<?= get(json('meta'), 'admin-bg') ?>'>
						</div>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<span class='message'></span>
					</fieldset>
				</div>
			</div>
			<?php createDashboard(); ?>
		</div>
	</body>
</html>