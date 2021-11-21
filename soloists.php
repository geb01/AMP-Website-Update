<?php

	require_once 'php/global.php';
	$roles = json('soloists');
	$page = str_replace('-', ' ', get($_GET, 'role', ''));
	$role = null;
	if (!empty($page))
		foreach ($roles as &$test)
			if (get($test, 'role') == $page) {
				$role = $test;
				break;
			}
?>

<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('dividers.css'); ?>
		<?php source('bands.css'); // for class='band-info' ?>
		<?php source('soloists.css'); ?>
		<?php if ($role !== null) source('soloists.js'); ?>
		<title>Ateneo Musicians' Pool - Bands</title>
	</head>
	<body id='fullscreen'>
		<?php createHeader('Soloists'); ?>
		<div id='container'>
			<?php
			// create the dividers
			source('dividers.php');
			if ($role === null):
				$role_map = array();
				foreach ($roles as $role)
					$role_map[] = array(
						'label' => get($role, 'role'), 
						'image' => get($role, 'image strip'), 
						'href'    => '/soloists.php?role='.propertize(get($role, 'role'))
					);
				createDividers($role_map);
				// code below won't work in PHP 5.2
				// createDividers(array_map(function($role) {
				// 	return array(
				// 		'label' => get($role, 'role'), 
				// 		'image' => get($role, 'image strip'), 
				// 		'href'    => 'soloists.php?role='.propertize(get($role, 'role'))
				// 	);
				// }, $roles));
			else: ?>
				<div class='wrapper'><div class='wrapper' id='role-wrapper'><ul id='role-list'>
				<?php foreach (get($role, 'artists') as $artist): ?>
					<li>
						<div class='role-name'><?= get($artist, 'name', '<i>&lt;no name&gt;</i>') ?></div>
						<div class='role-info band-info'><?php
							if (isset($artist['genre'])) {
								$label = 'Genres';
								if (strtolower(get($role, 'role', '')) == 'miscellaneous')
									$label = 'Instrument';
								echo "<div class='role-label'>$label: $artist[genre]</div>";
							}

							if (isset($artist['soundcloud']))
								echo "<a target='_blank' href='$artist[soundcloud]'><div class='soundcloud-com icon'></div><div>$artist[soundcloud]</div></a>";
						?></div>
					</li>
				<?php endforeach; // artists ?>
				</ul></div></div>
				<?php createDividers(array(
					array(
						'label' => get($role,'role'),
						'image' => get($role, 'image strip'),
						'href' => '/soloists.php',
					),
					array(),
					array(
						'href' => '/soloists.php',
						'image' => get($role, 'image strip'),
					)
				), 'single-role');
				endif; ?>
			</div>
		</div>
	</body>
</html>