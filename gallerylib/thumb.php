<?php 
/**
 * set common headers for the request
 * @param string $filepath
 */
$commonheaders = function($filepath) {
	header ( "Content-type: " . mime_content_type ( $filepath ) );
	header ( 'Content-Length: ' . filesize ( $filepath ) );
};
/**
 * set cache headers for the request
 *
 * @param apache_request_headers $headers        	
 * @param string $filepath        	
 */
$cacheheaders = function ($headers, $filepath) {
	$filemtime = filemtime ( $filepath );
	if (isset ( $headers ['If-Modified-Since'] ) && (strtotime ( $headers ['If-Modified-Since'] ) == $filemtime)) {
		header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s', $filemtime ) . ' GMT', true, 304 );
	} else {
		header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s', $filemtime ) . ' GMT', true, 200 );
		readfile ( $filepath );
	}
};

try {
	$debug = false;
	$width = 30;
	$height = 0;
	$path_info = "";
	$headers = apache_request_headers ();
	if (isset ( $_SERVER ['ORIG_PATH_INFO'] )) {
		$path_info = $_SERVER ['ORIG_PATH_INFO'];
	} else {
		$path_info = $_SERVER ['REQUEST_URI'];
		if (strpos ( $path_info, "?" ) !== false)
			$path_info = reset ( explode ( "?", $path_info ) );
	}
	$image_name = basename ( $path_info );
	$context_path = dirname ( $path_info );
	$image_directory = $_SERVER ['DOCUMENT_ROOT'] . "/" . $context_path;
	$image_path = $_SERVER ['DOCUMENT_ROOT'] . "/" . $path_info;
	$thumb_path = $image_directory . "/" . "." . $image_name;
	if (!$debug) {
		if(!(strpos(mime_content_type($image_path), 'image') === 0)){
			http_response_code ( 500 );
			error_log ( __FILE__ . ' called for non image file');
			exit;
		}
		if (file_exists ( $thumb_path ) && file_exists ( $image_path ) && filemtime ( $thumb_path ) == filemtime ( $image_path )) {
			$commonheaders ( $thumb_path );
			$cacheheaders ( $headers, $thumb_path );
			exit ();
		} else {
			if (file_exists ( $image_path )) {
				$image = new Imagick ( $image_path );
				if (is_writable ( $thumb_path ) || is_writable ( $image_directory )) {
					$image->thumbnailImage ( $width, $height );
					$image->writeImage ( $thumb_path );
					touch ( $thumb_path, filemtime ( $image_path ) );
					$commonheaders ( $thumb_path );
					header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s', filemtime ( $thumb_path ) ) . ' GMT', true, 200 );
					readfile ( $thumb_path );
				} else {
					error_log ( $thumb_path . " is not writeable serving original file instead" );
					$commonheaders (  $image_path );
					$cacheheaders ( $headers, $image_path );
				}
				exit ();
			} else {
				http_response_code ( 500 );
			}
		}
	} else {
		echo $image_directory . " " . $image_name . " " . $context_path . " " . $image_path . " " . file_exists ( $image_path ) . " " . $thumb_path . " " . file_exists ( $thumb_path ) . "\n";
	}
} catch ( Exception $e ) {
	http_response_code ( 500 );
	error_log ( $e->getMessage () );
}
?>

