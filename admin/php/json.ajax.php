<?php
	// writes into json file using AJAX queries from admin
	require_once '../../php/global.php';
	if (authenticated() && isset($_POST['json']) && (isset($_POST['data']) || isset($_POST['datastr']))) {
		try {
			$filename = $_POST['json'];
			$data = isset($_POST['data']) ? $_POST['data'] : json_decode(stripslashes($_POST['datastr']), true);
			if (isset($_POST['inplace']) && $_POST['inplace'] == 'true') {
				// write inplace
				$json = json($filename);
				foreach ($data as $key => $value)
					$json[$key] = str_replace("\n", "\\n", $value);
				json($filename, $json);
			} else {
				// replace everything
				json($filename, $data);
			}
		} catch (Exception $ex) {
			die('ERROR');
		}
	} else {
		die('ERROR');
	}
?>