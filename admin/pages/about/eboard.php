<?php
	require_once '../../../php/global.php';
	createLogin();
	source('dashboard.php');
	$site = $protocol.$host.'/about.php#executive-board';
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
		<?php source('eboard.js'); ?>
		<?php source('message.js'); ?>
		<style>
			#eb-group-photo {
				max-width: 500px;
				width: 100%;
				cursor: pointer;
			}
		</style>
		<title>AMP Admin - Executive Board</title>
	</head>
	<body id='holy-grail'>
		<section class='fixed amp-bg' src='<?= get(json('meta'), 'admin-bg') ?>'></section>
		<?php createHeader('About / Executive Board'); ?>
		<div id='container'>
			<div id='main-content'>
				<div>
					<h1>Executive Board</h1>
					<ul>
						<li>This page can be found in <a href='<?= $site ?>'><code><?= $site ?></code></a>.</li>
						<li>You may add and edit E-Board members here.</li>
						<li>Note that the enrollment year should be valid in order for the complete course name to be displayed properly.</li>
					</ul>
					<fieldset id='eb-info-editor' class='editor fieldset-editor'>
						<legend>Information</legend>
						<div><?php $meta = json('meta'); ?>
							<table class='fieldset-table' cellspacing='10'><tr>
								<td>E-board Description</td>
								<td><textarea type='text' class='entry' id='eb-description'><?php echo get($meta, 'executive board'); ?></textarea><td>
							</tr><tr>
								<td>Group Photo</td>
								<td><img id='eb-group-photo' class='img uploadable' name='image' src='<?php echo get($meta, 'eb group photo'); ?>'><td>
							</tr></table>
							<div class='save button' disabled>Save Information</div>
							<div class='reset button' disabled>Reset</div>
							<span class='message'></span>
						</div>
					</fieldset>
					<h2>Executive Board Members</h2>
					<div id='eb-editor' class='editor context-editor'>
						<div class='save button' disabled>Save Changes</div>
						<div class='reset button' disabled>Reset</div>
						<br>
						<?php $eboard = json('executive board'); ?>
						<div class='tree'>
							<?php foreach ($eboard as &$item): ?>
							<div class='node'>
								<div class='legend'>
									<div class='menu-button'></div>
									<img class='img uploadable' name='image' src='<?php echo get($item, 'image'); ?>'>
									<?php $member = get($item, 'member', array()); ?>
									<span name='position'><?php echo get($item, 'position'); ?></span>
									<span name='description'><?php echo get($item, 'description'); ?></span>
									<div class='member-wrapper'>
										<div name='member-name'><?php echo get($member, 'name'); ?></div>
										<div name='member-year'><?php echo get($member, 'year'); ?></div>
										<div name='member-course'><?php echo get($member, 'course'); ?></div>
									</div>
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