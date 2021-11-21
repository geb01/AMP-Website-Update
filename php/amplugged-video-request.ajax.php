<?php 

require 'global.php';

if (isset($_POST['cache']) && $_POST['cache']) {
	die(json_encode(json('videos')));
}

if (isset($_POST['id'])) {

	$api_key = 'AIzaSyB3KD-y7QIdOeSlwrGvPjqio5ihG2JGYjc';
	$youtube_api_request = "https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics,contentDetails&key=$api_key&id=$_POST[id]";

	// perform a request to the youtube API
	$json_items = json_decode(file_get_contents($youtube_api_request), true)['items'];
	json('videos', $json_items);
	die(json_encode($json_items));

}