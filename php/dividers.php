<?php

	/**
	 * Creates vertical dividers with clickable background images and centered labels.
	 * @param array $data array of tuples corresponding to a label and path to image
	 * @param string $name the id attribute for the divider section
	 * @return void
	 */

	function createDividers($data, $name = null) { ?>
		<section class='divider-container' <?php echo is_null($name) ? '' : "id='$name'"; ?>>
		<?php
			foreach($data as $info) {
				$label = strtoupper(get($info, 'label', ''));
				$image = get($info, 'image', '');
				$href = get($info, 'href', '');
				$id = get($info, 'id', '');
				if (!empty($image)) $image = "src='$image'";
				if (!empty($href)) $href = "href='$href'";
				if (!empty($id)) $id = "id='$id'";
				echo "<a class='divider' $href $id $image><p>$label</p></a>";
		} ?>
			<div class='arrow left scroller'></div>
			<div class='left shadow'></div>
			<div class='arrow right scroller'></div>
			<div class='right shadow'></div>
		</section>
	<?php } ?>