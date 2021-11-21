<?php
	require_once 'php/global.php';
	$slides = json('home');
?>

<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<meta name="google-site-verification" content="-CgO_A5vd99txcEHdkVG2B9TALekowaM2SY5CCiSZAI">
		<?php source('index.css'); ?>
		<title>Ateneo Musicians' Pool | Official Website</title>
	</head>
	<body id='fullscreen'>
		<?php createHeader('Home'); ?>
		<div id='home'>
			<h1>Bigger than the sound</h1>
		</div>
	</body>
	<?php createFooter('Home'); ?>
</html>