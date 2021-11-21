/*
	Ateneo Musician's Pool
	by Rico Tiongson
	
	All rights reserved for Ateneo Musician's Pool Design commitee.
	Ateneo Musician's Pool Design commitee members, including alumni,
	have full ownership of this code. Transfer of ownership is only
	through jurisdiction of both the author and the organization.

	Uncited referencing of this code will be considered plagiarized.
	Full documentation will be published once available.

	(c) 2015-2016
*/

// this script uses the Youtube IFrame Player API
// @see https://developers.google.com/youtube/iframe_api_reference

// load the iframe player API code asynchronously
var tag = document.createElement('script');
tag.src = 'https://www.youtube.com/iframe_api';

var firstScript = document.getElementsByTagName('script')[0];
firstScript.parentNode.insertBefore(tag, firstScript);

// Create video listener on click so that the
// the amplugged video icons outside will also update on its own
var player;
function onYouTubePlayerAPIReady() {

	// prepare the player
	player = new YT.Player('video-main', {
		events: {
			onStateChange: change
		}
	});

	// prepare custom display events
	var videoSel = $('#video-sel');
	videoSel.on('click', '.video-thumbnail', function() {
		var video = $(this).closest('.video');
		if (video.hasClass('selected')) return;
		var id = video.attr('id');
		var index = getIndex(id);
		select(index);
		player.playVideoAt(index);
	});

	// navigate prev videos
	var prevVideo = $('#prev-video');
	prevVideo.click(function() {
		var lead = getIndex(videoSel.children('.video:not(.hidden):first').attr('id'));
		showGroup(lead + videosDisplayed);
	});

	// navigate prev videos
	var nextVideo = $('#next-video');
	nextVideo.click(function() {
		var lead = getIndex(videoSel.children('.video:not(.hidden):last').attr('id'));
		showGroup(lead - videosDisplayed);
	})

	var videosDisplayed = 3;
	var firstPlay = true;
	var videos = null;

	// retrieve first cache
	$.post('/php/amplugged-video-request.ajax.php', {
		cache: true
	}, function(data) {
		console.log('Set AMPlugged video info from cache');
		videos = JSON.parse(data);
		update();
		select(videos.length - 1);
	});

	function loading(show) {
		// TODO: show loading gif
	}

	// convert id to index
	function getIndex(id) {
		for (var i = 0; i < videos.length; ++i)
			if (videos[i]['id'] == id)
				return i;
		return -1;
	}

	// select the video by index and show its neighbors
	function select(index) {
		if (index >= 0 && index < videos.length) {
			var children = videoSel.children('.video');;
			children.filter('.selected').removeClass('selected');
			var selected = $(children[index]).addClass('selected');
			showGroup(index);
			var header = $('.video-description', selected).clone();
			// header.css('font-size', '1.3em');
			var content = '<span>' + videos[index]['snippet']['description'].replace(/\n/g, '<br>') + '</span>';
			$('#amplugged-description .content').html('').append(header.add(content));
		}
	}

	// have concurrency measures for showGroup and update
	var deferredUpdate = false;
	var showGroupLatch = 0;
	var updating = 0;

	// show the group of a certain video by index
	function showGroup(index) {
		if (!showGroupLatch && !updating && index >= 0 && index < videos.length) {

			++showGroupLatch;

			var children = videoSel.children('.video');
			var rev = videos.length - index - 1;
			var right = ~~(rev / 3) * 3;
			var left = Math.min(right + videosDisplayed, videos.length);
			left = videos.length - left;
			right = videos.length - right;
			var show = children.slice(left, right).filter('.hidden');
			var hide = children.slice(0, left).add(children.slice(right)).filter(':not(.hidden)');

			var prevHeight = videoSel.height();
			hide.addClass('hidden');
			show.removeClass('hidden');
			var nextHeight = videoSel.height();

			// add to the latch
			showGroupLatch += videoSel.length + hide.length + show.length - 1;


			videoSel.height(prevHeight).animate({
				height: nextHeight
			}, 600, function() {
				$(this).css('height', '');
				if (--showGroupLatch == 0 && deferredUpdate) {
					deferredUpdate = 0;
					update();
				}
			});

			hide.removeClass('hidden').height(prevHeight).animate({
				width: 0,
				height: nextHeight,
				marginRight: 0,
				fontSize: 0
			}, 600, function() {
				$(this).addClass('hidden').css({
					width: '',
					height: '',
					marginRight: '',
					fontSize: ''
				});
				if (--showGroupLatch == 0 && deferredUpdate) {
					deferredUpdate = 0;
					update();
				}
			});

			show.width(0).height(prevHeight).animate({
				width: '100%',
				height: nextHeight
			}, 600, function() {
				$(this).css({
					width: '',
					height: ''
				});
				if (--showGroupLatch == 0 && deferredUpdate) {
					deferredUpdate = 0;
					update();
				}
			});
		}
	}

	// convert time to duration
	function duration(then) {
		var ms = new Date().getTime() - then.getTime();
		var s = ~~(ms / 1000);
		var m = ~~(s / 60);
		var h = ~~(m / 60);
		var d = ~~(h / 24);
		var w = ~~(d / 7);
		var M = ~~(d / 30);
		var Y = ~~(d / 365);
		var tf, value;
		if (Y) tf = 'year', value = Y;
		else if (M) tf = 'month', value = M;
		else if (w) tf = 'week', value = w;
		else if (d) tf = 'day', value = d;
		else if (h) tf = 'hour', value = h;
		else if (m) tf = 'minute', value = m;
		else tf = 'second', value = s;
		return value + ' ' + tf + (value > 1 ? 's' : '') + ' ago';
	}

	function update() {
		if (showGroupLatch > 0) {
			deferredUpdate = true;
			return;
		}

		deferredUpdate = false;
		++updating;
		// updates the information in the "MORE VIDEOS" section
		var videoSel = $('#video-sel');
		var videosNav = $('#video-nav');
		var videoMap = {};
		// TODO: show loading
		loading(true);

		var children = videoSel.children('.video');

		$.each(videos, function(index, item) {
			if (index >= children.length) {
				var newElem = $(children[0]).clone();
				videoSel.append(newElem);
				children = children.add(newElem);
			}
			var child = $(children[index]);
			// TODO: modify child
			$('.video-url', child).attr('href', 'https://youtu.be/' + item['id']);
			$('.video-thumbnail', child).attr('src', item['snippet']['thumbnails']['medium']['url']);
			$('.video-title', child).html(item['snippet']['title']);
			$('.video-views', child).html(item['statistics']['viewCount'] + ' views');
			child.attr('id', item['id']);
			var publishedAt = Date.parse(item['snippet']['publishedAt']);
			$('.video-date', child).html(duration(new Date(publishedAt)));
		});

		for (var i = videos.length; i < children.length; ++i)
			$(children[i]).remove();

		--updating;

		// var selected = videoSel.children('.video.selected');
		// if (selected.length)
			// select(getIndex(selected.attr('id')));
		// else
			// select(videos.length - 1);
		loading(false);

	}

	// change listener to youtube video player
	function change(e) {
		
		// synchronize custom display's selected video with youtube player's
		select(player.getPlaylistIndex());

		// reupdate cache on first play
		if (firstPlay && e.data == YT.PlayerState.PLAYING) {
			// update server cache on first play
			firstPlay = false;
			var query = player.getPlaylist().join(',');
			$.post('/php/amplugged-video-request.ajax.php', {
				id: query
			}, function(data) {
				console.log('Updated AMPlugged video info');
				// console.log(data);
				videos = JSON.parse(data);
				update();
			});
		}

	};

}


