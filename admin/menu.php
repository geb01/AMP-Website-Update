<?php
	require_once '../php/global.php';
	createLogin();
	source('dashboard.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('admin.css'); ?>
		<?php source('editor.css'); ?>
		<?php source('jquery-ui.min.js'); ?>
		<?php source('editor.js'); ?>
		<?php source('context-editor.js'); ?>
		<?php source('menu.js'); ?>
		<title>AMP Admin - Menu</title>
		<style>
			#menu-editor [name='active'][disabled]::before,
			#menu-editor [name='open new tab']:not([disabled])::before {
				text-transform: none;
				font-size: 10px;
				color: #646464;
			}

			#menu-editor [name='active'][disabled]::before {
				content: "[disabled]";
			}
			#menu-editor [name='open new tab']:not([disabled])::before {
				content: "[opens in new tab]";
			}
			#menu-editor [name='active'][disabled] ~ code {
				background-color: gray;
			}
		</style>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('Admin'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Menu Editor</h1>
					<ul>
						<li>You may edit and rearrange menu items here.</li>
						<li>Absolute Links - <code>/page.php</code></li>
						<li>Relative Links - <code>page.php</code></li>
						<li>External Links - <code>http://site.com/page.php</code></li>
					</ul>
					<div id='menu-editor' class='editor context-editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<br>
						<div class='tree'>
							<?php $menu = json('menu'); ?>
							<?php foreach ($menu as &$item): ?>
							<div class='node'>
								<div class='legend pad'>
									<span name='label'><?php echo get($item, 'label'); ?></span>
									<span name='active' <?php if (!get($item, 'active')) echo 'disabled'; ?>></span>
									<span name='open new tab' <?php if (!get($item, 'open new tab')) echo 'disabled'; ?>></span>
									<code name='href'><?php echo get($item, 'href'); ?></code>
									<div class='menu-button'></div>
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