<?php

require_once 'php/global.php';

/**
 * Fetches the amplugged videos from the Youtube database.
 * Important information are stored in an array of objects.
 *
 * @return array
 *	object {
 *		kind		=> 'youtube#video',
 *		etag		=> string,
 *  	id			=> string,
 *		statistics	=> object {
 *			viewCount		=> string,
 *			likeCount		=> string,
 *			dislikeCount	=> string,
 *			favoriteCount	=> string,
 *			commentCount	=> string
 *		},
 *  	contentDetails	=> object {
 *			duration		=> string, // PT<minutes>M<seconds>S
 *			dimension		=> string,
 *			definition		=> string,
 *			caption			=> string,
 *			licensedContent	=> string
 *   	},
 *		snippet			=> object {
 *	 		publishedAt		=> string,
 *	 		channelId		=> string,
 *	 		title			=> string,
 *	 		description		=> string,
 *	 		channelTitle	=> string,
 *	 		categoryId		=> string,
 *	 		thumbnails		=> object {
 *				default 	=> object {
 *					url			=> string
 *					width		=> int
 *					height		=> int
 *				},
 *				medium		=> object {
 *					url			=> string
 *					width		=> int
 *					height		=> int
 *				},
 *				high		=> object {
 *					url			=> string
 *					width		=> int
 *					height		=> int
 *				},
 *				standard	=> object {
 *					url			=> string
 *					width		=> int
 *					height		=> int
 *				},
 *				maxres		=> object {
 *					url			=> string
 *					width		=> int
 *					height		=> int
 *				}
 *	 		},
 *	 		liveBroadcastContent	=> string,
 *	 		localized				=> object {
 *				title			=> string,
 *				description		=> string
 *	 		}
 *	 	}
 *	}
 * 
 * 
 */
function amplugged_videos() {


	// api information
	$api_key = 'AIzaSyB3KD-y7QIdOeSlwrGvPjqio5ihG2JGYjc';
	$max_results = 50; // allowed: 1-50 pings per request; this script will fetch ALL videos anyway
	$playlist_id = 
			// 'PLN6PnhvDvv4_XIOoxmKcBLlLzYCa203DO'; // 2016 amplugged
				'PLR2k5ELr5FgVJ-dXsvbnkoHeAN3kOVNB_'; // 2015 amplugged


	// generate request parameters
	$playlist_request = "https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&playlistId=$playlist_id&key=$api_key&maxResults=$max_results&order=relevance";
	$video_request = "https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics,contentDetails&key=$api_key&maxResults=$max_results&order=relevance&id=%s";


	// perform an HTTP request on the youtube API to retrieve the playlist videos
	$playlist = json_decode(file_get_contents($playlist_request));
	$total = $playlist->pageInfo->totalResults;
	$videos = array();
	foreach ($playlist->items as $item)
		$videos[] = $item->contentDetails->videoId;
	// code below won't work in PHP 5.2
	// $videos = array_map(function($item) {return $item->contentDetails->videoId;}, $playlist->items);
	$items = json_decode(stripslashes(file_get_contents(sprintf($video_request, implode(',', $videos)))))->items;

	// collect video IDs from the next pages
	for ($i = 1; $i * $max_results < $total; $i++) {
		$next_page_token = $playlist->nextPageToken;
		$playlist = json_decode(file_get_contents($playlist_request.'&pageToken='.$next_page_token));
		$videos = array();
		foreach ($playlist->items as $item)
			$videos[] = $item->contentDetails->videoId;
		// code below won't work in PHP 5.2
		// $videos = array_map(function($item) {return $item->contentDetails->videoId;}, $playlist->items);
		$next_items = json_decode(file_get_contents(sprintf($video_request, implode(',', $videos))))->items;
		$items = array_merge($items, $next_items);
	}

	return $items;
}
// json('videos', amplugged_videos());
// $videos = amplugged_videos();

?>
<!DOCTYPE html>
<html>
	<head>
		<?php globalLinks(); ?>
		<?php source('amplugged.css'); ?>
		<?php source('amplugged.js'); ?>
		<title>Ateneo Musicians' Pool - AMPlugged</title>
	</head>
	<body id='fullscreen'>
		<?php createHeader('AMPlugged'); ?>
		<div id='container'>
			<section class='amp-bg fixed' src='<?= get(json('meta'), 'amplugged-bg') ?>'></section>
			<section>
				<div id='amplugged' class='wrapper'>
					<div id='amplugged-top'>
						<!-- New AMPlugged Channel (playlist ver) -->
						<iframe id='video-main' src='https://www.youtube.com/embed?list=UUB5siGRAwfqbcOzP4ZrTK2w&autohide=1&enablejsapi=1&listType=playlist&autoplay=0&showinfo=1&theme=light&index=<?= count(json('videos')) - 1 ?>' frameborder='0' allowfullscreen>
						</iframe>
						<!-- Old AMPLUGGED Playlist
						<iframe id='video-main' src='https://www.youtube.com/embed/RzvLuyA87ss?list=PLR2k5ELr5FgVJ-dXsvbnkoHeAN3kOVNB_&autohide=1&enablejsapi=1&listType=playlist&autoplay=0&showinfo=1&theme=light&index=<?= count(json('videos')) - 1 ?>' frameborder='0' allowfullscreen>
						</iframe>
						-->
						
						<div id='amplugged-description'>
							<div class='heading'>
								<img src='images/icons/amplugged.jpg' alt='AMPlugged Sessions'>
							</div>
							<div class='content'></div>
						</div> 
					</div>
					<div id='amplugged-bottom'>
						<div class='heading'>
							MORE VIDEOS
						</div>
						<div class='content'>
							<div id='next-video' class='arrow right'></div>
							<div id='prev-video' class='arrow left'></div>
							<div id='video-sel'>
								<div class='video'>
									<img class='video-thumbnail'>
									<div class='video-description'>
										<a class='video-url' href='' target='_blank'>
											<span class='video-title'></span>
										</a>
										<span class='video-views'></span>
										<span class='video-date'></span>
									</div>
								</div>
							</div>
							<div id='video-nav'>

							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</body>
</html>

<!-- iframe version -->
<!--
<br>
<iframe width='800' height='450' src="https://www.youtube.com/embed/RzvLuyA87ss?list=PLR2k5ELr5FgVJ-dXsvbnkoHeAN3kOVNB_&enablejsapi=1&listType=playlist&autoplay=1&showinfo=1&theme=light&index=<?php echo rand(1, 17);?>" frameborder="0" allowfullscreen>
</iframe>
<br><br><a href='https://www.youtube.com/watch?list=PLR2k5ELr5FgVJ-dXsvbnkoHeAN3kOVNB_&v=UXQaT-42QmI'><h1>Visit us on YouTube</h1></a>-->