<?php

//Index page made by Hans Koberg - hans@koberg.nu - 2015
//
//built from the (crap coding) Simple Index - sindex.php made by Joni Rantala 2011

main();

function main() {

	$path      = !empty($_GET["p"]) ? "{$_GET["p"]}" : "./"; #TODO: bullshit location if wrong!
	$sort      = !empty($_GET["s"]) && in_array($_GET["s"], array("n", "s", "m")) ? $_GET["s"] : "n";
	$direction = !empty($_GET["d"]) && $_GET["d"] == "d" ? SORT_DESC : SORT_ASC;

	header("Content-Type: text/html; charset=utf-8");
	printHeader($path);
	printFileListing(
		$path,
		"*",
		array(
			basename(__FILE__)
		),
		$sort,
		$direction
	);
	printFooter();
}

function printHeader($path) {
?>
<!DOCTYPE html>
<html>
<head>
<title>Index of <?php echo $path; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="generator" content="Index page made by Hans Koberg with base by Joni Rantala">
<style type="text/css">
body         { margin: 20px; padding: 0; background: #cccccc; }
a            { color: #0088ff; }
a:visited    { color: #8800aa; }
a img        { border: none; }
body, td, th { font-family: sans-serif; font-size: 12pt; text-align: left; vertical-align: top; color: #333333; }
h1           { font-size: 16pt; text-align: center; }
h1 a         { color: #000000 !important; text-decoration: none; }
p            { text-align: center; font-size: 9pt; }
p a          { color: #666666 !important; }
table        { margin: 0 auto; border-collapse: collapse; border: 5px solid #ffffff; min-width: 400px; }
th, td       { padding: 5px 10px; }
th           { background: #888888; color: #ffffff; }
th a         { color: #ffffff !important; text-decoration: none; }
th img       { position: relative; top: -3px; left: 2px; }
td           { border-bottom: 1px dotted #cccccc; background: #ffffff; }
tr.odd td    { background: #f5f5f5; }
small        { font-size: 9pt; }
</style>
</head>
<body>
<?php
}

function printFooter() {
?>
</body>
</html>
<?php
}

function currentPath() {
	return rtrim(dirname($_SERVER["SCRIPT_NAME"]) . "/" . (empty($_GET["p"]) ? "" : ltrim($_GET["p"], "/")) , "/");
}

function parentDirectory($path) {
	//$path = currentPath();
	$path = substr($path, 0, strrpos($path, "/"));

	return $path == "" ? "/" : $path;
}

function formatSize($size) {
    $units = array("B", "K", "M", "G", "T");
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2) . " " . $units[$i];
}

function printFileListing($path = "./", $pattern = "*", $excluded = array(), $sort = "n", $direction = SORT_ASC) {
    $pathNoSlash = rtrim($path,"/")

	$files = glob($_SERVER["DOCUMENT_ROOT"] . $path . $pattern);
	
	$sizes      = array();
	$timestamps = array();
	
	if (!is_array($files)) $files = array();
	
	foreach ($files as $index => $file) {
		foreach ($excluded as $regexPattern) {
			if (preg_match("@$regexPattern@", $file)) {
				unset($files[$index]);
				continue 2;
			}
		}
	
		$sizes[]      = filesize($file);
		$timestamps[] = filemtime($file);
	}
	
	switch ($sort) {
	// size
	case "s":
		array_multisort($sizes, $direction, $files, $timestamps);
		break;
	// modified
	case "m":
		array_multisort($timestamps, $direction, $files, $sizes);
		break;
	// name
	case "n":
	default:
		array_multisort($files, $direction, $sizes, $timestamps);
		break;
	}
			
	$iconFile = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAA9QTFRFAAAA5PX6yez1/////////SHUhAAAAAV0Uk5T/////wD7tg5TAAAAQUlEQVR42mJgQQMMIAwFCAEmJkZGRgZmBnQBsAhEAKieEaoJoQKkCLcAxBZGyrQwoWuBiDCg+QXuMAzfogCAAAMAPGcCZJ/5Pw0AAAAASUVORK5CYII=";
	$iconDir  = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABJQTFRF//IAAAAA8ucA////ysAA////eQEvdgAAAAZ0Uk5T//////8As7+kvwAAADtJREFUeNpiYEUDDKyMyAAswMLExAAFzIyMDCgKGJmBAkgKGOgrwIQsAHYpTAjkUlS/gAQwfIsGAAIMAN7QAc8Ckh4aAAAAAElFTkSuQmCC";
	$iconAsc  = "iVBORw0KGgoAAAANSUhEUgAAAAcAAAAECAMAAAB1GNVPAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRF////////VXz1bAAAAAJ0Uk5T/wDltzBKAAAAGklEQVR42mJgZGRkgGAGBhABohigJBAABBgAATAADUnnWMkAAAAASUVORK5CYII=";
	$iconDesc = "iVBORw0KGgoAAAANSUhEUgAAAAcAAAAECAMAAAB1GNVPAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRF////////VXz1bAAAAAJ0Uk5T/wDltzBKAAAAF0lEQVR42mJggAJGKMkIYjCCaDAGCDAAAJAADcpaiWkAAAAASUVORK5CYII=";
	
	#$parent = explode("/", currentPath());
	echo "<h1><a href=\"" . $pathNoSlash . "\">Index of " . $pathNoSlash . "</a></h1>\n";
	echo "<p><a href=\"" . parentDirectory($path) . "\">&larr; Parent directory</a></p>\n";
	echo "<table>\n";
	echo "\t<tr>\n\t\t<th></th>\n";
	
	$labels = array(
		"n" => "Name",
		"s" => "Size",
		"m" => "Modified"
	);
	
	foreach ($labels as $key => $label) {
		$link  = "?s=$key";
		$image = "";
		
		if ($sort == $key) {
			$d      = $direction == SORT_DESC ? "a" : "d";
			$link  .= "&amp;d=$d";
			$icon   = $d == "a" ? $iconDesc : $iconAsc;
			$image  = " <img src=\"data:image/png;base64,$icon\" alt=\"\" />";
		}
		
		echo "\t\t<th><a href=\"$link\">$label$image</a></th>\n";
	}
	
	echo "\t</tr>\n";
    $class = "even";
	foreach ($files as $index => $file) {
		$name     = basename($file);
		$url      = $path . rawurlencode($name);
		$isDir    = is_dir($file);
		$type     = $isDir ? "Directory" : "File";
		$icon     = $isDir ? $iconDir    : $iconFile;
		$size     = $isDir ? "-"         : formatSize($sizes[$index]);
		$modified = date("Y-m-d H:i:s", $timestamps[$index]);
		$class    = $class == "even" ? "odd" : "even";
		
		echo "\t<tr class=\"$class\">\n" .
			 "\t\t<td><img src=\"data:image/png;base64,$icon\" title=\"$type\" alt=\"$type\" /></td>\n" .
			 "\t\t<td><a href=\"$url\">$name</a></td>\n" .
			 "\t\t<td><small>$size</small></td>\n" .
			 "\t\t<td><small>$modified</small></td>\n" .
			 "\t</tr>\n";
	}

	echo "</table>\n";
}
