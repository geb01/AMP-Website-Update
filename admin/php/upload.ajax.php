<?php

require_once '../../php/global.php';

$FILE_SIZE_LIMIT = 20;
$MB = 1024 * 1024;


try {

	if (!authenticated()) die('ERROR: not authenticated');

	if (!isset($_FILES['file'])) die('ERROR: no file found');

	if (!isset($_FILES['file']['type'])) die('ERROR: no file type');

	$file = $_FILES['file'];
	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	
	if (!in_array($ext, array('jpeg', 'jpg', 'png')))
		die('ERROR: invalid file type');

	if (!in_array($file['type'], array('image/jpeg', 'image/jpg', 'image/png')))
		die('ERROR: the file image compression looks corrupt. Try uploading a more lossless image, preferrably in PNG format.');

	if ($file['size'] >= $FILE_SIZE_LIMIT * $MB)
		die("ERROR: exceed file size limit of $FILE_SIZE_LIMIT MB");

	if ($file['error'] > 0)
		die("ERROR: return code $file[error]");

	$upload_dir = get($_POST, 'upload_dir', '');
	if ($upload_dir != '' && !ends_with($upload_dir, '/'))
		$upload_dir .= '/';

	$source = $file['tmp_name'];
	$destination = "/images/$upload_dir".uniqid().'.'.$ext;
	move_uploaded_file($source, $_SERVER['DOCUMENT_ROOT'].$destination);
	die($destination);

} catch (Exception $ex) {
	die('ERROR: exception occured');
}