<?php
	require_once '../../../php/global.php';
	createLogin();
	source('dashboard.php');
	$site = $protocol.$host.'/about.php';
	function br($text) {
		return str_replace('\n', "\r\n", $text);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('about.js'); ?>
		<?php source('message.js'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('bg-editor.js'); ?>
		<title>AMP Admin - About</title>
		<style>
		</style>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('About'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>About Page</h1>
					<ul>

						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may edit the about page's metadata here.</li>
						<li>You may wrap with HTML tags like:
						<ul>
							<li><code>&lt;b&gt; <b style='color:#FF0068'>BOLD</b> &lt;/b&gt;</code></li>
							<li><code>&lt;i&gt; <i>ITALIC</i> &lt;/i&gt;</code></li>
							<li><code>&lt;u&gt; <u>UNDERLINE</u> &lt;/u&gt;</code></li>
						</ul></li>
					</ul>
					<fieldset id='about-amp' class='editor fieldset-editor'>
						<legend>About Amp</legend>
						<div><?php $meta = json('meta'); ?>
							<table class='fieldset-table' cellspacing='10'><tr>
								<td>Heading</td>
								<td><input type='text' class='entry' id='about-heading' value='<?php echo br(get($meta, 'about-heading')); ?>'></input><td>
							</tr><tr>
								<td>Description</td>
								<td><textarea type='text' class='entry' id='about-description'><?php echo br(get($meta, 'about-description')); ?></textarea><td>
							</tr><tr>
								<td>Mission</td>
								<td><textarea type='text' class='entry' id='about-mission'><?php echo br(get($meta, 'mission')); ?></textarea><td>
							</tr><tr>
								<td>Vision</td>
								<td><textarea type='text' class='entry' id='about-vision'><?php echo br(get($meta, 'vision')); ?></textarea><td>
							</tr></table>
							<div class='save button' disabled>Save Changes</div>
							<div class='reset button' disabled>Reset</div>
							<span class='message'></span>
						</div>
					</fieldset>
					<fieldset class='editor bg-editor fieldset-editor' name='about-bg'>
						<legend>About Background Image</legend>
						<div>
							<button class='img-upload-btn'>Change Image</button>
							<img class='img uploadable' src='<?= get(json('meta'), 'about-bg') ?>'>
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