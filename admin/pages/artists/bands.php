<?php
require_once '../../../php/global.php';
createLogin();
source('dashboard.php');
$site = $protocol.$host.'/bands.php';
function template($item, $id = null) {
	$members = get($item, 'members', array());
	$links = get($item, 'links', array());
	$link_data = array();
	foreach ($links as $link) {
		$link = strtolower($link);
		if (starts_with($link, 'http://'))
			$link = substr($link, strlen('http://'));
		else if (starts_with($link, 'https://'))
			$link = substr($link, strlen('https://'));
		if (starts_with($link, 'www.'))
			$link = substr($link, strlen('www.'));
		$site = substr($link, 0, strpos($link, '.'));
		$link_data[$site] = $link;
	}
	$use_links = array('facebook', 'twitter', 'youtube', 'soundcloud'); ?>
	<div class='node' <?= $id ? "id='$id'" : '' ?>>
		<div class='legend'>
			<div class='menu-button'></div>
			<img class='img uploadable' name='image' src='<?= get($item, 'image') ?>'>
			<table class='fieldset-table' cellspacing='10'><tr>
				<td>Band name</td>
				<td><input type='text' class='entry' name='name' value='<?= singlequote(get($item, 'name')) ?>'></td>
			</tr><tr>
				<td>Description</td>
				<td><textarea type='text' class='entry' name='description'><?= get($item, 'description') ?></textarea></td>
			</tr><tr>
				<td>Contact</td>
				<td><input type='text' class='entry' name='contact' title='separate with commas (,)' value='<?= singlequote(implode(', ', get($item, 'contact', array()))) ?>'></td>
			</tr>
			<?php foreach ($use_links as $link_name): ?>
			<tr>
				<td><?php echo $link_name; ?></td>
				<td><input type='text' class='entry' name='link-<?= $link_name ?>' value='<?= singlequote(get($link_data, strtolower($link_name))) ?>'></td>
			</tr>
			<?php endforeach; // use_links ?>
			<tr>
				<td>Members</td>
				<td class='members'>
				<?php foreach ($members as $member => $roles): ?>
					<input type='text' name='member-name' title='member name' value='<?= singlequote($member) ?>'/>
					<input type='text' name='member-role' title='separate with slashes (/)' value='<?= singlequote(implode('/', $roles)) ?>'/>
					<div class='delete-btn'>x</div>
					<br>
				<?php endforeach; // members ?>
					<div class='member-add button'>Add New Member</div>
				</td>
			</tr>
			<tr>
				<td>Image Strip</td>
				<td>
					<input type='button' value='Upload image' class='img-upload-btn'>
					<img class='img uploadable' name='image strip' src='<?= get($item, 'image strip') ?>'>
				</td>
			</tr>
			</table>
		</div>
	</div>
<?php } ?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('bands.css'); ?>
		<?php source('jquery-ui.min.js'); ?>
		<?php source('context-editor.js'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('bands.js'); ?>
		<?php source('message.js'); ?>
		<title>AMP Admin - Bands</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Artists / Bands'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Bands Page</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may edit band information here.</li>
					</ul>
					<div id='bands-editor' class='editor context-editor fieldset-editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<br>
						<?php $bands = json('bands'); ?>
						<?php // var_dump($home_slides); ?>
						<div class='tree'>
							<?php foreach ($bands as $item) template($item); ?>
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