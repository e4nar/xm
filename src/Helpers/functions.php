<?php


/**
 * Return a string containing the modified datetime
 * of a specified asset file (usually js,css) in order
 * to force browser to download the file and overcome
 * any caching issues
 *
 * @param $filepath Filepath to an asset file
 * @return string
 */
function getModifiedDate($filepath) {
	
	$file = public_path($filepath);
	
	return '?lm='.date ("YmdHis", filemtime($file));
	
}