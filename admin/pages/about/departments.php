<?php
	require_once '../../../php/global.php';
	createLogin();
	source('dashboard.php');
	$site = $protocol.$host.'/about.php#departments';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('about.css'); ?>
		<?php source('jquery-ui.min.js'); ?>
		<?php source('context-editor.js'); ?>
		<?php source('uploadable.js'); ?>
		<?php source('departments.js'); ?>
		<?php source('message.js'); ?>
		<title>AMP Admin - Departments</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('About / Departments'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Departments</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may add and edit departments here.</li>
					</ul>
					<div id='dep-editor' class='editor context-editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<br>
						<?php $departments = json('departments'); ?>
						<div class='tree'>
							<?php foreach ($departments as &$item): ?>
							<div class='node'>
								<div class='legend'>
									<div class='menu-button'></div>
									<img class='img uploadable' name='image' src='<?php echo get($item, 'image'); ?>'>
									<span name='name'><?php echo get($item, 'name'); ?></span>
									<span name='description'><?php echo get($item, 'description'); ?></span>
									<?php
										$officers = array();
										foreach (get($item, 'officers', array()) as $officer)
											if (isset($officer['name']))
												$officers[] = $officer['name'];
										// code below won't work in PHP 5.2
										// $officers = array_map(function($officer) {return $officer['name'];}, get($item, 'officers', array()));
									?>
									<span name='officers'><?php echo implode(' / ', $officers); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
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