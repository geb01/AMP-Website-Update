<?php
	require_once 'php/global.php';
	// $sec = $target->format('U') - $now->format('U');
?>

<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(false); ?>
		<title>Ateneo Musicians' Pool - Coming Soon!</title>
		<script>
			$(document).ready(function() {
				$('section .parallax').css('opacity', 1);
				var startTime = <?php echo $sec; ?>;
				var timer = $('h5');
				function updateTime() {
					var h = parseInt(startTime / (60 * 60));
					var m = parseInt(startTime / 60) % 60;
					var s = startTime % 60;
					var time = ('0' + h).slice(-2) + ':' + ('0' + m).slice(-2) + ':' + ('0' + s).slice(-2);
					timer.html(time);
					startTime--;
					if (startTime <= 0)
						window.location.href = './';
				}
				updateTime();
				setInterval(updateTime, 1000);
			});
		</script>
		<style>
			.parallax {
				background-image: url(images/about/description.jpg);


				-webkit-flex-flow: column nowrap;
				flex-flow: column nowrap;
			}
			.wrapper {
				position: absolute;
				margin-top: auto;
				margin-bottom: auto;
				top: 50%;
				bottom: 50%;
				font-family: LeagueSpartan;
				text-shadow: 0 0 0.5em black;
			}
		</style>
	</head>
	<body>
		<div id='container' id='fullscreen'>
			<?php createHeader(''); ?>
			<div id='main-content'>
				<section>
					<div class='parallax selected'>
						<div class='wrapper'>
							<h1>Coming Soon!</h1>
							<h5></h5>
						</div>
					</div>
				</section>
			</div>
			<?php // require 'php/footer.php'; ?>
		</div>
	</body>
</html>