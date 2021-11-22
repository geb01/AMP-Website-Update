<?php
	require_once 'php/global.php';
	$meta = json('meta');

	// derive the year level of eb member from the current date and date of enrollment
	function year_level($year) {
		$month = 5;
		$year_now = (int) date('Y');
		$month_now = (int) date('M');
		$months_diff = $year_now * 12 + $month_now - $year * 12 - $month;
		return (int) ($months_diff / 12) + 1;
		// $year_diff = date_diff(date_create('now'), date_create($year.'-05-01'));
		// $months = intval($year_diff->format('%m'));
		// $years = intval($year_diff->format('%y'));
		// return $years + ($months % 12 !== 0 ? 1 : 0);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('about.css'); ?>
		<title>Ateneo Musicians' Pool - About</title>
		<script>
			$(document).ready(function() {
				var d = $('#departments h1');
				$(window).scroll(function() {
					if ($(this).scrollTop() + 50 >= d.offset().top)
						d.addClass('sliding');
					else
						d.removeClass('sliding');
				});
			});
		</script>
	</head>
	<body id='fullscreen'>
		<section class='fixed amp-bg' src='<?= get($meta, 'about-bg') ?>'></section>
		<?php createHeader('About'); ?>
		<div id='container'>
			<!-- first child section for fixed background -->
			<section id='description'>
				<div class='about'>
					<div class='wrapper'>
						<div class='wrapper'>
							<h1><?php echo get($meta, 'about-heading', 'We Are Amp.'); ?></h1>
							<?php echo paragraph(get($meta, 'about-description')); ?>
						</div>
					</div>
					<div class='about' id='mission-vision'>
						<div class='wrapper'>
							<?php if (isset($meta['mission'])): ?>
							<div id='mission' class='hover-float'>
								<h1>Mission</h1>
								<?php echo paragraph($meta['mission']); ?>
							</div>
							<?php endif; ?>
							<?php if (isset($meta['vision'])): ?>
							<div id='vision' class='hover-float'>
								<h1>Vision</h1>
								<?php echo paragraph($meta['vision']); ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
			<section class='transition' id='executive-board'>
				<div class='wrapper'>
					<h1 class='dropdown-button'>The Executive Board<div class='arrow'></div></h1>
					<p class='hidden-dropdown'>
						<?php echo $meta['executive board']; ?>
					</p>
				</div>
				<img src='<?php echo $meta['eb group photo']; ?>'>
			</section>
			<section class='feature'>
				<div class='feature-labels'><?php
					$eb = json('executive board');
					$id = 0;
					foreach ($eb as &$executive) { ?>
						<div index=<?php echo $id++; ?>>
							<div class='label'><?php echo $executive['position']; ?></div>
							<div class='arrow right'></div>
						</div>
					<?php } ?>
				</div>
				<div class='feature-wrapper'><?php
					$id = 0;
					foreach ($eb as &$executive) {
						$image = $executive['image'];
						if ($image) $image = "src='$image'"; ?>
						<div class='feature-display' index='<?php echo $id++; ?>'>
							<img class='feature-pic' <?php echo $image; ?>>
							<div class='feature-description'>
								<div><?php $member = &$executive['member']; ?>
									<h1><?php echo $member['name']; ?></h1>
									<h2><?php echo year_level($member['year']).' '.$member['course']; ?></h2>
									<?php echo paragraph($executive['description']); ?>
								</div>
							</div>
						</div>
					<?php } unset($eb); ?>
				</div>
			</section>
			<section class='transition' id='departments'>
				<div class='wrapper'>
					<h1>Departments</h1>
					<!--
					<div class='arrow down dropdown-button'></div>
					<p class='hidden-dropdown'>
					</p>-->
				</div>
			</section>
			<section class='feature'>
				<div class='feature-labels'><?php
					$department = json('departments');
					$id = 0;
					foreach ($department as &$dep) { ?>
						<div index='<?php echo $id++; ?>'>
							<div class='label'><?php echo $dep['name']; ?></div>
							<div class='arrow right'></div>
						</div>
					<?php } ?>
				</div>
				<div class='feature-wrapper'><?php
					$id = 0;
					foreach ($department as &$dep) {
						$image = $dep['image'];
						if ($image) $image = "src='$image'"; ?>
						<div class='feature-display' index='<?php echo $id++; ?>'>
							<img class='feature-pic' <?php echo $image; ?>>
							<div class='feature-description'>
								<div><?php
									$officers = array();
									foreach (get($dep, 'officers', array()) as $officer)
										if (isset($officer['name']))
											$officers[] = $officer['name'];
									// code below won't work in PHP 5.2
									// $officers = array_map(function($officer) {return $officer['name'];}, $dep['officers']);
									echo "<h1>$dep[name]</h1>";
									echo "<h2>".implode(' / ', $officers)."</h2>";
									echo paragraph($dep['description']);
								?></div>
							</div>
						</div>
					<?php } ?>
				</div>
			</section>
			<?php 
				/* createDividers([
					['Bands', 'images/bands.jpg', 'artists.php'],
					['Soloists', 'images/soloists.jpg', 'soloists.php']
				], 'roster');
				// */
			?>
		</div>
	</body>
</html>