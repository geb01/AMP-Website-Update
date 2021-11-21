<?php
require_once 'php/global.php';
source('dividers.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('dividers.css'); ?>
		<title>Artists - AMP</title>
		<style>.divider-container .arrow {display: none !important;}</style>
	</head>
	<body id='fullscreen'>
		<?php createHeader('Artists'); ?>
		<div id='container'><?php
			$images = json('artists');
			createDividers(array(
				array(
					'label' => 'bands',
					'image' => get($images, 'bands'),
					'href' => '/bands.php'
				),
				array(
					'label' => 'soloists',
					'image' => get($images, 'soloists'),
					'href' => '/soloists.php'
				)
			));
		?></div>
	</body>
</html>