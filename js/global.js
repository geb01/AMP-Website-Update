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



$(document).ready(function() {
	var loc = window.location.href;
	var hash = window.location.hash;
	var tag = hash.length ? loc.length - hash.length : loc.length;
	var current = loc.substring(loc.lastIndexOf('/') + 1, tag);
	window.filename = current;
	backToTop();
	adjustHeader();
	adjustDividers();
	adjustFeatures();
	runSlideshows();
	allowParallaxBackground();
	allowHrefScroll();
	allowDropdown();
	$('div[src],.divider[src],.amp-bg[src]').each(function() {
		var $this = $(this);
		var src = $this.attr('src');
		if (src.length) {
			$this.css('background-image', 'url(' + src + ')');
			$this.removeAttr('src');
		}
	});
});

$.fn.reverse = [].reverse;

/*
	algorithm: select text
	parameters: none
	returns: none
	info: selects editable text in jquery object
*/

$.fn.selectText = function(){
	var doc = document;
	var element = this[0];
	//console.log(this, element);
	if (doc.body.createTextRange) {
		var range = document.body.createTextRange();
		range.moveToElementText(element);
		range.select();
	} else if (window.getSelection) {
		var selection = window.getSelection();
		var range = document.createRange();
		range.selectNodeContents(element);
		selection.removeAllRanges();
		selection.addRange(range);
	}
};


function backToTop() {
	var back = $("<a id='back-to-top' href='" + window.filename + "#'>▲ Back to top</a>").appendTo('#container');
	var $window = $(window);
	var toTop = function() {
		if ($window.scrollTop() > 100)
			back.removeClass('hidden');
		else
			back.addClass('hidden');
	};
	$window.scroll(toTop);
	toTop();
}

/*
	algorithm: allow dropdown
	parameters: none
	returns: none
	info: enables toggling of dropdown buttons to show hidden menu
*/

function allowDropdown() {
	$('.dropdown-button').click(function() {
		var button = $(this).find('.arrow');
		var drop = $(this).parent().find('.hidden-dropdown');
		if (button.hasClass('selected')) {
			drop.slideUp();
			button.removeClass('selected');
		} else {
			drop.slideDown();
			button.addClass('selected');
		}
	});
}

/*
	algorithm: allow parallax background
	parameters: none
	returns: none
	info: enables mouse parallax effect for divs with background images with class='parallax'
*/

function allowParallaxBackground() {
	$('.parallax').each(function() {
		var slide = $(this);
		var scale = 1.013;
		if ($(this).is('[scale]')) 
			scale = parseFloat($(this).attr('scale'));
		transform(slide, 'scale(' + (1 + (scale - 1) * 2) + ')');
		// add parallax effect for mobile devices
		if (window.DeviceMotionEvent) {
			window.ondevicemotion = function(e) {
				if (slide.hasClass('selected')) {
					var my = e.accelerationIncludingGravity.y;
					var dy = -(my + 10) * (scale);
					slide.css('background-position-y', dy + 'px ');
				}
			}
		}
		// add parallax effect for desktop applications 
		$(document).mousemove(function(e) {
			if (slide.hasClass('selected')) {
				var mx = e.pageX;
				var my = e.pageY - $(window).scrollTop();
				// move slowly with respect to time
				var dx = -mx * (scale - 1);
				var dy = -my * (scale - 1);
				slide.css('background-position', dx + 'px ' + dy + 'px');
			}
		});
	});
}

/*
	algorithm: run slideshows
	parameters: none
	returns: none
	info: runs timeouts for all slideshows in the page
*/

function runSlideshows() {

	if ($('.slideshow').length == 0) return;

	// enable slide selection via clicking event
	$('.slider>*').click(function() {
		if ($(this).hasClass('selected')) return;
		var index = $(this).attr('index');
		var slideshow = $(this).parent().parent();
		slideshow.find('.selected').removeClass('selected');
		slideshow.find('[index=' + index + ']').addClass('selected');
		$(document).mousemove(); // for the parallax
	});

	// start slideshow by clicking first child
	if ($('.slider>span').length > 0)
		$('.slider>span')[Math.floor(Math.random() * $('.slider>span').length)].click();
	setInterval(function() {
		$('.slideshow').each(function() {
			var slider = $(this).children('.slider');
			if (slider.is(':hover')) return;
			var selected = slider.children('.selected');
			var next = selected.next();
			if (next.length) next.click();
			else slider.children(':first-child').click();
		});
	}, 10000);
}

