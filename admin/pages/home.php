<?php
require_once '../../php/global.php';
createLogin();
source('dashboard.php');
$site = $protocol.$host.'/';
function template($item, $id = null) { ?>
	<div class='node' <?= $id ? "id='$id'" : '' ?>>
		<div class='legend'>
			<div class='menu-button'></div>
			<img class='img uploadable' name='image' src='<?php echo get($item, 'image'); ?>'>
			<?php $quote = get($item, 'quote', array()); ?>
			<table class='fieldset-table' cellspacing='10'><tr>
				<td>Quote Content</td>
				<td><textarea type='text' class='entry' name='quote-content'><?= get($quote, 'content') ?></textarea></td>
			</tr><tr>
				<td>Song Title</td>
				<td><input type='text' class='entry' name='quote-song' value='<?= singlequote(get($quote, 'song')) ?>'></td>
			</tr><tr>
				<td>Song Artist</td>
				<td><input type='text' class='entry' name='quote-artist' value='<?= singlequote(get($quote, 'artist')) ?>'></td>
			</tr></table>
		</div>
	</div>
<?php } ?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('home.css'); ?>
		<?php source('jquery-ui.min.js'); ?>
		<?php source('context-editor.js'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('home.js'); ?>
		<?php source('message.js'); ?>
		<title>AMP Admin - Home Page</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Home Page'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Home Page</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may edit the home image slideshow here.</li>
						<li>Optional: You may also put quotes for each image.</li>
						<li>If the artist exists in the database, it will automatically link to the correct page.</li>
					</ul>

					<div id='home-editor' class='editor context-editor fieldset-editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<br>
						<?php $home_slides = json('home'); ?>
						<div class='tree'>
							<?php foreach ($home_slides as &$item) template($item); ?>
							<?php template(array(), 'dummy'); ?>
							<div class='add'>+</div>
						</div>
						<br>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
					</div>

				</div>
			</div>
			<?php createDashboard(); ?>
		</div>
	</body>
</html>