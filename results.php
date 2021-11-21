<?php require_once 'php/global.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<title>Ateneo Musicians' Pool - 2015 Accepted Applicants</title>
		<style type='text/css'>
			#container {
				width: 800px;
				margin: auto;
			}
			section {
				width: 100%;
				height: auto;
				max-height: auto;
				min-height: auto;
			}
			section img {
				width: 100%;
			}
		</style>
	</head>
	<body id='fullscreen'>
		<?php createHeader('Recital Results'); ?>
		<div id='container'>
			<?php foreach (json('results') as $image): ?>
			<section>
				<div class='wrapper'>
					<img src='<?php echo $image; ?>'>
				</div>
			</section>
			<?php endforeach; ?>
		</div>
	</body>
</html>