/*
	algorithm: allow href scroll
	parameters: none
	returns: none
	info: sets up smooth scrolling for elements with href attribute
	courtesy of https://css-tricks.com/snippets/jquery/smooth-scrolling/
*/

function allowHrefScroll() {
	var scroller = $('html,body');
	// enable scrolling animation for within-page clicks
	$('[href*="' + window.filename + '#"]').click(function(e) {
		if (e.button != 0) return;
		e.preventDefault();
		var href = $(this).attr('href').substring(window.filename.length + 1);
		var top = href.length ? $('#' + href).offset().top : 0;
		scroller.animate({scrollTop: top}, 400, 'swing', function() {window.location.hash = '#' + href;});
	});

	// replace all index.php to ./
	$('a[href$="index.php"]').each(function() {
		var href = $(this).attr('href').replace('index.php', './');
		$(this).attr('href', href);
	});

	// scroll to appropriate within-page link at the start
	var index = window.location.href.lastIndexOf('#');
	if (index != -1) {
		var name = window.location.href.slice(index + 1);
		if (name.length) {
			var offset = $('section#' + name).offset().top;
			scroller.animate({scrollTop: offset}, 1);
		}
	}
}

/*
	algorithm: adjust features
	parameters: none
	returns: none
	info: adjusts the elements of the feature class
*/

function adjustFeatures() {

	// anchor feature-description boxes to the bottom and make it float
	var featureWrapper = $('.feature-wrapper');
	function anchor() {
		$('.selected', featureWrapper).each(function() {
			var display = $(this);
			var pic = $('.feature-pic', display);
			var box = $('.feature-description', display);
			box.css('overflow', 'visible');
			var child = box.children('div');
			var bh = parseFloat(child.outerHeight());
			box.css('bottom', '');
			if (pic.height() + bh > display.height())
				box.css('top', (display.height() - bh) + 'px');
			else
				box.css('top', pic.height() + 'px');
		});
	}

	// display new information on click
	$('.feature-labels>*').click(function() {
		var sel = $(this).closest('.feature').find('.selected');
		if (sel.length && sel.attr('index') == $(this).attr('index')) return;
		if (sel.length) sel.removeClass('selected');
		$('[index=' + $(this).attr('index') + ']', $(this).closest('.feature')).addClass('selected');
		anchor();
	});

	// set-up user-defined ancher on resize
	$(window).resize(anchor);

	// show the first label
	setTimeout(function() {$('.feature-labels>*:first-child').click()}, 300);

	// set up a transition sequence
	$('.feature').each(function() {
		var feature = $(this);
		var featureLabels = $('.feature-labels', feature);
		setInterval(function() {
			if (feature.is(':hover')) return;
			var selected = $('.selected', featureLabels);
			var next = selected.next();
			if (next.length) next.click();
			else featureLabels.children().first().click();
		}, 7000);
	});

}

/*
	algorithm: adjust header
	parameters: none
	returns: none
	info: adjusts the dimensions of header items
*/

function adjustHeader() {

	var header = $('header');
	var menuButton = $('.menu-button', header);
	var menuLabel = $('#menu-label', header);
	var ampLogo = $('.amp-logo', header);
	var searchBar = $('#search-bar', header);
	var hiddenMenu = $('#menu', header);
	var widgetContainer = $('#widget-container', header);

	// open menu when clicked
	menuButton.add(menuLabel).click(function() {
		hiddenMenu.toggleClass('hidden');
	});

	// close menu upon clicking document outside of menu
	$('html').click(function() {
		if (!hiddenMenu.hasClass('hidden') && !hiddenMenu.is(':hover') && !menuButton.is(':hover') && !menuLabel.is(':hover'))
			hiddenMenu.addClass('hidden');
	});

	// create widgets for each section with an id
	var sections = $('section[id]');
	if (sections.length) {

		// create widgets for widget container
		sections.each(function() {
			var name = $(this).attr('id');
			widgetContainer.append("<a class='widget' href='" + window.filename + "#" + name + "'><div>" + name.replace('-', ' ') + "</div></a>");
		});

		// mark widget whenever territory was scrolled
		var widgets = $('.widget', widgetContainer);
		widgets.first().addClass('selected');
		var curFrame = 0;

		// update widget on scroll
		$(window).scroll(function() {
			var scrollY = $(this).scrollTop() + window.innerHeight / 2;
			// walk through the frames
			var prevFrame = curFrame;
			while (curFrame + 1 < sections.length && sections[curFrame].offsetTop < scrollY)
				++curFrame;
			while (curFrame > 0 && sections[curFrame].offsetTop > scrollY)
				--curFrame;
			if (prevFrame != curFrame) {
				$(widgets[prevFrame]).removeClass('selected');
				$(widgets[curFrame]).addClass('selected');
			}
		});
		// invoke function immediately
		$(window).scroll();
	}

	// focus on scrollbar input upon hover
	searchBar.hover(function() {
		$('input', $(this)).focus();
	});

}

