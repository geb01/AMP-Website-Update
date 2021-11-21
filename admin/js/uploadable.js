$(function() {
	$(document).on('upload', 'img.uploadable', function() {
		var img = $(this);
		var form = img.next('.uploadable-form');

		if (!form.length)
			form = $("<form class='uploadable-form' enctype='multipart/form-data'>"
			+ "<input type='file' name='file' />"
			+ "<input type='text' name='upload_dir' value='' />"
			+ "</form>").insertAfter(img);

		var file = $('[name=file]', form);
		// var upload_dir = $('[name=upload_dir]', form);
		file.click();
	});

	var MAX_FILE_SIZE = 20;
	var MB = 1024 * 1024;

	$(document).on('change', '.uploadable-form>input[name=file]', function() {
		var self = $(this);
		var file = this.files[0];
		if (file.type != 'image/jpeg' && file.type != 'image/png' && file.type != 'image/jpg') {
			alert('File type mismatch. Must by *.(jpeg | jpg | png) only.');
			return false;
		}
		if (file.size > MAX_FILE_SIZE * MB) {
			alert('Image should be less than ' + MAX_FILE_SIZE + ' MB.');
			return false;
		}
		var reader = new FileReader();
		reader.onload = function(e) {
			self.parent().prev('.uploadable').attr('src', e.target.result);
		}
		reader.readAsDataURL(file);
	});

	window.uploadImages = function(context, uploadDir, done) {
		if (!done) done = function() {};
		if (!uploadDir) uploadDir = '';
		if (!context) context = $(document);
		
		// check first if there are uploaded images
		var uploaded = $(".img.uploadable[src^='data:']", context).next('.uploadable-form');	
		var queue = uploaded.length;
		
		// upload images through ajax before finally saving
		if (queue == 0) done();
		else {
			// show an uploading tab
			var uploading = $('<div class=button>Uploading...</div>');
			uploading.css({
				position: 'fixed',
				bottom: 0,
				right: 10
			});
			$('#container').append(uploading);

			uploaded.each(function() {
				var form = $(this);
				$('[name=upload_dir]', this).val(uploadDir);
				$.ajax({
					url: '/admin/php/upload.ajax.php',
					method: 'POST',
					data: new FormData(this),
					contentType: false,
					processData: false,
					success: function(data) {
						uploading.remove();
						if (data.startsWith('ERROR'))
							alert(data);
						else {
							form.prev().attr('src', data);
							if (--queue == 0) {
								uploading.remove();
								done();
							}
						}
					}
				});
			});
		}

	}
});