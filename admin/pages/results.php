<?php
require_once '../../php/global.php';
createLogin();
source('dashboard.php');
$site = $protocol.$host.'/results.php';
function template($item, $id = null) { ?>
	<div class='node' <?= $id ? "id='$id'" : '' ?>>
		<div class='legend'>
			<div class='menu-button'></div>
			<img class='img uploadable' src='<?= singlequote($item) ?>'>	
		</div>	
	</div>
<?php } ?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('jquery-ui.min.js'); ?>
		<?php source('context-editor.js'); ?>
		<?php source('results.js'); ?>
		<?php source('message.js'); ?>
		<?php source('uploadable.js'); ?>
		<title>AMP Admin - Results Roster</title>
		<style>
			.img, .editor .node, .add {
				display: inline-block;
				border: 1px solid #646464;
				max-width: 300px;
				width: 100%;
			}
			.editor, .tree {
				max-width: 810px;
				border: none !important;}
		</style>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Results Roster'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Results Roster</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may add a set of images as a results roster to be shown in <code>/results.php</code>.</li>
					</ul>
					<div id='results-editor' class='editor context-editor fieldset-editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<br>
						<?php $results = json('results'); ?>
						<div class='tree'>
							<?php foreach ($results as $item) template($item); ?>
							<?php template('', 'dummy'); ?>
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