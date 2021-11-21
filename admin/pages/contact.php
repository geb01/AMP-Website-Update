<?php
require_once '../../php/global.php';
createLogin();
source('dashboard.php');
$site = $protocol.$host.'/contact.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('contact.js'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('message.js'); ?>
		<?php source('bg-editor.js'); ?>
		<title>AMP Admin - Contact Us</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Contact Us'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Contact Page</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may edit what contact information to display</li>
					</ul>
					<fieldset id='contact-editor' class='editor fieldset-editor'>
						<legend>Contact Information</legend>
						<div><?php $contact = json('meta'); ?>
							<table class='fieldset-table' cellspacing='10'>
								<tr>
									<td>Facebook</td>
									<td><input type='text' name='facebook' value='<?= singlequote(get($contact, 'contact-facebook', '')) ?>'></td>
								</tr><tr>
									<td>Twitter</td>
									<td><input type='text' name='twitter' value='<?= singlequote(get($contact, 'contact-twitter', '')) ?>'></td>
								</tr><tr>
									<td>Email</td>
									<td><input type='text' name='email' value='<?= singlequote(get($contact, 'contact-email', '')) ?>'></td>
								</tr>
							</table>
							<div class='save button' disabled>Save Changes</div>
							<div class='reset button' disabled>Reset</div>
						</div>
					</fieldset>
					<fieldset class='editor bg-editor fieldset-editor' name='contact-bg'>
						<legend>Contact Background Image</legend>
						<div>
							<button class='img-upload-btn'>Change Image</button>
							<img class='img uploadable' src='<?= get($contact, 'contact-bg') ?>'>
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