/*
	algorithm: adjust dividers
	parameters: none
	returns: none
	info: adjusts the widths and content of all dividers in the document
*/

function adjustDividers() {
	$('.divider-container').each(function() {
		var container = this;
		var dividers = $(this).children('.divider');
		dividers.css('width', 100.0 / dividers.length + '%');

		// bind scroll events
		var left = $(this).find('.left');
		var right = $(this).find('.right');

		// function for when scrollbars should show up
		function checkScrolling() {
			if (container.offsetWidth < container.scrollWidth) {
				// there's overflow
				// check if left scroller is needed
				if (container.scrollLeft > 0) {
					left.css('left', container.scrollLeft + 'px');
					left.fadeIn(300);
				} else left.fadeOut(300);

				// check if right scroller is needed
				if (container.scrollLeft + container.offsetWidth < container.scrollWidth) {
					right.css('right', -container.scrollLeft + 'px');
					right.fadeIn(300);
				} else
					right.fadeOut(300);
			} else {
				// no need for scrollers
				left.fadeOut(300);
				right.fadeOut(300);
			}
		}

		// handle scrollbar visibility even after window resize
		$(window).resize(checkScrolling);
		// $(container).scroll(checkScrolling);

		// handle actual scrolling when scrollers are clicked
		$('.left.scroller').click(function() {
			var D = Math.max(0, container.scrollLeft - container.offsetWidth / 2);
			if (Math.abs(container.scrollLeft - D) < Number.EPSILON)
				return;
			$(container).animate({scrollLeft: D}, 300, checkScrolling);
			left.animate({left : D}, 300);
			right.animate({right: -D}, 300);
		});
		$('.right.scroller').click(function() {
			var D = Math.min(container.scrollWidth - container.offsetWidth, container.scrollLeft + container.offsetWidth / 2);
			if (Math.abs(container.scrollLeft - D) < Number.EPSILON)
				return;
			$(container).animate({scrollLeft: D}, 300, checkScrolling);
			left.animate({left : D}, 300);
			right.animate({right: -D}, 300);
		});

		// check for scrolling at the start
		setTimeout(checkScrolling, 300);
	});
}

/*
	algorithm: transform
	paramters:
		element - html element you want to transform
		argument - value of transform
	returns: none
	info: allows convenience for multi-platform CSS3 transform
*/

function transform(element, argument) {
	element.css({
		'-webkit-transform': argument,
		'-ms-transform': argument,
		'transform': argument
	});
}

/*
	algorithm: create context menu
*/

function createContextMenu(list, owner) {
	// create lone context menu if does not exist
	var menu = $('#context-menu');
	if (!menu.length)
		menu = $("<div id='context-menu' />").appendTo('body');

	menu.empty();
	

	// align context menu on mouse position
	menu.css({
		left: owner.hasOwnProperty('pageX') ? owner.pageX : $(owner).offset().left + $(owner).width(),
		top: owner.hasOwnProperty('pageY') ? owner.pageY : $(owner).offset().top + $(owner).height()
	});

	// add entries from list
	list.forEach(function(entry) {
		var item = $('<div>' + ($.isFunction(entry[0]) ? entry[0]() : entry[0]) + '</div>').appendTo(menu);
		item.click(function() {entry[1](); menu.addClass('hidden');});
	});

	// global variable for state of context menu
	window.contextMenuState = true;

	$('html').unbind('click.contextmenu');
	// unbind then bind
	$('html').bind('click.contextmenu', function() {
		if (window.contextMenuState) {
			// show
			if (!owner.hasOwnProperty('pageX'))
				owner.addClass('selected');
			menu.removeClass('hidden');
			window.contextMenuState = false;
		} else {
			// hide
			if (!owner.hasOwnProperty('pageX'))
				owner.removeClass('selected');
			menu.addClass('hidden');
		}
	});
}