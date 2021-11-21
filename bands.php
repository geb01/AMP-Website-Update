<?php
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);
	require_once 'php/global.php';
	$bands = json('bands');
?>

<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<link rel='stylesheet' type='text/css' href='css/dividers.css'>
		<link rel='stylesheet' type='text/css' href='css/bands.css'>
		<title>Ateneo Musicians' Pool - Bands</title>
	</head>
	<body id='fullscreen'>
		<?php createHeader('Bands'); ?>
		<div id='container'>
			<?php
			// create the dividers
			source('dividers.php');
			$dividers = array();

			foreach ($bands as &$band)
				$dividers[] = array(
					'label' => get($band, 'name'),
					'image' => get($band, 'image strip'),
					'href'  => 'bands.php#'.propertize(get($band, 'name'))
				);

			createDividers($dividers, 'bands');
			
			// create the main bands
			foreach ($bands as &$band): ?>
				<section class='band' id='<?php echo propertize(get($band, 'name')); ?>'>
					<?php if (!get($band, 'image')) { ?>
					<div class='band-left'>
					<?php } else { ?>
					<div class='band-left band-image'>
						<div><img class='band-image' src='<?php echo $band['image']; ?>'></div>
					<?php } ?>
						<div class='band-description'>
							<h1><?php echo get($band, 'name'); ?></h1>
							<?php echo paragraph(get($band, 'description')); ?>
						</div>
					</div>
					<div class='band-right'>
						<div><?php
							$members = get($band, 'members');
							$links = get($band, 'links');
							$contacts = get($band, 'contact');
							if ($members) { ?>
								<div>
									<p class='label'>MEMBERS:</p>
									<div class='band-members'>
										<div><?php
											echo implode('<br>', array_keys($members));
											echo '</div><div>';
											$role_list = array();
											foreach (array_values($members) as $roles)
												$role_list[] = implode('/', $roles);
											echo implode('<br>', $role_list);
											// code below won't work for PHP 5.2
											// echo implode('<br>', array_map(function($roles) {return implode('/', $roles);}, array_values($members)));
										?></div>
									</div>
								</div>
							<?php } ?>
							<?php if ($links) { ?>
								<div>
									<p class='label'>LINKS:</p>
									<div class='band-info'>
										<?php foreach ($links as &$link) { ?>
											<a href='<?php echo $link; ?>' target='_blank'><?php
												$domain = parse_url($link, PHP_URL_HOST);
												if (starts_with($domain, 'www.'))
													$domain = substr($domain, 4);
												$icon = str_replace('.', '-', $domain);
												echo "<div class='$icon icon'></div>";
												echo "<div>$link</div>";
											?></a>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<?php if ($contacts) { ?>
								<div>
									<p class='label'>CONTACT:</p>
									<div class='band-info'><?php
										foreach ($contacts as &$contact)
											echo "<span><div></div><div>$contact</div></span>";
									?></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</section>
			<?php endforeach; ?>
		</div>
	</body>
</html>