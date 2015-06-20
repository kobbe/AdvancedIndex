<html>
<body>

File to be tailed is <?php echo $_POST["fileName"]; ?><br>
Tail number is: <?php echo $_POST["tail"]; ?><br>

<?php

//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);

echo "<pre>";
$out = array();
$file = escapeshellarg($_POST["fileName"]);
$num = escapeshellarg($_POST["tail"]);
exec('tail -n '.$num.' '.'/var/www'.$file,$out);
foreach($out as $line){
	echo $line;
	echo "<br>";
}
echo "</pre>";

?>

</body>
</html> 
