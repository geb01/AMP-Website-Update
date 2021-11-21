<?php
require_once '../../../php/global.php';
createLogin();
source('dashboard.php');
$site = $protocol.$host.'/soloists.php';
function template($item, $id = null) {
	$members = get($item, 'artists', array()); ?>
	<div class='node' <?= $id ? "id='$id'" : '' ?>>
		<div class='legend'>
			<div class='menu-button'></div>
			<table class='fieldset-table' cellspacing='10'><tr>
				<td>Role</td>
				<td><input type='text' class='entry' name='role' value='<?= singlequote(get($item, 'role')) ?>'></td>
			</tr><tr>
				<td>Image Strip</td>
				<td>
					<input type='button' value='Upload image' class='img-upload-btn'>
					<img class='img uploadable' name='image strip' src='<?= get($item, 'image strip') ?>'>
				</td>
			</tr><tr>
				<td>Members</td>
				<td class='members'>
				<?php foreach ($members as $member): ?>
					<input type='text' name='member-name' title='member name' value='<?= singlequote(get($member, 'name', '')) ?>'/>
					<input type='text' name='member-genre' title='genre/instrument' value='<?= singlequote(get($member, 'genre', '')) ?>'/>
					<img src='/images/icons/soundcloud-icon.png' height='32' style='float:left'>
					<input type='text' name='member-soundcloud' title='soundcloud' value='<?= singlequote(str_replace('https://soundcloud.com/', '', get($member, 'soundcloud', ''))) ?>'/>
					<div class='delete-btn'>x</div>
					<br>
				<?php endforeach; // members ?>
					<div class='member-add button'>Add New</div>
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
		<?php source('soloists.js'); ?>
		<?php source('message.js'); ?>
		<title>AMP Admin - Soloists</title>
		<style>
			.members {
				display: flex;
				flex-flow: row wrap;
			}
			#main-content {
				min-width: 750px !important;
			}
			.members input {
				float: none;
			}
			.members [name=member-name] {
				width: 25%;
			}
			.members [name=member-genre] {
				width: 25%;
			}
			.members [name=member-soundcloud] {
				width: 25%;
				background-color: #FF5516;
				border-color: #FF5516;
				border-style: groove;
				color: white;
				outline: none;
			}
			.members [name=member-soundcloud]:focus {
				border-style: inset;
			}
		</style>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Artists / Soloists'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Soloists Page</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may edit soloists information here.</li>
					</ul>
					<div id='soloists-editor' class='editor context-editor fieldset-editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<br>
						<?php $soloists = json('soloists'); ?>
						<div class='tree'>
							<?php foreach ($soloists as $item) template($item); ?>
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