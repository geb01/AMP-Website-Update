<?php
	require_once 'php/global.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<title>Ateneo Musicians' Pool - Contact Us</title>
		<?php source('bands.css'); ?>
		<?php source('contact.css'); ?>
	</head>
	<body id='fullscreen'>
		<?php createHeader('Contact Us'); ?>
		<div id='container'>
			<section class='amp-bg fixed' src='<?= get(json('meta'), 'contact-bg', '') ?>'></section>
			<section>
				<div id='contact-frame' class='wrapper'>
					<h1>Ateneo Musicians' Pool</h1><br>
					<div id='contact-info'><?php $contact = json('meta'); ?>
						<div class='wrapper'>
							<a href='https://facebook.com/<?= get($contact, 'contact-facebook') ?>' target='_blank' id='contact-fb'>
								<div class='facebook-com icon'></div>
								<span><?= get($contact, 'contact-facebook') ?></span>
							</a>
							<a href='https://twitter.com/<?= get($contact, 'contact-twitter') ?>' target='_blank' id='contact-twitter'>
								<div class='twitter-com icon'></div>
								<span><?= get($contact, 'contact-twitter') ?></span>
							</a>
							<a href='mailto:<?= get($contact, 'contact-email') ?>' id='contact-email'>
								<div class='gmail-com icon'></div>
								<span><?= get($contact, 'contact-email') ?></span>
							</a>
						</div>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>