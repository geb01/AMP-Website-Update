<?php
require_once '../../../php/global.php';
createLogin();
source('dashboard.php');
source('dividers.php');
$site = $protocol.$host.'/artists.php';
$images = json('artists');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('dividers.css'); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php // source('context-editor.js'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('artists.js'); ?>
		<?php source('message.js'); ?>
		<style>
		.editor {position: relative; width: 90%; max-width: 800px;}
		.fieldset-table {text-align: center; width: 100%; position: relative;}
		.img {max-width: 400px; width: 100%; cursor: pointer;}
		</style>
		<title>AMP Admin - Artists</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Artists'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Artists Page</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may edit the divider images in the artists page here.</li>
					</ul>
					<div id='artists-editor' class='editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<table class='fieldset-table'>
							<tr>
								<td>Bands</td>
								<td>Soloists</td>
							</tr><tr>
								<td><img id='bands-img' class='img uploadable' src='<?= get($images, 'bands') ?>'></td>
								<td><img id='soloists-img' class='img uploadable' src='<?= get($images, 'soloists') ?>'></td>
							</tr>
						</table>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
					</div>
				</div>
			</div>
			<?php createDashboard(); ?>
		</div>
	</body>
</html>