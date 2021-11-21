<?php
	require_once '../../php/global.php';
	createLogin();
	source('dashboard.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<title>AMP Admin - Pages</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Admin'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Pages</h1>
					<ul>
						<li>There is nothing to do here.</li>
					</ul>
				</div>
			</div>
			<?php createDashboard(); ?>
		</div>
	</body>
</html>