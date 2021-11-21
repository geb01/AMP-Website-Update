<?php
require_once '../../php/global.php';
createLogin();
source('dashboard.php');
$site = $protocol.$host.'/amplugged.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('message.js'); ?>
		<?php source('bg-editor.js'); ?>
		<title>AMP Admin - Amplugged</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Amplugged'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Amplugged Page</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>Go to the Amplugged playlist in <a href='https://www.youtube.com/watch?v=RzvLuyA87ss&list=PLR2k5ELr5FgVJ-dXsvbnkoHeAN3kOVNB_' target='_blank'><code>Youtube</code></a> and edit videos there.</li>
					</ul>
					<fieldset class='editor bg-editor fieldset-editor' name='amplugged-bg'>
						<legend>Amplugged Background Image</legend>
						<div>
							<button class='img-upload-btn'>Change Image</button>
							<img class='img uploadable' src='<?= get(json('meta'), 'amplugged-bg') ?>'>
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