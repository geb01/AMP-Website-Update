<?php
	
	$minify = $_SERVER['HTTP_HOST'] != 'localhost';
	$minify = false;
	define('FILENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));

	/**
	 * Checks if needle is string is a prefix of the other string.
	 * @param string &$haystack 
	 * @param string $needle 
	 * @return string
	 */
	function starts_with($haystack, $needle) {
		if (strlen($haystack) < strlen($needle))
			return false;
		return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
	}

	/**
	 * Checks if needle is string is a suffix of the other string.
	 * @param string &$haystack 
	 * @param string $needle 
	 * @return string
	 */
	function ends_with($haystack, $needle) {
		$hlen = strlen($haystack);
		$nlen = strlen($needle);
		if ($hlen < $nlen) return false;
		return substr_compare($haystack, $needle, $hlen - $nlen) === 0;
	}

	/**
	 * Prepends single-quotes with a &apos;
	 * @param string $text
	 * @return string
	 */
	function singlequote($text) {
		return str_replace("'", '&apos;', $text);
	}

	/**
	 * Saves or reads JSON file in the /json directory.
	 * @param string $filename the JSON file name that will be searched in the /json directory. will automatically append the .json extension.
	 * @param string &$data data to put to the JSON file. if not specified, this function will return the contents of the json file.
	 * @return string the encoded JSON string if data is not null
		 * @return type the JSON object if the data is null
	 */
	function json($filename, $data = null) {
		$prefix = $_SERVER['DOCUMENT_ROOT'];
		if ($prefix[strlen($prefix) - 1] != '/')
		$filename = "./json/$filename.json";
		if (!is_null($data)) {
			$encoded = json_encode($data);
			file_put_contents($filename, $encoded);
			return $encoded;
		}
		else if (file_exists($filename))
			return json_decode(file_get_contents($filename), true);
		else
			return array();
	}

	/**
	 * Forcefully redirects current page to the designated url using javascript.
	 * @param string $link
	 * 		The URL of the page to be redirected to.
	 * @return void
	 */
	function redirect($link) {
		if ($link != '' && strpos($link, 'http') === false)
			while (!file_exists($link))
				$link = '../'.$link;
		die ('<script> window.location.href = "'.$link.'"</script>');
	}

	/**
	 * References to a source code.
	 * @param string $link 
	 * @return void
	 * Possible extensions: *.(php|css|js).
	 * Automatically prepends the address with the extension (e.g. "file.php" => "php/file.php").
	 * Iteratively searches for the nearest uncle in the file directory.
	 */
	function source($link) {
		$ext = pathinfo($link, PATHINFO_EXTENSION);
		global $minify;
		if ($minify && $ext == 'js' && strpos($link, '.min') === false)
			$link = str_replace('.js', '.min.js', $link);
		if (strrpos($link, 'http', -strlen($link)) === false) {
			$link = "$ext/$link";
			for ($limit = 100, $prefix = ''; $limit > 0; --$limit, $prefix = "../$prefix") {
				if (file_exists($prefix.$link)) {
					$link = $prefix.$link;
					break;
				}
			}
			if ($limit == 0) $link = '';
		}
		if ($ext === 'css') { ?>
			<link rel='stylesheet' type='text/css' href='<?php echo $link; ?>'>
		<?php } else if ($ext === 'js') { ?>
			<script src='<?php echo $link; ?>'></script>
		<?php } else if ($ext === 'php') {
			require_once $link;
		}
	}

	$auth_key_cache = null;
	/**
	 * Checks if password is the same as the authentication key in the database.
	 * @param string $password 
	 * @return boolean
	 */
	function validPassword($password) {
		// check for cache
		global $auth_key_cache;
		if (!is_null($auth_key_cache))
			return $password == $auth_key_cache;

		// connect to database and acquire auth_key
		source('sql.php');
		$query = database()->prepare("SELECT description FROM about WHERE header='auth_key' LIMIT 1");
		$query->execute();
		$auth_key_cache = $query->fetchColumn();

		return $password == $auth_key_cache;
	}

	$auth_cache = null;
	/**
	 * Checks if current session is authenticated.
	 * @return boolean
	 */
	function authenticated() {
		// check for cache
		global $auth_cache;
		if (!is_null($auth_cache)) return $auth_cache;

		// start the session
		// @see http://stackoverflow.com/questions/6249707/check-if-php-session-has-already-started
		if(session_id() == PHP_SESSION_NONE)
			session_start();

		// check if a new password was inputted
		if (isset($_POST['password'])) {
			$_SESSION['auth_key'] = md5($_POST['password']);
			// redirect to self to prevent form resubmission
			redirect('');
		}

		// check if logout is requested
		if (isset($_GET['logout']))
			unset($_SESSION['auth_key']);

		return $auth_cache = (isset($_SESSION['auth_key']) ? validPassword($_SESSION['auth_key']) : false);
	}

	/**
	 * Returns file name of jquery file used.
	 * @return string
	 * @version 2.1.4
	 */
	function jquery() {
		// $online = 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js';
		return 'jquery-2.1.4.min.js';
	}

	/**
	 * Sets up metadata and default links for the <head> tag
	 * @return void
	 */
	function globalLinks() { ?>
		<meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<!--<meta name='viewport' content='maximum-scale=0.5, initial-scale=1'>-->
		<meta name='viewport' content='maximum-scale=1, initial-scale=1'>
		<meta property="og:image" content="./images/icons/logo-black.png">
		<meta property="og:title" content="Ateneo Musicians' Pool">
		<meta property="og:description" content="The Ateneo Musicians’ Pool is the premier music organization of Ateneo. Originally formed for the fair treatment of Ateneo bands, it has grown into an organization which hones the skills of its members in music and other related aspects such as music production and events management. Through our member development and different projects and events, we aim to show that music is an essential part of the human experience.">
		<link rel="shortcut icon" href="./images/icons/logo.ico" type="image/x-icon">
		<link rel="icon" href="./images/icons/logo.ico" type="image/x-icon">
		<link rel="stylesheet" href="./css/global.css"/>
		<link rel="stylesheet" href="./css/header.css"/>
		<?php // source('mobile.css'); ?>
		<script src="./js/jquery-2.1.4.min.js"></script>
		<script src="./js/global.js"></script>
	<?php }

	/**
	 * Prints menu item information of the menu item along with its submenus in pre-order traversal.
	 * @param array &$menu reference to the array containing the menu items
	 * @param bool $submenu marks whether current node is root or not
	 * @return void
	 */
	function extractMenu(&$menu, $root = true) {
		foreach ($menu as &$item) {
			$label = get($item, 'label', '&nbsp;');
			$href = get($item, 'active') && get($item, 'href') ? "href='$item[href]'" : "";
			$target = get($item, 'open new tab') ? 'target=_blank' : '';
			// output menu item template
			echo "<div class='tree'>";
			echo "<div class='node'><a $href $target>$label</a></div>";
			if (get($item, 'submenu')) {
				echo "<div class='subtree'>";
				extractMenu($item['submenu'], false);
				echo '</div>';
			}
			echo '</div>';
		}
		if ($root && authenticated())
			echo "<div id='admin-menu-item' class='node'><a href='/admin'>admin</a></div>";
	}

	/**
	 * Creates a header: menu button, the amp logo, and the search bar.
	 * @param string $menuLabel text to be displayed next to the menu button
	 * @return void
	 */
	function createHeader($menuLabel) { ?>
		<?php source('sql.php'); ?>
		<base href='/'>
		<header>
			<div class='menu-button'></div>
			<div id='menu' class='hidden'><?php
				$menu = json('menu');
				extractMenu($menu);
			?></div>
			<div id='menu-label'><?php echo $menuLabel; ?></div>
			<a class='amp-logo' href='/index.php' title='Home'></a>
			<base href=''>
			<div id='widget-container'></div>
			<base href='/'>
		</header>
	<?php }

	function createFooter($menuLabel) { ?>
		<base href='/'>
		<footer class='footer'>
			<div id='footer-left'>
				<img src='/images/icons/footer_logo.png'/>
			</div>
			<div id = 'footer-center'>
				<ul>
					<li><a href = "https://www.facebook.com/ateneomusicianspool" target = "_blank"><img src= "/images/icons/facebook-icon.png"></a></li>		
					<li><a href = "https://www.twitter.com/AMPAteneo" target = "_blank"><img src= "/images/icons/twitter-icon.png"></a></li>
					<li><a href = "https://www.instagram.com/ateneomusicianspool" target = "_blank"><img src= "/images/icons/instagram-icon.png"></a></li>
					<li><a href = "https://www.behance.net/AMPAteneo" target = "_blank"><img src= "/images/icons/behance-icon.png"></a></li>
					<li><a href = "mailto: amp.ls@obf.ateneo.edu" target = "_blank"><img src= "/images/icons/gmail-icon.png"></a></li>
				</ul>
			</div>
			<div id='footer-right'>
				<p>Ateneo de Manila University<p>
				<p>ateneomusicianspool@gmail.com<p>
			</div>
		</footer>
	<?php }

	/**
	 * Convenient method for array key-value acquisition.
	 * @param array $array input array
	 * @param type $key
	 * @param type $default (optional) the default result when the key is not found
	 * @return type
	 */
	function get($array, $key, $default = NULL) {
		return isset($array[$key]) ? $array[$key] : $default;
	}

	/**
	 * Convenient method for normalizing HTML attributes. replaces ' ' with '-' and removes all single quotes.
	 * @param string $text text you want to normalize 
	 * @return string
	 */
	function propertize($text) {
		return str_replace(array(' ', "'"), array('-', ''), strtolower($text));
	}

	/**
	 * Wraps and replaces line breaks with HTML paragraph tags.
	 * @param type $text text you want to transform into an html paragraph
	 * @return type
	 */
	function paragraph($text) {
		if ($text === null || $text === '') return '';
		return '<p>'.str_replace('\n', '</p><p>', $text).'</p>';
	}

	/**
	 * Parses a file into a multi-dimensional array depending oon tab structure.
	 * @param string $filename file to be parsed
	 * @return array
	 */
	function parseFile($filename) {
		$lines = array();
		try {$lines = file($filename);}
		catch (Exception $e) {return null;}
		$nameStack = array();
		$arrayStack = array();
		$tabStack = array();
		
		foreach ($lines as $line) {
			$rep = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', str_replace('ñ', '&ntilde;', $line)); // remove spec characters
			if (strlen($rep) == 0) continue; // ignore empty line

			$trim = ltrim($line, '	'); // trim tabs
			$tabCount = strlen($line) - strlen($trim);
			$trim = $rep; // trim remaining special characters

			while (count($tabStack) > 0 && $tabStack[count($tabStack) - 1] > $tabCount) {
				if (count($arrayStack[count($arrayStack) - 1]) > 0) {
					
					if (end($arrayStack[count($arrayStack) - 1]) == array()) { // insert name only, not a mapping
						array_push($arrayStack[count($arrayStack) - 2][count($arrayStack[count($arrayStack) - 2]) - 1], array_pop($nameStack[count($nameStack) - 1]));
						array_pop($arrayStack[count($arrayStack) - 1]);
					}
					
					else  // insert mapping name => array

						$arrayStack[count($arrayStack) - 2][count($arrayStack[count($arrayStack) - 2]) - 1][array_pop($nameStack[count($nameStack) - 1])] = array_pop($arrayStack[count($arrayStack) - 1]);
				
				} else {
					array_pop($nameStack);
					array_pop($arrayStack);
					array_pop($tabStack);
				}
			}

			if (count($tabStack) == 0 || $tabStack[count($tabStack) - 1] < $tabCount) {
				array_push($nameStack, array($trim));
				array_push($arrayStack, array(array()));
				array_push($tabStack, $tabCount);
			} else { // if ($tabStack[count($tabStack) - 1] == $tabCount) {
				array_push($nameStack[count($nameStack) - 1], $trim);
				array_push($arrayStack[count($arrayStack) - 1], array());
			}
		}

		
		while (count($tabStack) > 1) {
			if (count($arrayStack[count($arrayStack) - 1]) > 0) {
				
				if (end($arrayStack[count($arrayStack) - 1]) == array()) { // insert name only, not a mapping
					array_push($arrayStack[count($arrayStack) - 2][count($arrayStack[count($arrayStack) - 2]) - 1], array_pop($nameStack[count($nameStack) - 1]));
					array_pop($arrayStack[count($arrayStack) - 1]);
				}
				
				else  // insert mapping name => array

					$arrayStack[count($arrayStack) - 2][count($arrayStack[count($arrayStack) - 2]) - 1][array_pop($nameStack[count($nameStack) - 1])] = array_pop($arrayStack[count($arrayStack) - 1]);
			} else {
				array_pop($nameStack);
				array_pop($arrayStack);
				array_pop($tabStack);
			}
		}
		
		if (count($arrayStack) == 0)
			return array();

		$ans = array();
		for ($i = 0; $i < count($arrayStack[0]); ++$i) {
			$ans[$nameStack[0][$i]] = $arrayStack[0][$i];
		}

		return $ans;
	}

	/**
	 * Creates a default login interface for pages that need authentication.
	 * @return void
	 */
	function createLogin() {
		if (!authenticated()) { ?>
			<!DOCTYPE html>
			<html>
				<head>
					<?php globalLinks(); ?>
					<?php source('login.css'); ?>
					<title>Ateneo Musicians' Pool - Login</title>
				</head>
				<body id='fullscreen'>
					<?php createHeader('Login'); ?>
					<div id='container'>
						<section class='fixed amp-bg'>
							<div class='wrapper' id='login-form'>
								<form action='/admin/index.php?login=1' method='post'>
									Password: &nbsp;
									<input type='password' name='password' autofocus>
									</input>
									<input type='submit' value='Log In'></input>
									<?php
										if (isset($_GET['logout']))
											echo "<p style='color:green;'>Successfully logged out.</p>";
										else if (isset($_SESSION['auth_key']))
											echo "<p style='color:red;'>Invalid password.</p>";
									?>
								</form>
							</div>
						</section>
					</div>
				</body>
			</html>
		<?php exit; } ?>
	<?php }

	// rename URL
	$host = $_SERVER['HTTP_HOST'];
	$uri = $_SERVER['REQUEST_URI'];
	$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
	$changed = false;

	// remove www
	if (starts_with($host, 'www.')) {
		$changed = true;
		$host = substr($url, 4);
	}
	// redirect test subdomain
	if (starts_with($uri, '/test')) {
		$changed = true;
		$host = "test.$host";
		$uri = substr($uri, 5);
	}
	// remove index.php
	$len = strlen($uri);
	$uri = str_replace('index.php', '', $uri);
	if ($len !== strlen($uri))
		$changed = true;

	// remove index.php
	if ($changed) {
		// authenticate first before rename
		authenticated();
		redirect("$protocol$host$uri");
	}
	if (starts_with($host, 'test.')) createLogin();

?>
